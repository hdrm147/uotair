<?php

namespace App\Http\Controllers;

use App\Airport;
use App\Flight;
use App\FlightClass;
use App\FlightRoute;
use App\Ticket;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class APIController extends Controller
{
    public function airports()
    {
        return Response::json([
            "airports" => Airport::whereHas("arrival_routes")->get(),
            "classes" => FlightClass::all(),
        ]);

    }

    public function getFlights(Request $request, $departureAirportId, $arrivalAirportId)
    {
        try {
            $requiredTicketNumber = $request->adults + $request->children;
            $roundTrip = filter_var($request->roundTrip, FILTER_VALIDATE_BOOLEAN);
            $route = FlightRoute::where("departure_airport_id", $departureAirportId)->where("arrival_airport_id", $arrivalAirportId)->firstOrFail();
            $departureDay = Carbon::createFromFormat("Y-m-d", $request->departureDate)->format("l");

            $returnDay = $request->returnDate ? Carbon::createFromFormat("Y-m-d", $request->returnDate)->format("l") : null;
            $departureDay = Str::lower($departureDay);
            /** @var Builder $flights */
            $flights = $route->flights()->where($departureDay, "=", true);
            if ($roundTrip && $returnDay) {
                $returnDay = Str::lower($returnDay);
                $flights = $flights->where($returnDay, "=", true);
            }
            $flights = $flights->whereHas("classes", function (Builder $query) use ($requiredTicketNumber, $request) {
                return $query->where("flight_class_id", "=", $request->flightClass)->selectRaw("flight_classes.id,name, capacity - (select count(*) from `tickets` where `departure_date` = '{$request->departureDate}' and `flights`.`id` = `tickets`.`flight_id` and `tickets`.`type` != 'infant' and `flight_classes`.`id` = `tickets`.`flight_class_id`) as remaining")->having("remaining", ">=", $requiredTicketNumber);
            });

            $flights = $flights->with(["classes" => function ($query) use ($request) {

                return $query->selectRaw("flight_classes.id,name, capacity - (select count(*) from `tickets` where `departure_date` = '{$request->departureDate}' and `flights_classes`.`flight_id` = `tickets`.`flight_id` and `tickets`.`type` != 'infant' and `flight_classes`.`id` = `tickets`.`flight_class_id`) as remaining");
            }, "flight_route"]);


            $flights = $flights->get();
            $flights = $flights->map(function ($flight) use ($request) {
                $flight->classes = $flight->classes->transform(function ($class) use ($request) {
                    $class->adult_fare = $class->getFareForDate($request->departureDate);
                    $class->children_fare = $class->adult_fare * ($class->pivot->children_fare_percentage / 100);
                    $class->number_of_bags = $class->pivot->number_of_bags;
                    $class->bag_weight_limit = $class->pivot->bag_weight_limit;
                    unset($class->pivot);
                    return $class;
                });

                return $flight;
            });
            return Response::json([
                "flights" => $flights
            ]);
        } catch (ModelNotFoundException $exception) {

        }

    }

    public function seats(Request $request, Flight $flight)
    {
        return Response::json([
            "seats" => $flight->online_seating,
            "classes" => $flight->safe_classes,
            "bookedSeats" => Ticket::where("flight_id", "=", $flight->id)->where("flight_class_id", "=", $request->flightClass)->where("departure_date", "=", $request->date)->where("type", "!=", "infant")->get()->pluck("seat"),
        ]);
    }

    public function book(Request $request, $flightId)
    {
        $query = Flight::with(["classes" => function ($query) use ($request) {
            return $query->selectRaw("flight_classes.id,name, capacity - (select count(*) from `tickets` where `departure_date` = '{$request->date}' and `flights_classes`.`flight_id` = `tickets`.`flight_id` and `tickets`.`type` != 'infant' and `flight_classes`.`id` = `tickets`.`flight_class_id`) as remaining");
        }, "flight_route"]);


        $flight = $query->find($flightId);
        $remaining = $flight->classes->find($request->flightClass)->remaining;

        $nonInfants = array_filter($request->passengers, function ($passenger) {
            return $passenger["type"] != "infant";
        });
        if ($remaining < count($nonInfants)) {
            return Response::json([
                "message" => "Cloud not proceed with the given data",
                "errors" => [
                    "There's only $remaining seats available on this flight"
                ]
            ])->setStatusCode(422);
        }
        $bookedSeats = Ticket::where("flight_id", "=", $flightId)->where("flight_class_id", "=", $request->flightClass)->where("departure_date", "=", $request->date)->get()->pluck("seat")->toArray();
        $rules = [
            "passengers" => "array|required",
            "passengers.*.seat" => $flight->online_seating ? "required_unless:passengers.*.type,infant" : "nullable",
            "passengers.*.type" => "required|in:child,adult,infant",
            "passengers.*.gender" => "required|in:male,female",
            "passengers.*.date_of_birth" => ["required", "date_format:Y-m-d", function ($attribute, $value, $fail) use ($request) {
                $index = Str::between($attribute, "passengers.", ".date_of_birth");
                $type = $request->passengers[$index]["type"];
                $age = Carbon::createFromFormat("Y-m-d", $value)->age;
                switch ($type) {
                    case "adult":
                        if ($age < 12) {
                            $fail("Passenger ticket type adult should be older than 12 years old");
                        }
                        break;
                    case "child":
                        if ($age < 2 || $age >= 12) {
                            $fail("Passenger ticket type child should be between 2 and 12 years old");
                        }
                        break;
                    case "infant":
                        if ($age >= 2) {
                            $fail("Passenger ticket type infant should be younger than 2 years old");
                        }
                        break;
                }
            }],
            "passengers.*.name" => "required",
            "email_address" => "required|email",
        ];
        $this->validate($request, $rules);
        if ($flight->online_seating) {
            $booking = [];
            foreach ($request->passengers as $passenger) {
                if ($passenger["seat"])
                    $booking[] = $passenger["seat"];
            }
            $alreadyBooked = array_intersect($booking, $bookedSeats);
            if (count($alreadyBooked) > 0) {
                $errors = [];
                foreach ($alreadyBooked as $index => $seat) {
                    $errors["passengers." . $index . ".seat"] = ["The seat $seat has been booked already"];
                }
                return Response::json([
                    "message" => "Cloud not proceed with the given data",
                    "errors" => $errors
                ])->setStatusCode(422);
            }


            $airplane_structure = json_decode(json_encode($flight->online_seating));

            $grid = collect($airplane_structure->grid);
            $cells = $grid->map(function ($row) {
                return $row->cells;
            })->flatten();
            array_filter($cells->toArray(), function ($cell) use ($airplane_structure, $booking, $request) {
                if (in_array($cell->seatNumber, $booking) && $cell->type != "no-seat" && $cell->type != "class-" . $request->flightClass) {
                    Response::json([
                        "cell" => $cell,
                        "grid" => $airplane_structure,
                        "message" => "Incorrect class chosen",
                        "errors" => ["The seat $cell->seatNumber is not in the same class you are trying to book in"]
                    ])->setStatusCode(422)->send();
                    die();
                }
            });
        }
        $booking_reference = Str::upper(Str::random(8));
        $tickets = [];
        /** @var FlightClass $class */
        $class = $flight->classes()->find($request->flightClass);
        $total = 0;
        foreach ($request->passengers as $passenger) {
            $fare = $class->getFareForDate($request->date, $passenger["type"] == "adult");
            $total += $fare;
            $ticket_data = [
                "name" => $passenger["name"],
                "date_of_birth" => $passenger["date_of_birth"],
                "type" => $passenger["type"],
                "gender" => $passenger["gender"],
                "seat" => $passenger["seat"],
                "flight_class_id" => $request->flightClass,
                "flight_id" => $flight->id,
                "departure_date" => $request->date,
                "booking_reference" => $booking_reference,
                "email_address" => $request->email_address,
                "fare" => $fare
            ];
            $tickets[] = Ticket::create($ticket_data);
        }
        return Response::json([
            "tickets" => $tickets,
            "total" => $total,
            "reference_id" => $booking_reference,
        ]);
    }

    public function getTickets(Request $request)
    {
        $id = Str::upper($request->referenceId);
        $tickets = Ticket::where("booking_reference", "=", $id)->where("email_address", "=", $request->email)->get();
        if ($tickets->count() == 0) {
            return Response::json([
                "message" => "There's no tickets with this reference id",
                "errors" => [
                    "referenceId" => ["Please make sure to enter correct reference id and email address"]
                ]
            ])->setStatusCode(404);
        }
        $ticket = $tickets->first();
        $flight = $ticket->flight;
        $class = $flight->safe_classes()->withPivot("id", "number_of_bags", "bag_weight_limit")->find($ticket->flight_class_id);
        return Response::json([
            "tickets" => $tickets,
            "flightClass" => $class,
            "flight" => $flight,
        ]);
    }
}
