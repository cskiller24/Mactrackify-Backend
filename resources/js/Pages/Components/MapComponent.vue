<template>
    <GMapMap
        :center="center"
        :zoom="14"
        map-type-id="terrain"
        class="w-100 h-50"
    >
        <GMapMarker
            :key="member.id"
            :position="{lat: parseFloat(member.latestTrack.latitude), lng: parseFloat(member.latestTrack.longitude)}"
            v-for="member in team.members"
        >
            <GMapInfoWindow
                :opened="openedMarker === member.id"
            >
                <div>{{ member.fullName }} {{ new Date(member.latestTrack.created_at).toLocaleString() }}</div>
            </GMapInfoWindow>
        </GMapMarker>
    </GMapMap>
</template>
<script setup>

defineProps({center: Object, team: Object, openedMarker: Number})

</script>
