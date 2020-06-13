import Vue from 'vue'
import VueRouter from 'vue-router'


import Index from "./pages/admin/Index";
import Detail from "./pages/admin/Detail";
import Form from "./pages/admin/Form";
import Login from "./pages/admin/Login";
import Dashboard from "./pages/admin/Dashboard";
const routes = [
    {path: '/admin/login', component: Login},
    {path: '/admin', component: Dashboard},
    {path: '/admin/:resourceName', component: Index},
    {path: '/admin/:resourceName/new', component: Form},
    {path: '/admin/:resourceName/:resourceId/edit', component: Form},
    {path: '/admin/:resourceName/:resourceId', component: Detail},


]


Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    routes
})

export default router;
