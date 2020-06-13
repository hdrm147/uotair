<template>
    <v-col cols="12">
        <v-row>
            <v-col class="pb-0">
                <v-text-field autocomplete="new" dense hide-details v-model.number="width" label="Grid Width"
                              outlined></v-text-field>
            </v-col>
            <v-col class="pb-0">
                <v-text-field autocomplete="new" dense hide-details v-model.number="height" label="Grid Height"
                              outlined></v-text-field>
            </v-col>
        </v-row>
        <v-row>
            <template v-for="flightClass in classes">
                <v-col class="pb-2">
                    <span>{{flightClass.name}} ({{flightClass.pivot.capacity}}): {{seatsFountFor(flightClass)}}</span>
                </v-col>
                <v-col class="pb-1" cols="auto">
                    <v-divider vertical></v-divider>
                </v-col>
            </template>
        </v-row>
        <v-row v-if="field.errorMessages && field.errorMessages.length > 0">
            <v-col class="py-0 title red--text pb-2">
                <v-alert type="error">
                    {{field.errorMessages.join("")}}
                </v-alert>
            </v-col>
        </v-row>
        <v-row>
            <v-col class="pa-0">
                <v-divider></v-divider>
            </v-col>
        </v-row>
        <v-row v-if="width > 0 && height > 0" justify="center" align="end">
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
                        <v-menu
                            v-model="showMenu"
                            absolute
                            offset-y
                            style="max-width: 600px"
                        >
                            <template v-slot:activator="{ on, attrs }">
                                <drag-select-container @change="selectedChanged" selectorClass="grid-cell-selectable">
                                    <template>
                                        <v-row class="letter-select">
                                            <v-col class="px-1 pb-0" style="width: 2rem" v-for="column in width"
                                                   :key="`column-${column}`">
                                                <v-select autocomplete="new" hide-details append-icon="" outlined dense
                                                          :items="letters"
                                                          @change="setLetterForColumn(column,$event)"></v-select>
                                            </v-col>
                                        </v-row>

                                        <v-row @contextmenu.prevent="on.click" v-for="row in grid"
                                               :key="`row-${row.number}`">
                                            <v-col class="text-center pa-1" v-for="cell in row.cells"
                                                   :key="cell.key">
                                                <v-sheet class="grid-cell grid-cell-selectable"
                                                         :color="cell.selected ? 'orange' : colorFor(cell)"
                                                         :cell="cell">
                                                <span class="caption white--text">
                                                    <template v-if="cell.type !== 'no-seat'">
                                                       {{cell.seatNumber}}
                                                    </template>
                                                </span>
                                                </v-sheet>

                                            </v-col>
                                        </v-row>
                                    </template>
                                </drag-select-container>
                            </template>
                            <v-list>
                                <v-list-item
                                    v-for="(type, index) in seatsType"
                                    :key="index"
                                    @click="changeType(type)"
                                >
                                    <v-list-item-title @click="">{{ type.name }}</v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-menu>
                    </v-col>

                </div>
            </div>

        </v-row>
    </v-col>
</template>

<script>
    import DragSelect from 'vue-drag-select/src/DragSelect.vue'

    export default {
        props: ["classes", "value", "field"],
        name: "FlightSeats",
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
                shouldRedraw: true,
                showMenu: false,
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
            unselectAll() {
                this.grid.forEach(row => {
                    row.cells.forEach(cell => {
                        cell.selected = false;
                    })
                });
            },
            selectedChanged(value) {
                this.unselectAll();
                value.forEach(component => {
                    if (component.$attrs.cell)
                        component.$attrs.cell.selected = true;
                });
            },
            changeType(value) {
                this.selectedCells.forEach(cell => {
                    cell.type = value.key;
                })

                this.unselectAll();
                this.correctNumbers();
            },
            setLetterForColumn(column, letter) {
                this.grid.forEach(row => {
                    row.cells.forEach(cell => {
                        if (cell.column === column)
                            cell.letter = letter;
                    })
                })
                this.correctNumbers();
                this.$forceUpdate();
            },

            colorFor(cell) {


                if (cell.type === "not-assigned") {
                    return "primary";
                }
                let type = this.seatsType.find(type => type.key === cell.type);

                return type ? type.seat_color : "primary";
            },
            correctNumbers() {
                let counter = 1;

                this.grid.forEach(row => {
                    row.cells.filter(cell => cell.type !== "no-seat").forEach(cell => {
                        let rowNumber = cell.row - this.emptyRows.filter(search => search.number <= row.number).length;
                        cell.number = counter++
                        cell.seatNumber = `${cell.letter}${rowNumber}`;
                    });
                })
            },
            makeKey(length = 8) {
                let result = '';
                let characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                let charactersLength = characters.length;
                for (let i = 0; i < length; i++) {
                    result += characters.charAt(Math.floor(Math.random() * charactersLength));
                }
                return result;
            },
            redraw() {
                if (this.width > 20) {
                    alert("Too large width value, this will cause to hang-up your browser")
                    return
                }
                if (this.height > 300) {
                    alert("Too large height value, this will cause to hang-up your browser")
                    return
                }
                this.grid = [];
                let counter = 1;
                for (let y = 1; y <= this.height; y++) {
                    let row = {
                        number: y,
                        cells: [],
                    }
                    for (let x = 1; x <= this.width; x++) {
                        row.cells.push({
                            column: x,
                            number: counter++,
                            row: row.number,
                            selected: false,
                            type: "not-assigned",
                            letter: "",
                            key: this.makeKey(),
                        })
                    }
                    this.grid.push(row);
                }
                this.correctNumbers();
            },
        },
        watch: {
            width() {
                if (this.shouldRedraw)
                    this.redraw();
            },
            height() {
                if (this.shouldRedraw)
                    this.redraw();

            },
            grid: {
                deep: true,
                handler() {
                    this.$emit('input', {
                        grid: this.grid,
                        width: this.width,
                        height: this.height
                    });
                }
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
            if (this.value === null) {
                this.redraw();
            } else {
                this.shouldRedraw = false;
                this.grid = this.value.grid;
                this.width = this.value.width;
                this.height = this.value.height;
                this.$nextTick(() => {
                    this.shouldRedraw = true;

                })

            }

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
</style>
