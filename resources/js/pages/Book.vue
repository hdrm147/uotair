<template>
    <v-container>
        <v-dialog v-if="details" scrollable v-model="dialog" width="600px">
            <v-card>
                <v-card-title>
                    <span class="headline">Pick {{currentSeatPassenger ? currentSeatPassenger.name : ""}} Seat</span>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <online-seating ref="seats" @seat-changed="seatChanged" :flight-class="details.flightClass"
                                    :currently-booking-seats="currentlyBookingSeats"
                                    :booked="bookedSeats" :classes="classes" :value="grid"></online-seating>

                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="green darken-1" text @click="dialog = false">Close</v-btn>
                    <v-btn color="green darken-1" text @click="bookSeat" :disabled="selectedSeat == null">Book
                        Seat {{ selectedSeat ? `(${selectedSeat})` :"" }}
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-form id="form" ref="form" lazy-validation>
            <v-row justify="center" class="pt-10">
                <v-col class="pa-0 pb-6" cols="12" sm="12" md="11" lg="10" xl="7">
                    <span class="display-1 font-weight-light blue-grey--text">Fill In Your Tickets Details</span>
                </v-col>
            </v-row>
            <v-row v-if="details" v-for="(passenger,index) in passengers" :key="`passenger-${index}`"
                   class="mb-12 pb-12"
                   justify="center">
                <v-col class="pa-0" cols="12" sm="12" md="11" lg="10" xl="7">
                    <v-card class="boarding-card overflow-hidden">
                        <v-row>
                            <v-col cols="8" class="dashed py-0">
                                <v-row>
                                    <v-col cols="auto" class="px-6">
                                        <v-img style="width: 7rem" src="/images/logo.png"></v-img>
                                        <span class="subtitle-2 font-weight-light blue-grey--text text--lighten-2">Boarding Pass</span>
                                    </v-col>
                                    <v-col cols="auto" class="pt-5">
                                        <v-icon color="blue-grey lighten-2">mdi-seat-passenger</v-icon>
                                        <span
                                            class="caption blue-grey--text text--lighten-2">{{details.flightClass.name}}</span>
                                    </v-col>
                                    <v-col cols="auto" class="pt-5">
                                        <v-icon color="blue-grey lighten-2">mdi-bag-carry-on</v-icon>
                                        <span class="caption blue-grey--text text--lighten-2">{{details.flightClass.number_of_bags}} x {{details.flightClass.bag_weight_limit}}Kg</span>
                                    </v-col>
                                    <v-col cols="auto" class="pt-5">

                                        <v-icon color="blue-grey lighten-2">
                                            <template v-if="passenger.type === 'adult'">
                                                mdi-human-male
                                            </template>
                                            <template v-else-if="passenger.type === 'child'">
                                                mdi-human-child
                                            </template>
                                            <template v-else-if="passenger.type === 'infant'">
                                                mdi-account-child
                                            </template>

                                        </v-icon>
                                        <span class="caption blue-grey--text text--lighten-2">
                                           <template v-if="passenger.type === 'adult'">
                                                Adult
                                            </template>
                                            <template v-else-if="passenger.type === 'child'">
                                              Child
                                            </template>
                                            <template v-else-if="passenger.type === 'infant'">
                                               Infant
                                            </template>
                                            Ticket
                                        </span>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="12" sm="5" class="pl-6 pl-sm-6 py-2 py-sm-0">
                                        <v-text-field
                                            :rules="[isRequired]"
                                            @click="clearErrorsFor(index,'name')"
                                            :error-messages="getErrorsFor(index,'name')"
                                            v-model="passenger.name" dense hide-details="auto" label="Passenger"
                                            outlined></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="3" class="pl-6 pr-3 px-sm-2 py-2 py-sm-0">
                                        <v-menu
                                            ref="dateOfBirthMenu"
                                            :close-on-content-click="false"
                                            :return-value.sync="passenger.date_of_birth"
                                            transition="scale-transition"
                                            @input="changeToYear()"
                                            offset-y
                                            min-width="290px"
                                        >
                                            <template v-slot:activator="{ on, attrs }">
                                                <v-text-field
                                                    outlined
                                                    hide-details="auto"
                                                    v-model="passenger.date_of_birth"
                                                    @click="clearErrorsFor(index,'date_of_birth')"

                                                    :error-messages="getErrorsFor(index,'date_of_birth')"
                                                    :rules="[isRequired]"
                                                    label="Birthday"
                                                    readonly
                                                    dense
                                                    v-bind="attrs"
                                                    v-on="on"
                                                ></v-text-field>
                                            </template>
                                            <v-date-picker
                                                :max="new Date().toISOString().substr(0, 10)"
                                                min="1950-01-01"
                                                active-picker="YEAR"
                                                ref="datePicker"
                                                @input="$refs.dateOfBirthMenu[index].save(passenger.date_of_birth)"
                                                v-model="passenger.date_of_birth" scrollable>
                                            </v-date-picker>
                                        </v-menu>

                                    </v-col>
                                    <v-col cols="12" sm="2" class="pl-6 pr-3 px-sm-2 py-2 py-sm-0">
                                        <v-select  autocomplete="new" :error-messages="getErrorsFor(index,'gender')"
                                                  @change="clearErrorsFor(index,'gender')"
                                                  v-model="passenger.gender" :items="genders" dense
                                                  hide-details="auto"
                                                  :rules="[isRequired]"

                                                  label="Gender" append-icon=""
                                                  outlined></v-select>
                                    </v-col>
                                    <v-col cols="12" sm="2" class="pl-6 pr-3 px-sm-2 py-2 py-sm-0">
                                        <v-text-field  autocomplete="new":error-messages="getErrorsFor(index,'seat')"
                                                      v-model="passenger.seat"
                                                      readonly
                                                      @click="openSeatsDialog(passenger,index)"
                                                      dense
                                                      v-if="details.flight.online_seating && passenger.type !=='infant'"
                                                      :rules="[isRequired]"
                                                      :disabled="loadingSeats"
                                                      :loading="loadingSeats"
                                                      hide-details="auto" label="Seat"
                                                      outlined></v-text-field>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col class="pl-6 py-3">
                                        <v-divider></v-divider>
                                    </v-col>
                                </v-row>
                                <v-row align="center">
                                    <v-col cols="auto" class="pl-6 pt-0">
                                        <v-col class="py-0">
                                            <v-row class="py-0 display-1 font-weight-medium">
                                                {{details.departureAirport.code}}
                                            </v-row>
                                            <v-row class="py-0 subtitle-2">{{details.departureAirport.name}}</v-row>
                                        </v-col>
                                    </v-col>
                                    <v-col cols="auto" class="pt-0 rotate-90">
                                        <v-icon v-if="$vuetify.breakpoint.mdAndUp" color="primary" size="60">mdi-airplane</v-icon>
                                    </v-col>
                                    <v-col cols="auto" class="pt-0">
                                        <v-col class="py-0 pl-6">
                                            <v-row class="py-0 display-1 font-weight-medium">
                                                {{details.arrivalAirport.code}}
                                            </v-row>
                                            <v-row class="py-0 subtitle-2">{{details.arrivalAirport.name}}</v-row>
                                        </v-col>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col class="pl-6 py-0">
                                        <v-divider></v-divider>
                                    </v-col>
                                </v-row>
                                <v-row align="center">
                                    <v-col class="pl-6 pt-0">
                                        <v-col class="py-0 mt-1">
                                            <v-row class="py-0 blue-grey--text caption font-weight-medium">Depart
                                            </v-row>
                                            <v-row class="py-0 blue-grey--text subtitle-1 font-weight-bold">
                                                {{details.flight.departure_time}}
                                            </v-row>
                                        </v-col>
                                    </v-col>
                                    <v-col class="pl-6 pt-0">
                                        <v-col class="py-0 mt-1">
                                            <v-row class="py-0 blue-grey--text caption font-weight-medium">Date</v-row>
                                            <v-row class="py-0 blue-grey--text subtitle-1 font-weight-bold">
                                                {{departureDateFormatted}}
                                            </v-row>
                                        </v-col>
                                    </v-col>
                                    <v-col class="pl-6 pt-0">
                                        <v-col class="py-0 mt-1">
                                            <v-row class="py-0 blue-grey--text caption font-weight-medium">Arrive
                                            </v-row>
                                            <v-row class="py-0 blue-grey--text subtitle-1 font-weight-bold">
                                                {{details.flight.arrival_time}}
                                            </v-row>
                                        </v-col>
                                    </v-col>
                                </v-row>

                                <v-row>
                                    <v-col class="pl-6 py-0">
                                        <v-divider></v-divider>
                                    </v-col>
                                </v-row>
                                <v-row align="center">
                                    <v-col class="pl-6 pt-0">
                                        <v-col class="py-0 mt-1">
                                            <v-row class="py-0 blue-grey--text caption font-weight-medium">Flight #
                                            </v-row>
                                            <v-row class="py-0 blue-grey--text subtitle-1 font-weight-bold">
                                                {{details.flight.number}}
                                            </v-row>
                                        </v-col>
                                    </v-col>
                                    <v-col class="pl-6 pt-0">
                                        <v-col class="py-0 mt-1">
                                            <v-row class="py-0 blue-grey--text caption font-weight-medium">Booking Date
                                            </v-row>
                                            <v-row class="py-0 blue-grey--text subtitle-1 font-weight-bold">{{today}}
                                            </v-row>
                                        </v-col>
                                    </v-col>
                                    <v-col class="pl-6 pt-0">
                                        <v-col class="py-0 mt-1">
                                            <v-row class="py-0 blue-grey--text caption font-weight-medium"></v-row>
                                            <v-row class="py-0 blue-grey--text subtitle-1 font-weight-bold"></v-row>
                                        </v-col>
                                    </v-col>
                                </v-row>

                            </v-col>
                            <v-col cols="4">
                                <v-row>
                                    <v-col cols="auto" class="px-6">
                                        <span class="subtitle-2 font-weight-light blue-grey--text text--lighten-2">Boarding Pass</span>
                                    </v-col>
                                </v-row>
                                <v-row align="center">
                                    <v-col class="pl-6 py-0">
                                        <v-col class="py-0 mt-1">
                                            <v-row class="py-0 blue-grey--text title font-weight-medium">
                                                {{passenger.name || "YOUR NAME"}}
                                            </v-row>
                                        </v-col>
                                    </v-col>

                                </v-row>

                                <v-row align="center">
                                    <v-col class="pl-6 py-0">
                                        <v-col class="py-0 mt-1">
                                            <v-row class="py-0 blue-grey--text caption font-weight-medium">Seat</v-row>
                                            <v-row class="py-0 blue-grey--text subtitle-1 font-weight-bold">
                                                {{passenger.seat}}
                                            </v-row>
                                        </v-col>
                                    </v-col>
                                    <v-col class="pl-6 py-0">
                                        <v-col class="py-0 mt-1">
                                        </v-col>
                                    </v-col>
                                    <v-col class="pl-6 py-0">
                                        <v-col class="py-0 mt-1">
                                        </v-col>
                                    </v-col>
                                </v-row>
                                <v-row align="center">
                                    <v-col cols="auto" class="pl-6 display-1 py-0 font-weight-bold">
                                        {{details.departureAirport.code}}
                                    </v-col>
                                    <v-col cols="auto" class="py-0  rotate-90">
                                        <v-icon v-if="$vuetify.breakpoint.mdAndUp" large>mdi-airplane</v-icon>
                                    </v-col>
                                    <v-col class="py-0 pl-6 display-1 font-weight-bold">
                                        {{details.arrivalAirport.code}}
                                    </v-col>
                                </v-row>
                                <v-row align="center">
                                    <v-col class="pl-6 py-0">
                                        <v-col class="py-0 mt-1">
                                            <v-row class="py-0 blue-grey--text caption font-weight-medium">Flight #
                                            </v-row>
                                            <v-row class="py-0 blue-grey--text subtitle-1 font-weight-bold">
                                                {{details.flight.number}}
                                            </v-row>
                                        </v-col>
                                    </v-col>
                                    <v-col class="pl-6 py-0">
                                        <v-col class="py-0 mt-1">
                                        </v-col>
                                    </v-col>
                                    <v-col class="pl-6 py-0">
                                        <v-col class="py-0 mt-1">
                                        </v-col>
                                    </v-col>
                                </v-row>
                                <v-row align="center">
                                    <v-col class="pl-6 py-0">
                                        <v-col class="py-0 mt-1">
                                            <v-row class="py-0 blue-grey--text caption font-weight-medium">Class</v-row>
                                            <v-row class="py-0 blue-grey--text subtitle-1 font-weight-bold">
                                                {{details.flightClass.name}}
                                            </v-row>
                                        </v-col>
                                    </v-col>
                                    <v-col class="pl-6 py-0">
                                        <v-col class="py-0 mt-1">
                                        </v-col>
                                    </v-col>
                                    <v-col class="pl-6 py-0">
                                        <v-col class="py-0 mt-1">
                                            <v-img style="width: 6rem" src="/images/logo.png"></v-img>

                                        </v-col>
                                    </v-col>
                                </v-row>

                                <v-row justify="end">
                                    <v-col cols="12" class="pr-6 pt-0">
                                        <v-img style="width: 100%" src="/images/barcode.png"></v-img>
                                    </v-col>

                                </v-row>

                            </v-col>
                        </v-row>
                    </v-card>

                </v-col>
            </v-row>
            <v-row justify="center">
                <v-col class="pa-0 pb-0" cols="12" sm="12" md="11" lg="10" xl="7">
                    <span class="display-1 font-weight-light blue-grey--text">Payment</span>
                </v-col>
            </v-row>
            <v-row v-if="details" justify="center" class="my-12 py-0">
                <v-col class="pa-0" cols="12" sm="12" md="11" lg="10" xl="7">
                    <CardForm
                        @pay="pay"
                        :form-data="creditFormData"
                        @input-card-number="updateCardNumber"
                        @input-card-name="updateCardName"
                        @input-card-month="updateCardMonth"
                        @input-card-year="updateCardYear"
                        @input-card-cvv="updateCardCvv"
                    >
                        <template v-slot:top>
                            <v-row>
                                <v-col class="py-0">
                                    <v-alert
                                        v-if="validation"
                                        color="red lighten-1"

                                    >
                                        <v-row align="center">
                                            <v-col cols="auto">
                                                <v-icon large class="pr-0" color="white">mdi-alert-circle</v-icon>
                                            </v-col>
                                            <v-col class="pl-0 white--text font-weight-medium title">
                                                {{validation.message}}
                                            </v-col>
                                        </v-row>
                                        <v-row v-for="(error,i) in validation.errors" :key="`errors-${i}`">
                                            <v-col class="py-0 white--text subtitle-2">
                                                {{error[0]}}
                                            </v-col>

                                        </v-row>
                                    </v-alert>

                                </v-col>
                            </v-row>
                        </template>
                        <v-row>
                            <v-col cols="12" class="py-0">
                                <v-text-field  autocomplete="new":rules="[isRequired]" v-model="email" hide-details="auto" outlined
                                              label="Email Address"></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row justify="center">
                            <v-col cols="auto" class="py-2">
                                <span class="title blue-grey--text font-weight-light">Order Total</span>
                            </v-col>
                        </v-row>
                        <v-row justify="center">
                            <v-col cols="auto" class="pt-0 pb-4">

                                <span class="display-1 blue-grey--text font-weight-medium">${{totalFare}}</span>

                            </v-col>
                        </v-row>
                        <template v-slot:button>
                            <v-btn :loading="loading" outlined block color="primary" x-large v-on:click="pay">Pay Now
                            </v-btn>

                        </template>
                    </CardForm>

                </v-col>
            </v-row>
        </v-form>


    </v-container>

