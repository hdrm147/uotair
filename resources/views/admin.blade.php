<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="/css/materialdesignicons.min.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="/css/jquery.seat-charts.css">
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <link href="{{mix('/css/app.css')}}" rel="stylesheet">

    <title>UOTAir</title>
    <style>
    </style>
</head>
<style>
    [v-cloak] {display: none}
</style>
<body>
<div id="app" v-cloak>


    <v-app id="inspire">
        <v-navigation-drawer
            v-model="drawer"
            app
        >
            <v-list dense>
                <v-list-item v-if="user">
                    <v-row>
                        <v-col cols="12" class="pb-0">
                            <v-avatar color="primary" size="80">
                                <img v-if="user.avatar" :src="`/${user.avatar}`">
                                <span v-else class="white--text display-1">HM</span>
                            </v-avatar>
                        </v-col>
                        <v-col cols="12" class="py-0">
                            <span class="title">@{{ user.name }}</span>
                        </v-col>
                        <v-col cols="12" class="py-0">
                            <span class="subtitle-2">@{{ user.email }}</span>
                        </v-col>
                        <v-col cols="12">
                            <v-divider></v-divider>
                        </v-col>
                    </v-row>
                </v-list-item>
                <template v-for="item in items">
                    <v-list-item
                        :key="item.text"
                        link
                        :to="`/admin/${item.resourceName}/`"
                    >
                        <v-list-item-action>
                            <v-icon>@{{ item.icon }}</v-icon>
                        </v-list-item-action>

                        <v-list-item-content>
                            <v-list-item-title>
                                @{{ item.text }}
                            </v-list-item-title>
                        </v-list-item-content>

                    </v-list-item>
                </template>
            </v-list>

        </v-navigation-drawer>

        <v-app-bar
            app
            v-if="bar"
            color="primary"
            dark
        >
            <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
            <v-toolbar-title>UOTAir</v-toolbar-title>
        </v-app-bar>

        <v-content class="blue-grey lighten-5">
            <v-dialog v-model="deleteDialog.show" persistent max-width="400">

                <v-card>
                    <v-card-title class="headline">@{{ deleteDialog.title }}</v-card-title>
                    <v-card-text>@{{ deleteDialog.message }}</v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn @click="deleteDialog.show=false" :disabled="deleteDialog.loading"
                               color="primary darken-1" text @click="dialog = false">Cancel
                        </v-btn>
                        <v-btn :loading="deleteDialog.loading" @click="deleteResource" class="white--text"
                               color="red darken-1" @click="dialog = false">Delete
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
            <v-snackbar
                v-model="snackbar.show"
                bottom
                right
                :color="snackbar.color"
            >
                @{{ snackbar.message }}
                <v-btn
                    color="white"
                    text
                    @click="snackbar = false"
                >
                    Close
                </v-btn>
            </v-snackbar>
            <transition name="fade" mode="out-in">
                <router-view :key="$route.fullPath"></router-view>
            </transition>
        </v-content>
    </v-app>

</div>
<script src="{{mix('/js/app.admin.js') }}"></script>
<script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="/js/jquery.seat-charts.js"></script>
</body>
</html>
