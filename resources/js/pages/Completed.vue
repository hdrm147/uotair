<template>
    <v-container v-if="loaded" class="printable-container" style="position: relative">
        <input id="cb" type="text" hidden>

        <v-row justify="center" class="screen-height d-print-none" align="center">
            <v-col class="pa-0 pb-6" cols="12" sm="12" md="9" lg="8" xl="5">
                <v-card>
                    <v-card-text>
                        <v-row justify="center">
                            <v-col cols="auto" class="pb-0">
                                <v-img style="width: 10rem" src="/images/logo.png"></v-img>
                            </v-col>
                        </v-row>
                        <v-row justify="center">
                            <v-col cols="auto">
                                <v-icon size="80" color="success">mdi-check</v-icon>
                            </v-col>
                        </v-row>
                        <v-row justify="center">
                            <span class="display-2 font-weight-light">Order Placed</span>
                        </v-row>
                        <v-row justify="center" class="mt-0 py-4">
                            <h1 class="font-weight-light">
                                Your Booking Reference ID is
                            </h1>
                        </v-row>
                        <v-row justify="center" class="mt-2">
                            <v-sheet color="blue lighten-4"
                                     class="text-uppercase title px-2 py-1 font-weight-medium primary--text ">
                                <v-row justify="center">
                                    <v-col class="py-0 pl-5 pr-0">
                                        <span class="text-button font-weight-medium">{{referenceId}}</span>
                                    </v-col>
                                    <v-col cols="auto" class="py-0 pl-2 pr-4">
                                        <v-icon color="primary" class="mt-n1" @click="copyReferenceId" small>
                                            mdi-content-copy
                                        </v-icon>
                                    </v-col>
                                </v-row>

                            </v-sheet>
                        </v-row>

                        <v-row justify="center" class="mt-2 py-4">
                            <h1 class="font-weight-light">
                                Order Total
                            </h1>
                        </v-row>
                        <v-row justify="center" class="mt-2 pb-4">
                            <h1 class="font-weight-light">
                                ${{total.toLocaleString()}}
                            </h1>
                        </v-row>
                        <v-row justify="center">
                            <span class="font-weight-normal subtitle-2">{{passengers.length}} Ticket{{passengers.length>1 ? "s" : ""}} for Flight {{details.flight.number}} at {{departureDateFormatted}}</span>
                        </v-row>
                        <v-row justify="center">
                            <v-col cols="auto">
                                <v-btn @click="print" outlined color="primary">Print Tickets</v-btn>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col class="py-0">
                                <span class="subtitle-2">Be sure to arrive at the airport 3 hours before your scheduled flight</span>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col class="py-0">
                                <span class="caption">Better an hour early than a min late.</span>
                            </v-col>
                        </v-row>


                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-row justify="center">
            <v-col class="pa-0 pb-6" cols="12" sm="12" md="11" lg="10" xl="7">
                <span class="display-1 font-weight-light blue-grey--text">Your Tickets</span>
            </v-col>
        </v-row>
        <div class="printable-area">
            <v-row v-if="details" v-for="(passenger,index) in passengers"
                   :key="`passenger-${index}`"
                   class="mb-12 pb-12"
                   justify="center">
                <v-col class="pa-0" cols="12" sm="12" md="11" lg="10" xl="7">
                    <v-card class="boarding-card overflow-hidden">
                        <v-row>
                            <v-col cols="8" class="dashed py-0">
                                <v-row>
                                    <v-col cols="auto" class="px-6">
                                        <img style="width: 7rem" src="/images/logo.png" alt="">
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
                                            disabled
                                            v-model="passenger.name" dense hide-details="auto" label="Passenger"
                                            outlined></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="3" class="pl-6 pr-3 px-sm-2 py-2 py-sm-0">

                                        <v-text-field
                                            outlined
                                            hide-details="auto"
                                            v-model="passenger.date_of_birth"
                                            label="Birthday"
                                            disabled
                                            dense></v-text-field>


                                    </v-col>
                                    <v-col cols="12" sm="2" class="pl-6 pr-3 px-sm-2 py-2 py-sm-0">
                                        <v-select  autocomplete="new"
                                            v-model="passenger.gender" :items="genders" dense
                                            hide-details="auto"
                                            disabled
                                            label="Gender" append-icon=""
                                            outlined></v-select>
                                    </v-col>
                                    <v-col cols="12" sm="2" class="pl-6 pr-3 px-sm-2 py-2 py-sm-0">
                                        <v-text-field
                                            v-model="passenger.seat"
                                            disabled

                                            dense
                                            v-if="details.flight.online_seating && passenger.type !=='infant'"

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
                                            <img style="width: 6rem" src="/images/logo.png" alt=""/>
                                        </v-col>
                                    </v-col>
                                </v-row>

                                <v-row justify="end">
                                    <v-col cols="12" class="pr-6 pt-0">
                                        <img style="width: 100%" src="/images/barcode.png" alt=""/>
                                    </v-col>

                                </v-row>

                            </v-col>
                        </v-row>
                    </v-card>

                </v-col>
            </v-row>

        </div>
    </v-container>
</template>

<script>
    export default {
        name: "Completed",
        data() {
            return {
                details: null,
                passengers: [],
                classes: [],
                grid: null,
                dialog: null,
                genders: [{text: "Male", value: "male"}, {text: "Female", value: "female"}],
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
                total: 0,
                referenceId: null,
                loaded: false,
            }
        },
        methods: {
            copyReferenceId() {
                this.copy(this.referenceId);
            },
            print() {
                window.print();
            },
            copy(txt) {
                var cb = document.getElementById("cb");
                cb.value = txt;
                cb.style.display = 'block';
                cb.select();
                document.execCommand('copy');
                cb.style.display = 'none';
            },
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
        },
        mounted() {
            this.details = this.$route.params.details;
            this.passengers = this.$route.params.passengers;
            this.classes = this.$route.params.classes;
            this.grid = this.$route.params.grid;
            this.total = this.$route.params.total;
            this.referenceId = this.$route.params.referenceId;
            this.loaded = true;
        }
    }
</script>

<style>

    .dashed {
        border-right: 2px dashed gray;
    }


    @media print {
        .v-app-bar {
            display: none !important;
        }

        .printable-container > *:not(.printable-area) {
            display: none;
        }

        .printable-area {

            position: absolute;
            width: 100%;
            top: 0;
            left: 0;
            padding: 1rem;
        }

        .printable-area * {
            page-break-inside: avoid;
        }

        .boarding-card {
            box-shadow: none;
            border: 1px solid gray !important;
        }
    }

</style>
