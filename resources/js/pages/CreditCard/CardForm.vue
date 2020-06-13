<template>
    <div class="card-form">
        <div class="card-list">
            <Card
                :fields="fields"
                :labels="formData"
                :isCardNumberMasked="isCardNumberMasked"
                :randomBackgrounds="randomBackgrounds"
                :backgroundImage="backgroundImage"
            />
        </div>
        <div class="card-form__inner elevation-1">
            <div class="card-input">

                <slot name="top"></slot>
                <v-text-field
                    outlined
                    hide-details="auto"
                    label="Card Number"
                    type="tel"
                    :id="fields.cardNumber"
                    @input="changeNumber"
                    @focus="focusCardNumber"
                    @blur="blurCardNumber"
                    :value="formData.cardNumber"
                    :maxlength="cardNumberMaxLength"
                    data-card-field
                    append-icon="mdi-eye"
                    @click:append="toggleMask"
                    autocomplete="off"
                />
            </div>
            <div class="card-input">
                <v-text-field
                    label="Card Name"
                    outlined
                    type="text"
                    :id="fields.cardName"
                    v-letter-only
                    hide-details="auto"
                    @input="changeName"
                    :value="formData.cardName"
                    data-card-field
                    autocomplete="off"
                />
            </div>
            <div class="card-form__row">
                <div class="card-form__col mr-2">
                    <div class="card-form__group">
                        <v-col class="px-0">
                            <v-select  autocomplete="new" hide-details="auto"
                                      label="Expiration Month" outlined :id="fields.cardMonth"
                                      v-model="formData.cardMonth"
                                      :items="months"
                                      @change="changeMonth"
                                      data-card-field>

                            </v-select>

                        </v-col>
                        <v-col class="pl-6">
                            <v-select  autocomplete="new" hide-details="auto"
                                      :id="fields.cardYear"
                                      v-model="formData.cardYear"
                                      @change="changeYear"
                                      data-card-field :items="years" outlined></v-select>

                        </v-col>
                    </div>
                </div>
                <div class="card-form__col -cvv">
                    <div class="card-input">
                        <v-col class="px-0">
                            <v-text-field  autocomplete="new"hide-details="auto"
                                          outlined label="CVV" :value="formData.cardCvv" data-card-field
                                          @input="changeCvv"
                                          :id="fields.cardCvv"></v-text-field>
                        </v-col>
                    </div>
                </div>
            </div>
            <v-row>
                <v-col class="py-0">
                    <slot></slot>
                </v-col>
            </v-row>
            <slot name="button"></slot>
        </div>
    </div>
</template>

