<template>
    <Head>
        <title>Tracking | Mactrackify</title>
    </Head>
    <div class="page">
        <TeamLeaderNav />
        <div class="page-wrapper">
            <!-- Page header -->
            <div class="page-header d-print-none">
                <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col ms-1">
                            <!-- Page pre-title -->
                            <div class="page-pretitle">ADMIN</div>
                            <h2 class="page-title">DASHBOARD</h2>
                        </div>
                        <!-- Page title actions -->
                        <div class="col-auto ms-auto d-print-none">
                            <a href="/team-leader/notifications" class="nav-link mx-3">Notifications</a>
                        </div>
                        <div
                            class="col-auto ms-auto d-print-none d-none d-lg-flex"
                        >
                            <div class="btn-list">
                                <div class="dropdown">
                                    <a
                                        href="#"
                                        class="nav-link dropdown-toggle"
                                        data-bs-toggle="dropdown"
                                        >Hello TEAM LEADER !</a
                                    >
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href=""
                                            >Settings</a
                                        >
                                        <div class="dropdown-divider"></div>
                                        <form
                                            action="/logout"
                                            method="post"
                                        >
                                            <button
                                                type="submit"
                                                class="dropdown-item"
                                            >
                                                Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl">
                    <div class="row">
                        <div class="col-12 text-center">
                            <h1>Live Brand Ambassador Tracking</h1>
                        </div>
                        <div class="col-12 d-flex justify-content-center w-100 ">
                            <DeployeeMapComponent
                                style="height: 30vw;"
                                :center="center"
                                :opened-marker="openedMarker"
                                :tracks="user.latest_tracking"
                            />
                        </div>
                        <div class="col-12 text-center">
                            <p class="h3">
                                Deployed Brand Ambassadors:
                                <!-- <b>{{ mt_rand(5, 10) }}</b> -->
                            </p>
                        </div>

                        <div class="col-12 table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">
                                            Brand Ambassador Name
                                        </th>
                                        <th scope="col">GPS Coordinates</th>
                                        <th scope="col">Location</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">
                                            Location Retrieve At
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="track in user.latest_tracking" :key="track.id">
                                        <td>{{ user.id }}</td>
                                        <td>{{ user.fullName }}</td>
                                        <td @click="toCenter(track)">
                                            {{ track.latitude }} | {{ track.longitude }}
                                            <i class="ti ti-eye-pin"></i>
                                        </td>
                                        <td>{{ track.location ?? "No Location" }}</td>
                                        <td>
                                            <div v-if="track.is_authentic">
                                                <span class="badge bg-green" >Genuine</span>
                                            </div>
                                            <div v-else>
                                                <span class="badge bg-red">Spoofed</span>
                                            </div>
                                        </td>
                                        <td>{{ track.createdAtDiff }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue"
import { Head } from '@inertiajs/vue3'
import TeamLeaderNav from "./Components/TeamLeaderNav.vue";
import DeployeeMapComponent from "./Components/DeployeeMapComponent.vue";

const props = defineProps({user: Object})

const center = ref({lat: 14.58977819216876, lng: 120.98159704631904})
const openedMarker = ref(null);

const toCenter = (track) => {
    openedMarker.value = track.id
    center.value = {lat: parseFloat(track.latitude), lng: parseFloat(track.longitude)};
}
</script>