</template>

<script>
    import OnlineSeating from "./OnlineSeating";
    import CardForm from './CreditCard/CardForm.vue';

    export default {
        name: "Book",
        components: {
            OnlineSeating,
            CardForm
        },
        data() {
            return {
                details: null,
                grid: null,
                dialog: false,
                classes: [],
                bookedSeats: [],
                selectedSeat: null,
                genders: [{text: "Male", value: "male"}, {text: "Female", value: "female"}],
                passengers: [],
                validation: null,
                months: [
                    "January",
                    "February",
                    "March",
                    "April",
                    "May",
                    "June",
                    "July",
                    "August",
                    "September",
                    "October",
                    "November",
                    "December",
                ],
                currentSeatPassenger: null,
                creditFormData: {
                    cardName: '',
                    cardNumber: '',
                    cardMonth: '',
                    cardYear: '',
                    cardCvv: ''
                },
                loadingSeats: true,
                loading: false,
                email: ""
            }
        },
        methods: {
            changeToYear() {
                this.$nextTick(() => {
                    let components = this.$refs.datePicker ? this.$refs.datePicker : null;
                    if (components) {
                        components.forEach(component => {
                            component.activePicker = 'YEAR'

                        })
                    }
                })

            },
            clearErrorsFor(index, field) {
                console.log(index)
                if (!this.validation) {
                    return [];
                }
                this.validation.errors[`passengers.${index}.${field}`] = [];
            },
            getErrorsFor(index, field) {
                if (!this.validation) {
                    return null;
                }
                let errors = this.validation.errors[`passengers.${index}.${field}`];
                console.log(`passengers.${index}.${field}`)
                if (errors) {
                    return errors;
                }
            },
            updateCardNumber(val) {
            },
            updateCardName(val) {
            },
            updateCardMonth(val) {
            },
            updateCardYear(val) {
            },
            updateCardCvv(val) {
            },
            isRequired(value) {
                return !!value || `This field is Required`;
            },
            seatChanged(seat) {
                this.selectedSeat = seat;
            },
            bookSeat() {
                this.currentSeatPassenger.seat = this.selectedSeat;
                this.selectedSeat = null;
                this.$refs.seats.selectedSeat = null;
                this.dialog = false;

            },
            openSeatsDialog(passenger, index) {
                this.currentSeatPassenger = passenger;
                this.dialog = true;
                this.clearErrorsFor(index, 'seat');
            },
            next() {
                this.$refs.form.validate();
            },
            pay() {

                let valid = this.$refs.form.validate();

                if (this.loading) {
                    return "is loading";
                }
                if (!valid) {
                    this.$nextTick(() => {
                        this.$vuetify.goTo(".v-messages.error--text:first-of-type", {
                            duration: 1400,
                            offset: 100,
                            easing: 'easeInOutCubic',
                        })
                    })
                    return "is not valid";
                }

                this.loading = true;

                axios.post(`/api/flights/${this.details?.flight.id}/book`, {
                    flightClass: this.details.flightClass.id,
                    date: this.details.departureDate,
                    passengers: this.passengers,
                    email_address: this.email
                }).then(response => {
                    this.loading = false;
                    this.$router.push({
                        name: "completed",
                        params: {
                            tickets: response.data.tickets,
                            details: this.details,
                            passengers: this.passengers,
                            grid: this.grid,
                            classes: this.classes,
                            total: response.data.total,
                            referenceId: response.data.reference_id
                        },
                    })
                    console.log(response);
                }).catch(error => {
                    this.validation = error.response.data;
                    this.$nextTick(() => {
                        this.$vuetify.goTo(".v-messages.error--text:first-of-type", {
                            duration: 1400,
                            offset: 200,
                            easing: 'easeInOutCubic',
                        })
                    })
                    this.loading = false;
                })
            }
        },
        computed: {
            departureDateFormatted() {
                let date = new Date(this.details?.departureDate);
                return `${date.getDate()} ${this.months[date.getMonth()]}`;
            },
            today() {
                let date = new Date()
                return `${date.getDate()} ${this.months[date.getMonth()]}`;
            },
            currentlyBookingSeats() {
                return this.passengers.filter(passenger => passenger.seat);
            },
            totalFare() {
                if (!this.details) {
                    return 0;
                }
                return (this.details.adults * this.details.flightClass.adult_fare) + (this.details.children * this.details.flightClass.children_fare);
            }
        },
        mounted() {

            this.details = this.$route.params.details;
            if (!this.details) {
                this.$router.push({path: '/'});
                return;
            }
            for (let i = 1; i <= this.details.adults; i++) {
                this.passengers.push({
                    name: null,
                    gender: "male",
                    type: "adult",
                    date_of_birth: null,
                    seat: null,
                })
            }
            for (let i = 1; i <= this.details.children; i++) {
                this.passengers.push({
                    name: null,
                    gender: "male",
                    type: "child",
                    date_of_birth: null,
                    seat: null,
                })
            }
            for (let i = 1; i <= this.details.infants; i++) {
                this.passengers.push({
                    name: null,
                    gender: "male",
                    type: "infant",
                    date_of_birth: null,
                    seat: null,
                })
            }
            if (this.details?.flight.id)
                axios.get(`/api/flights/${this.details?.flight.id}/seats`, {
                    params: {
                        date: this.details?.departureDate,
                        flightClass: this.details?.flightClass.id
                    }
                }).then(response => {
                    this.classes = response.data.classes.map(flightClass => {
                        return {
                            key: `class-${flightClass.id}`,
                            ...flightClass
                        }
                    })
                    this.grid = response.data.seats;
                    this.bookedSeats = response.data.bookedSeats;
                    this.loadingSeats = false;
                });


        }
    }
</script>

<style scoped>
    .boarding-card {
        border-radius: 1rem !important;
        height: 100%;
    }

    .dashed {
        border-right: 2px dashed gray;
    }
</style>
