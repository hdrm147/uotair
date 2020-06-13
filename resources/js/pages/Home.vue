<template>
    <v-container>
        <v-row class="container-margin fill-height">
            <v-col offset-md="1" offset-lg="2" offset-xl="2" md="6" lg="4" xl="3" sm="8" offset-sm="2" cols="12">
                <v-card>
                    <v-card-title>
                        Where Would You Like To Go?
                    </v-card-title>
                    <v-card-text class="pb-0 mt-4">


                        <v-form ref="form">

                            <v-autocomplete autocomplete="off"
                                outlined
                                item-text="name"
                                :items="departureAirports"
                                v-model="departureAirport"
                                :rules="[required]"

                                :filter="filterAirports"
                                label="Departure Airport"
                                placeholder="Your boarding point..."
                                prepend-icon="mdi-airplane-takeoff"
                                :hide-selected="true"
                                return-object
                            >
                                <template v-slot:item="data">
                                    <div class="my-2">
                                        <h6>
                                            {{data.item.name}} - {{data.item.code}}
                                        </h6>
                                        <span>{{data.item.city.name}}</span>
                                    </div>
                                </template>
                                <template v-slot:selection="data">
                                    <span>{{data.item.name}} - {{data.item.code}}</span>
                                </template>

                            </v-autocomplete>
                            <h4 class="dot mb-0 first-dot">·</h4>
                            <h4 class="dot my-n3">·</h4>
                            <h4 class="dot my-n3">·</h4>
                            <h4 class="dot my-n3">·</h4>
                            <h4 class="dot my-n3">·</h4>
                            <h4 class="dot mt-n3">·</h4>
                            <h4 class="dot mt-n3 last-dot">·</h4>
                            <v-autocomplete  autocomplete="off"
                                outlined
                                class="mt-6"
                                hide-details="auto"
                                :rules="[required]"
                                :items="arrivalAirports"
                                v-model="arrivalAirport"
                                item-text="name"
                                :hide-selected="true"
                                :filter="filterAirports"
                                label="Your Destination"
                                placeholder="Start typing to Search"
                                prepend-icon="mdi-airplane-landing"
                                return-object
                            >
                                <template v-slot:item="data">
                                    <div class="my-2">
                                        <h6>
                                            {{data.item.name}} - {{data.item.code}}
                                        </h6>
                                        <span>{{data.item.city.name}}</span>
                                    </div>
                                </template>
                                <template v-slot:selection="data">
                                    <span>{{data.item.name}} - {{data.item.code}}</span>
                                </template>


                            </v-autocomplete>
                            <v-row>
                                <v-col class="m-0 py-0">
                                    <v-checkbox hide-details="auto" class="m-0 p-0" v-model="roundTrip"
                                                label="Round Trip"></v-checkbox>

                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col>
                                    <v-menu
                                        ref="departureDateMenu"
                                        :close-on-content-click="false"
                                        :return-value.sync="departureDate"
                                        transition="scale-transition"
                                        offset-y
                                        min-width="290px"
                                    >
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-text-field
                                                outlined
                                                hide-details="auto"
                                                v-model="departureDate"
                                                :rules="[required]"

                                                label="Departure Date"
                                                prepend-icon="mdi-calendar"
                                                readonly
                                                v-bind="attrs"
                                                v-on="on"
                                            ></v-text-field>
                                        </template>
                                        <v-date-picker @input="$refs.departureDateMenu.save(departureDate)"
                                                       v-model="departureDate" scrollable>
                                        </v-date-picker>
                                    </v-menu>
                                </v-col>
                                <v-col v-if="roundTrip">
                                    <v-menu
                                        ref="returnDateMenu"
                                        :close-on-content-click="false"
                                        :return-value.sync="returnDate"
                                        transition="scale-transition"
                                        offset-y
                                        min-width="290px"
                                    >
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-text-field
                                                outlined
                                                hide-details="auto"

                                                v-model="returnDate"
                                                label="Return Date"
                                                prepend-icon="mdi-calendar"
                                                readonly
                                                v-bind="attrs"
                                                v-on="on"
                                            ></v-text-field>
                                        </template>
                                        <v-date-picker @input="$refs.returnDateMenu.save(returnDate)"
                                                       v-model="returnDate" scrollable>
                                        </v-date-picker>
                                    </v-menu>
                                </v-col>
                            </v-row>
                            <div class="ow">
                                <span class="font-weight-medium">Passengers</span>
                            </div>
                            <v-row>
                             <v-col cols="12" class="py-4 pl-6 pr-3">
                                <v-row>
                                    <v-col class="py-0 pl-0">
                                        <v-text-field
                                            label="Adults"
                                            outlined
                                            hide-details="auto"
                                            :rules="[isNumber]"
                                            prepend-icon="mdi-human-male"
                                            v-model.number="adults"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col class="py-0 pl-0">
                                        <v-text-field
                                            label="Children"
                                            outlined
                                            hide-details="auto"
                                            :rules="[isNumber]"
                                            prepend-icon="mdi-human-child"
                                            v-model.number="children"
                                        ></v-text-field>

                                    </v-col>
                                    <v-col class="py-0 pl-0">
                                        <v-text-field
                                            label="Infants"
                                            outlined
                                            hide-details="auto"
                                            :rules="[isNumber]"
                                            prepend-icon="mdi-account-child"
                                            v-model.number="infants"
                                        ></v-text-field>
                                    </v-col>
                                </v-row>
                             </v-col>
                            </v-row>
                            <v-row>
                                <v-col class="py-0 pt-2">
                                    <v-select  autocomplete="new"
                                        :items="classes"
                                        item-text="name"
                                        item-value="id"
                                        label="Flight Class"
                                        outlined
                                        prepend-icon="mdi-seat-passenger"
                                        hide-details="auto"
                                        v-model="flightClass"
                                    ></v-select>
                                </v-col>
                            </v-row>
                        </v-form>

                    </v-card-text>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn :loading="loading" @click="fetchAvailableFlights" text color="primary">Find Flights
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>


        </v-row>

        <v-row justify="center" class="big-padding">
            <v-col cols="12" sm="11" md="8">
                <v-card id="flights">
                    <v-card-text>
                        <v-form ref="bottomForm">
                            <v-row>
                                <v-col class="pt-0">
                                    <span class="subtitle-1">Filters</span>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12" sm="12" md="12" lg="6" xl="4" class="pb-0">
                                    <v-autocomplete  autocomplete="off"
                                        outlined
                                        item-text="name"
                                        :items="departureAirports"
                                        v-model="departureAirport"
                                        :rules="[required]"

                                        :filter="filterAirports"
                                        label="Departure Airport"
                                        placeholder="Your boarding point..."
                                        prepend-icon="mdi-airplane-takeoff"
                                        :hide-selected="true"
                                        return-object
                                    >
                                        <template v-slot:item="data">
                                            <div class="my-2">
                                                <h6>
                                                    {{data.item.name}} - {{data.item.code}}
                                                </h6>
                                                <span>{{data.item.city.name}}</span>
                                            </div>
                                        </template>
                                        <template v-slot:selection="data">
                                            <span>{{data.item.name}} - {{data.item.code}}</span>
                                        </template>

                                    </v-autocomplete>

                                </v-col>
                                <v-col cols="12" sm="12" md="12" lg="6" xl="4" class="pb-0">
                                    <v-autocomplete  autocomplete="off"
                                        outlined
                                        hide-details="auto"
                                        :rules="[required]"
                                        :items="arrivalAirports"
                                        v-model="arrivalAirport"
                                        item-text="name"
                                        :hide-selected="true"
                                        :filter="filterAirports"
                                        label="Your Destination"
                                        placeholder="Start typing to Search"
                                        prepend-icon="mdi-airplane-landing"
                                        return-object
                                    >
                                        <template v-slot:item="data">
                                            <div class="my-2">
                                                <h6>
                                                    {{data.item.name}} - {{data.item.code}}
                                                </h6>
                                                <span>{{data.item.city.name}}</span>
                                            </div>
                                        </template>
                                        <template v-slot:selection="data">
                                            <span>{{data.item.name}} - {{data.item.code}}</span>
                                        </template>


                                    </v-autocomplete>

                                </v-col>
                                <v-col cols="12" sm="12" md="roundTrip ? 6 : 12" lg="6" xl="2" class="pb-0">
                                    <v-menu
                                        ref="bottomDepartureDateMenu"
                                        :close-on-content-click="false"
                                        :return-value.sync="departureDate"
                                        transition="scale-transition"
                                        offset-y
                                    >
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-text-field
                                                outlined
                                                hide-details="auto"
                                                v-model="departureDate"
                                                :rules="[required]"

                                                label="Departure Date"
                                                prepend-icon="mdi-calendar"
                                                readonly
                                                v-bind="attrs"
                                                v-on="on"
                                            ></v-text-field>
                                        </template>
                                        <v-date-picker @input="$refs.bottomDepartureDateMenu.save(departureDate)"
                                                       v-model="departureDate" scrollable></v-date-picker>
                                    </v-menu>
                                </v-col>
                                <v-col cols="12" sm="12" md="12" lg="6" xl="2" class="pb-0" v-if="roundTrip">
                                    <v-menu
                                        ref="bottomReturnDateMenu"
                                        :close-on-content-click="false"
                                        :return-value.sync="returnDate"
                                        transition="scale-transition"
                                        offset-y
                                        min-width="290px"
                                    >
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-text-field
                                                outlined
                                                hide-details="auto"

                                                v-model="returnDate"
                                                label="Return Date"
                                                prepend-icon="mdi-calendar"
                                                readonly
                                                v-bind="attrs"
                                                v-on="on"
                                            ></v-text-field>
                                        </template>
                                        <v-date-picker @input="$refs.bottomReturnDateMenu.save(returnDate)"
                                                       v-model="returnDate" scrollable>
                                        </v-date-picker>
                                    </v-menu>
                                </v-col>

                            </v-row>

                            <v-row class="mt-4 mt-sm-4 mt-md-4 mt-xl-0" align="center">
                                <v-col class="pt-0" lg="2" cols="4">
                                    <v-text-field
                                        label="Adults"
                                        outlined
                                        hide-details="auto"
                                        :rules="[isNumber]"
                                        prepend-icon="mdi-human-male"
                                        v-model.number="adults"
                                    ></v-text-field>
                                </v-col>
                                <v-col class="pt-0 pl-0" lg="2" cols="4">
                                    <v-text-field
                                        label="Children"
                                        outlined
                                        hide-details="auto"
                                        :rules="[isNumber]"
                                        prepend-icon="mdi-human-child"
                                        v-model.number="children"
                                    ></v-text-field>

                                </v-col>
                                <v-col class="pt-0 pl-0" lg="2" cols="4">
                                    <v-text-field
                                        label="Infants"
                                        outlined
                                        hide-details="auto"
                                        :rules="[isNumber]"
                                        prepend-icon="mdi-account-child"
                                        v-model.number="infants"
                                    ></v-text-field>
                                </v-col>
                                <v-col class="pt-0" lg="6" cols="12">
                                    <v-select  autocomplete="new"
                                        :items="classes"
                                        item-text="name"
                                        item-value="id"
                                        label="Flight Class"
                                        outlined
                                        prepend-icon="mdi-seat-passenger"
                                        hide-details="auto"
                                        v-model="flightClass"
                                    ></v-select>
                                </v-col>
                            </v-row>
                            <v-row justify="end">
                                <v-col cols="auto" class="py-0">
                                    <v-btn :loading="loading" @click="fetchAvailableFlights" text color="primary">
                                        Find Flights
                                    </v-btn>
                                </v-col>
                            </v-row>
                            <v-row v-if="searched">
                                <v-col>
                                    <v-divider></v-divider>
                                </v-col>
                            </v-row>
                            <v-row v-if="searched">
                                <v-col class="pt-0">
                                    <span class="subtitle-1">Flights</span>
                                </v-col>
                            </v-row>

                        </v-form>
                        <template v-if="flights.length > 0">
                        <v-row v-for="(flight,index) in flights" :key="`flight-${flight.id}`">

                            <v-col cols="12" class="py-0">
                                <v-row>
                                    <v-col class="py-0">
                                            <span
                                                class="display-1 font-weight-light primary--text">{{flight.number}}</span>
                                    </v-col>
                                </v-row>
                                <v-row align="center">
                                    <v-col cols="12" xl="4">
                                        <v-row>
                                            <v-col cols="3" class="">
                                                <v-row justify="center">
                                                    <span class="title">{{flight.departure_time}}</span>
                                                </v-row>
                                                <v-row justify="center">
                                                    <span
                                                        class="title">{{flight.flight_route.departure_airport.code}}</span>
                                                </v-row>
                                            </v-col>
                                            <v-col cols="6" class="py-4">
                                                <v-row justify="center">
                                                    <span class="subtitle-1">{{flight.flight_route.duration}}</span>
                                                </v-row>
                                                <v-row align="center">
                                                    <v-col>
                                                        <v-divider></v-divider>
                                                    </v-col>
                                                    <v-col class="pa-0" cols="auto">
                                                        <v-icon class="rotate-90">mdi-airplane</v-icon>
                                                    </v-col>
                                                </v-row>
                                            </v-col>
                                            <v-col cols="3">
                                                <v-row justify="center">
                                                    <span class="title">{{flight.arrival_time}}</span>
                                                </v-row>
                                                <v-row justify="center">
                                                        <span
                                                            class="title">{{flight.flight_route.arrival_airport.code}}</span>
                                                </v-row>
                                            </v-col>
                                        </v-row>
                                        <v-row justify="center" justify-lg="end">
                                            <v-col cols="auto" class="pt-2 pb-0">
                                                <v-tooltip bottom>
                                                    <template v-slot:activator="{ on, attrs }">
                                                        <v-icon v-bind="attrs" v-on="on" v-if="flight.on_board_wifi"
                                                                class="mx-1">mdi-wifi
                                                        </v-icon>
                                                    </template>
                                                    <span>On-board WiFi</span>
                                                </v-tooltip>

                                                <v-tooltip bottom>
                                                    <template v-slot:activator="{ on, attrs }">
                                                        <v-icon v-bind="attrs" v-on="on" v-if="flight.meals"
                                                                class="mx-1">mdi-food-fork-drink
                                                        </v-icon>
                                                    </template>
                                                    <span>Provide Meals</span>
                                                </v-tooltip>

                                                <v-tooltip bottom>
                                                    <template v-slot:activator="{ on, attrs }">
                                                        <v-icon v-bind="attrs" v-on="on" v-if="flight.power_plug"
                                                                class="mx-1">mdi-power-plug
                                                        </v-icon>
                                                    </template>
                                                    <span>Seat Power Plug</span>
                                                </v-tooltip>

                                                <v-tooltip bottom>
                                                    <template v-slot:activator="{ on, attrs }">
                                                        <v-icon v-bind="attrs" v-on="on" v-if="flight.seat_display"
                                                                class="mx-1">mdi-television-play
                                                        </v-icon>
                                                    </template>
                                                    <span>Seat Display Available</span>
                                                </v-tooltip>

                                            </v-col>
                                        </v-row>
                                    </v-col>
                                    <v-col class="py-0">
                                        <v-row>
                                            <v-col v-for="(flightClass,index) in flight.classes"
                                                   :key="`class-${flight.id}-${flightClass.id}`" class="py-0">
                                                <v-row>
                                                    <v-col v-if="!$vuetify.breakpoint.lgAndDown || index != 0"
                                                           cols="auto" class="py-0">
                                                        <v-divider vertical></v-divider>
                                                    </v-col>
                                                    <v-col class="py-0">
                                                        <v-row justify="center">
                                                            <span class="display-2">  {{flightClass.name}}</span>
                                                        </v-row>
                                                        <v-row class="display-1 my-4" justify="center">
                                                            ${{flightClass.adult_fare}}
                                                        </v-row>
                                                        <v-row
                                                            v-if="flightClass.adult_fare !== flightClass.children_fare"
                                                            class="subtitle-1 my-2" justify="center">
                                                            ${{flightClass.children_fare}} For Children
                                                        </v-row>

                                                        <v-row justify="center">
                                                            <v-btn :disabled="flightClass.remaining === 0" @click="book(flight,flightClass)" text
                                                                   color="primary">Book Now
                                                            </v-btn>
                                                        </v-row>
                                                        <v-row v-if="flightClass.remaining < 10 && flightClass.remaining" class="mt-2" justify="center">
                                                            <v-alert dense color="red" class="white--text py-1 caption">
                                                                Only {{flightClass.remaining}} {{flightClass.remaining > 2 ? "Seats" : "Seat"}} available!
                                                            </v-alert>
                                                        </v-row>
                                                    </v-col>
                                                </v-row>
                                            </v-col>
                                        </v-row>

                                    </v-col>
                                </v-row>
                            </v-col>
                            <v-col v-if="index + 1 !== flights.length" cols="12" class="py-4">
                                <v-divider></v-divider>
                            </v-col>
                        </v-row>
                        </template>
                        <template v-else-if="searched">
                            <v-row justify="center">
                                <v-col cols="auto" class="display-1">
                                 <v-alert type="info" text prominent>
                                     <span class="title">No Flights Found Matching This Criteria</span>
                                 </v-alert>
                                </v-col>
                            </v-row>
                        </template>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>


    </v-container>
