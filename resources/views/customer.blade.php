<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="/css/app.css" rel="stylesheet">

    <title>UOTAir</title>
    <style>
        body {
            background-image: url("/images/background-original.png");
            background-position: 100% 3rem;
            background-repeat: no-repeat;
            background-size: 80%;
            background-color: #F8F8FF;
        }

        .theme--light.v-application {
            background-color: transparent !important;
        }

    </style>
</head>
<body>
<div id="app">


    <v-app>
        <v-app-bar hide-on-scroll color="transparent" flat>
            <img class="py-1" style="height: 70px" src="/images/logo.png" alt="logo">
            <v-spacer></v-spacer>

            <v-btn  v-for="button in barButtons" :disabled="button.disabled" :to="button.link" text color="primary">
                @{{button.title}}
            </v-btn>
        </v-app-bar>
        <v-app-bar inverted-scroll color="primary" app>
            <img class="py-1" style="height: 70px;filter: brightness(0) invert(1);" src="/images/logo.png" alt="logo">
            <v-spacer></v-spacer>

            <v-btn v-for="button in barButtons" :disabled="button.disabled" :to="button.link" text color="white">
                @{{button.title}}
            </v-btn>
        </v-app-bar>

        <v-content>
            <transition name="fade" mode="out-in">

                <router-view :key="$route.fullPath"></router-view>
            </transition>
        </v-content>
    </v-app>
</div>
<script src="{{mix('/js/app.js') }}"></script>
</body>
</html>