<script>
    import Card from './Card'

    export default {
        name: 'CardForm',
        directives: {
            'number-only': {
                bind(el) {
                    function checkValue(event) {
                        event.target.value = event.target.value.replace(/[^0-9]/g, '')
                        if (event.charCode >= 48 && event.charCode <= 57) {
                            return true
                        }
                        event.preventDefault()
                    }

                    el.addEventListener('keypress', checkValue)
                }
            },
            'letter-only': {
                bind(el) {
                    function checkValue(event) {
                        if (event.charCode >= 48 && event.charCode <= 57) {
                            event.preventDefault()
                        }
                        return true
                    }

                    el.addEventListener('keypress', checkValue)
                }
            }
        },
        props: {
            formData: {
                type: Object,
                default: () => {
                    return {
                        cardName: '',
                        cardNumber: '',
                        cardMonth: '',
                        cardYear: '',
                        cardCvv: ''
                    }
                }
            },
            backgroundImage: [String, Object],
            randomBackgrounds: {
                type: Boolean,
                default: true
            }
        },
        components: {
            Card
        },
        data() {
            return {
                fields: {
                    cardNumber: 'v-card-number',
                    cardName: 'v-card-name',
                    cardMonth: 'v-card-month',
                    cardYear: 'v-card-year',
                    cardCvv: 'v-card-cvv'
                },
                minCardYear: new Date().getFullYear(),
                isCardNumberMasked: true,
                mainCardNumber: this.cardNumber,
                cardNumberMaxLength: 19,
                years: [],
                months: [],
            }
        },
        computed: {
            minCardMonth() {
                if (this.formData.cardYear === this.minCardYear) return new Date().getMonth() + 1
                return 1
            }
        },
        watch: {
            cardYear() {
                if (this.formData.cardMonth < this.minCardMonth) {
                    this.formData.cardMonth = ''
                }
            }
        },
        mounted() {
            for (let i = 0; i < 12; i++) {
                this.years.push({
                    value: this.minCardYear + i,
                    text: this.minCardYear + i
                })

            }
            for (let i = 1; i <= 12; i++) {
                this.months.push({
                    value: i < 10 ? '0' + i : i,
                    text: this.generateMonthValue(i)
                })
            }
            this.maskCardNumber()
        },
        methods: {
            generateMonthValue(n) {
                return n < 10 ? `0${n}` : n
            },
            changeName(e) {
                console.log(e);
                this.formData.cardName = e;
                this.$emit('input-card-name', this.formData.cardName)
            },
            changeNumber(e) {
                this.formData.cardNumber = e;
                let value = this.formData.cardNumber.replace(/\D/g, '')
                // american express, 15 digits
                if ((/^3[47]\d{0,13}$/).test(value)) {
                    this.formData.cardNumber = value.replace(/(\d{4})/, '$1 ').replace(/(\d{4}) (\d{6})/, '$1 $2 ')
                    this.cardNumberMaxLength = 17
                } else if ((/^3(?:0[0-5]|[68]\d)\d{0,11}$/).test(value)) { // diner's club, 14 digits
                    this.formData.cardNumber = value.replace(/(\d{4})/, '$1 ').replace(/(\d{4}) (\d{6})/, '$1 $2 ')
                    this.cardNumberMaxLength = 16
                } else if ((/^\d{0,16}$/).test(value)) { // regular cc number, 16 digits
                    this.formData.cardNumber = value.replace(/(\d{4})/, '$1 ').replace(/(\d{4}) (\d{4})/, '$1 $2 ').replace(/(\d{4}) (\d{4}) (\d{4})/, '$1 $2 $3 ')
                    this.cardNumberMaxLength = 19
                }
                // eslint-disable-next-line eqeqeq
                if (e.inputType == 'deleteContentBackward') {
                    let lastChar = this.formData.cardNumber.substring(this.formData.cardNumber.length, this.formData.cardNumber.length - 1)
                    // eslint-disable-next-line eqeqeq
                    if (lastChar == ' ') {
                        this.formData.cardNumber = this.formData.cardNumber.substring(0, this.formData.cardNumber.length - 1)
                    }
                }
                this.$emit('input-card-number', this.formData.cardNumber)
            },
            changeMonth() {
                this.$emit('input-card-month', this.formData.cardMonth)
            },
            changeYear() {
                this.$emit('input-card-year', this.formData.cardYear)
            },
            changeCvv(e) {
                this.formData.cardCvv = e;
                this.$emit('input-card-cvv', this.formData.cardCvv)
            },
            invaildCard() {
                this.$emit("pay");
                let number = this.formData.cardNumber
                let sum = 0
                let isOdd = true
                for (let i = number.length - 1; i >= 0; i--) {
                    let num = number.charAt(i)
                    if (isOdd) {
                        sum += num
                    } else {
                        num = num * 2
                        if (num > 9) {
                            num = num.toString().split('').join('+')
                        }
                        sum += num
                    }
                    isOdd = !isOdd
                }
                if (sum % 10 !== 0) {
                    // alert('invaild card number')
                }
            },
            blurCardNumber() {
                if (this.isCardNumberMasked) {
                    this.maskCardNumber()
                }
            },
            maskCardNumber() {
                this.mainCardNumber = this.formData.cardNumber
                let arr = this.formData.cardNumber.split('')
                arr.forEach((element, index) => {
                    if (index > 4 && index < 14 && element.trim() !== '') {
                        arr[index] = '*'
                    }
                })
                this.formData.cardNumber = arr.join('')
            },
            unMaskCardNumber() {
                this.formData.cardNumber = this.mainCardNumber
            },
            focusCardNumber() {
                this.unMaskCardNumber()
            },
            toggleMask() {
                this.isCardNumberMasked = !this.isCardNumberMasked
                if (this.isCardNumberMasked) {
                    this.maskCardNumber()
                } else {
                    this.unMaskCardNumber()
                }
            }
        }
    }
</script>

<style lang="scss">
    @import './style.scss';
</style>
