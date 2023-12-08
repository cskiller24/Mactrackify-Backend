<template>
<aside class="navbar navbar-vertical navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu"
            aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <h1 class="navbar-brand navbar-brand-autodark">
            <a href="" class="h2">
                Mactrackify
            </a>
        </h1>

        <div class="navbar-nav flex-row d-lg-none">
            <div class="nav-item">
                <div class="dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Hello
                        User !</a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="">Settings</a>
                        <div class="dropdown-divider"></div>
                        <form action="{{ route('logout') }}" method="post">
                            <button type="submit" class="dropdown-item">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="collapse navbar-collapse" id="sidebar-menu">
            <ul class="navbar-nav pt-lg-3" >

                <li class="nav-item" v-for="item in items" :key="item.id">
                    <a class="nav-link" :href="item.link">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i :class="`text-lg ti ti-${item.icon} icon`"></i>
                        </span>
                        <span class="nav-link-title">
                            {{ item.name }}
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</aside>
</template>

<script setup>

import { ref } from "vue";

const items = ref([
    {
        id: 1,
        name: "Dashboard",
        icon: "home",
        link: "/Deployer"
    },
    {
        id: 2,
        name: "Deployees",
        icon: "users-group",
        link: "/Deployer/Deployees"
    },
    {
        id: 3,
        name: "Data",
        icon: "file-info",
        link: "/Deployer/data"
    },
    {
        id: 4,
        name: "Tracking",
        icon: "location-search",
        link: "/Deployer/tracking"
    }
]);
</script>
