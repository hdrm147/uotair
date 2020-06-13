import Vue from 'vue'
import VueRouter from 'vue-router'


import Home from "./pages/Home";
import Book from "./pages/Book";
import Completed from "./pages/Completed";
import CheckTickets from "./pages/CheckTickets";

const routes = [
    {name: "home", path: '/', component: Home},
    {name: "book", path: '/book', component: Book},
    {name: "completed", path: '/completed', component: Completed},
    {name: "check-tickets", path: '/check', component: CheckTickets}

]


Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    routes,
    scrollBehavior (to, from, savedPosition) {
        return { x: 0, y: 0 }
    }
})

export default router;
