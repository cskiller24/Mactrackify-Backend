<template>
    <GMapMap
        :center="center"
        :zoom="14"
        map-type-id="terrain"
        class="w-100 h-50"
    >
        <GMapMarker
            :key="track.id"
            :position="{lat: parseFloat(track.latitude), lng: parseFloat(track.longitude)}"
            v-for="track in tracks"
        >
            <GMapInfoWindow
                :opened="openedMarker === track.id"
            >
                <div>{{ new Date(track.created_at).toLocaleString() }}</div>
            </GMapInfoWindow>
        </GMapMarker>
    </GMapMap>
</template>
<script setup>

defineProps({center: Object, tracks: Array, openedMarker: Number})

</script>