</template>

<script>
    import axios from 'axios'
    import Fuse from 'fuse.js'

    export default {
        name: "Home",
        data() {
            return {
                departureAirport: null,
                arrivalAirport: null,
                flightClass: null,
                adults: 1,
                infants: 0,
                children: 0,
                airports: [],
                fuse: null,
                departureDate: null,
                returnDate: null,
                roundTrip: true,
                loading: false,
                classes: [],
                flights: [],
                searched: false,
            }
        },
        methods: {
            book(flight, flightClass) {

                this.$router.push({
                    name: "book",
                    params: {
                        details: {
                            flight: flight,
                            flightClass: flightClass,
                            adults: this.adults,
                            children: this.children,
                            infants: this.infants,
                            departureAirport: this.departureAirport,
                            arrivalAirport: this.arrivalAirport,
                            returnDate: this.returnDate,
                            departureDate: this.departureDate,
                            roundTrip: this.roundTrip,
                        }
                    }
                })
            },
            required(value) {
                return !!value || `This field is Required`;
            },
            filterAirports(item, queryText, itemText) {

                let found = this.fuse.search(queryText).map(result => result.item);

                return found.includes(item) && (item != this.departureAirport || item != this.arrivalAirport);
            },
            fetchAvailableFlights() {
                if (this.loading) {
                    return;
                }
                let valid = this.$refs.form.validate() || this.$refs.bottomForm.validate();
                if (!valid) {
                    return;
                }
                this.loading = true;
                axios.get(`/api/flights/${this.departureAirport.id}/${this.arrivalAirport.id}`, {
                    params: {
                        departureDate: this.departureDate,
                        returnDate: this.returnDate,
                        roundTrip: this.roundTrip,
                        flightClass: this.flightClass,
                        adults: this.adults
                    }
                }).then(response => {
                    this.flights = response.data.flights;
                    this.loading = false;
                    this.searched = true;
                    this.$nextTick(() => {
                        this.scrollToFlights();
                    })
                })
            },
            fetchAirportsAndClasses() {
                axios.get('/api/airports').then(response => {

                    this.airports = response.data.airports;
                    this.classes = response.data.classes;
                    this.flightClass = this.classes.length > 0 ? this.classes[0].id : null;
                    this.fuse = new Fuse(this.airports, {
                        includeScore: true,
                        includeMatches: true,
                        keys: ['name', 'code', 'city.name', 'country.name']
                    })
                });
            },
            getAirportName(airport) {
                return airport.name;
            },
            scrollToFlights() {
                this.$vuetify.goTo("#flights", {
                    duration: 1400,
                    offset: 100,
                    easing: 'easeInOutCubic',
                })
            },
            isNumber(value) {
                return !isNaN(Number(value)) ? true : "Must be a number";
            }
        },
        computed: {
            departureAirports() {
                return this.airports.filter(airport => airport != this.arrivalAirport);
            },
            arrivalAirports() {
                return this.airports.filter(airport => airport != this.departureAirport);

            }
        },
        mounted() {
            this.fetchAirportsAndClasses()
        }
    }
</script>

<style>
    .aircraft {
        animation: float 6s ease-in-out infinite;

    }

    @keyframes float {
        0% {
            transform: translatey(0px);
        }
        50% {
            transform: translatey(-20px);
        }
        100% {
            transform: translatey(0px);
        }
    }

    .first-dot {
        margin-top: -3rem;
    }

    .last-dot {
        margin-bottom: -3rem;
    }

    .dot {
        margin-left: 10px;
    }

    .v-radio > .v-label {
        margin-bottom: 0 !important;
    }

    .v-checkbox > .v-label {
        margin-bottom: 0 !important;
    }

    .container-margin {
        margin-top: 4rem !important;
        margin-bottom: 0;
    }

    .screen-height {
        /*height: 50vh;*/
    }

</style>
