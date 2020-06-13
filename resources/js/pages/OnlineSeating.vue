<template>
    <v-col cols="12">

        <v-row v-if="width > 0 && height > 0" justify="center" align="start">
            <v-col cols="1">

                <v-row v-for="row in grid" :key="`row-${row.number}`" justify="end">

                    <v-col class="pa-1 mr-2" cols="auto">
                        <v-sheet class="row-count-cell" color="blue lighten-4">
                            <span class="black--text">{{row.number}}</span>
                        </v-sheet>
                    </v-col>

                </v-row>
            </v-col>
            <div class="plane">

                <div class="fuselage px-2">
                    <v-col cols="auto">
                        <template>

                            <v-row v-for="row in grid" :key="`row-${row.number}`">
                                <v-col class="text-center pa-1" v-for="cell in row.cells"
                                       :key="cell.key">
                                    <v-sheet :class="{'not-matching':cell.type !== `class-${flightClass.id}`}"
                                             class="grid-cell grid-cell-selectable"
                                             :color="cell === selectedSeat ? 'orange' : colorFor(cell)"
                                             @click="selectSeat(cell)"
                                             :cell="cell">
                                                <span class="caption white--text">
                                                    <template
                                                        v-if="cell.type !== 'no-seat'">{{cell.seatNumber}}</template>
                                                </span>
                                    </v-sheet>

                                </v-col>
                            </v-row>
                        </template>

                    </v-col>

                </div>
            </div>
            <v-col>
                <v-row>
                    <v-col class="py-0">
                        <span class="subtitle-1">Currently Booking</span>
                    </v-col>
                </v-row>
                <v-row v-for="(passenger,index) in currentlyBookingSeats" :key="`passenger-legend-${index}`">
                    <v-col cols="auto" class="py-1">
                        <v-sheet class="px-1" color="primary">
                            <span class="white--text">{{passenger.seat}}</span>
                        </v-sheet>
                    </v-col>
                    <v-col class="px-0 py-1">
                        {{passenger.name}}
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
    </v-col>
</template>

<script>
    import DragSelect from 'vue-drag-select/src/DragSelect.vue'

    export default {
        props: ["classes", "value", "booked", "flightClass", "currentlyBookingSeats"],
        name: "OnlineSeating",
        components: {
            'drag-select-container': DragSelect
        },
        data() {
            return {
                width: 7,
                height: 32,
                grid: [],
                selected: [],
                lastType: null,
                letters: ["A", "B", "C", "D", "E", "F", "G", "H"],
                selectedSeat: null,
            }
        },
        methods: {
            seatsFountFor(flightClass) {
                let type = `class-${flightClass.id}`;
                return _.flatten(this.grid.map(row => {
                    return row.cells.filter(cell => cell.type === type);
                }).filter(group => group.length > 0)).length;
            },
            selectColumn(column) {
                this.grid.forEach(row => {
                    row.cells.forEach(cell => {
                        if (cell.column === column)
                            cell.selected = true;
                    })
                });
            },
            zoom(type) {
                if (type === 'out') {
                    this.$refs.zoom.pan
                } else {

                }
            },
            selectSeat(cell) {

                if (cell.type !== `class-${this.flightClass.id}`) {
                    return;
                }
                if(this.booked.includes(cell.seatNumber)) {
                    return;
                }
                if (this.currentlyBookingSeats.map(passenger => passenger.seat).includes(cell.seatNumber)) {
                    return;
                }
                this.selectedSeat = cell;
                this.$emit('seat-changed', cell.seatNumber)

            },

            colorFor(cell) {

                if (cell.type === "not-assigned") {
                    return "primary";
                }
                if (cell.type !== `no-seat` && this.currentlyBookingSeats.map(passenger => passenger.seat).includes(cell.seatNumber)) {
                    return "primary";
                }
                if (cell.type !== `no-seat` && this.booked.includes(cell.seatNumber)) {
                    return "indigo";
                }

                let type = this.seatsType.find(type => type && type.key === cell.type);

                return type ? type.seat_color : "primary";
            },
        },
        watch: {
            value(newValue) {
                this.grid = newValue;
            }
        },
        computed: {
            seatsType() {

                let items = [
                    {
                        name: "No Seat",
                        key: "no-seat",
                        id: 0,
                        seat_color: "blue-grey lighten-4"
                    },
                ];

                return items.concat(this.classes);
            },
            selectedCells() {
                return _.flatten(this.grid.map(row => {
                    return row.cells.filter(cell => cell.selected);
                }).filter(group => group.length > 0));
            },
            emptyRows() {
                return this.grid.filter(row => {
                    return row.cells.filter(cell => cell.type === "no-seat").length === this.width
                });
            }
        },
        mounted() {
            this.grid = this.value.grid;
            this.width = this.value.width;
            this.height = this.value.height;
        }
    }
</script>

<style>
    .grid-cell {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 1rem;
        width: 2rem;
        cursor: pointer;
    }


    .plane {
    }

    .cockpit {
        height: 250px;
        position: relative;
        overflow: hidden;
        text-align: center;
        border-bottom: 5px solid #d8d8d8;
    }

    .cockpit:before {
        content: "";
        display: block;
        position: absolute;
        top: 0;
        left: 0;
        height: 500px;
        width: 100%;
        border-radius: 50%;
        border-right: 5px solid #d8d8d8;
        border-left: 5px solid #d8d8d8;
    }

    .fuselage {
        border-right: 5px solid #d8d8d8;
        border-left: 5px solid #d8d8d8;
    }

    .row-count-cell {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 1rem;
        width: 2rem;
        color: white;
    }

    .letter-select .v-text-field.v-text-field--enclosed:not(.v-text-field--rounded) > .v-input__control > .v-input__slot, .v-text-field.v-text-field--enclosed .v-text-field__details {
        padding: 0 !important;
    }

    .letter-select .v-select__selection.v-select__selection--comma {
        margin: 0 !important;
    }

    .letter-select .v-select__selections {
        width: 100%;
        display: flex;
        justify-content: center;
    }

    .letter-select .v-select.v-text-field input {
        display: none !important;
    }

    .not-matching {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>
