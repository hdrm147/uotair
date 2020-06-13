
import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'

Vue.use(Vuetify)
import colors from 'vuetify/lib/util/colors'

const opts = {
    theme: {
        themes: {
            light: {
                primary: "#2e86de",

            },
            dark: {
                primary: colors.blue.lighten3,

            },
        },
    },
}


export default new Vuetify(opts)
