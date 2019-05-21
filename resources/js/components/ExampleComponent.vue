<template>
    <div>
        <h1 class="text-center">Центры, производящие диализ крови</h1>
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-2">
                    <form action="">
                        <h6>Выбрать по региону</h6>
                        <select name="region" id="region" class="form-control" @change="onChangeRegion($event)">
                            <option v-for="region in regions" :value="region">{{region}}</option>
                        </select>

                        <h6 class="mt-3">Выбрать по городу</h6>
                        <select name="city" id="city" class="form-control" @change="onChangeRegion($event)">
                            <option v-for="city in cities" :value="city">{{city}}</option>
                        </select>
                    </form>
                </div>
                <div class="col-12 col-md-10">
                    <yandex-map @map-was-initialized="initHandler" zoom="4" :coords="coords" class="map" >
                        <ymap-marker v-for="marker in markers" markerId="marker.id" marker-type="placemark"
                                     :hint-content="marker.center_name"
                                     :coords="marker.capacity" :key="marker.id"></ymap-marker>
                    </yandex-map>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {yandexMap, ymapMarker} from 'vue-yandex-maps';

    export default {
        name: 'app',
        components: {
            yandexMap,
            ymapMarker
        },
        mounted() {
            // this.csrf = window.Laravel.csrfToken;
        },
        data() {
            return {
                coords: [54, 39],
                markers: [{id: 0, capacity: [], center_name: ''}],
                markersAll: [],
                regions:[],
                cities:[],
            }
        },
        methods: {
            initHandler() {
                axios.get('/getObjects').then(data => {
                    this.markers = this.markersAll = _.map(data.data, (m) => {
                        m.capacity = m.capacity.replace(/[(,)]/, '').split(',');
                        m.capacity[0] = parseFloat(m.capacity[0]);
                        m.capacity[1] = parseFloat(m.capacity[1]);
                        return m
                    });
                    let aregions = _.map(data.data, (m)=>{
                        return m.region;
                    });
                    this.regions = [...new Set(aregions)];
                    let acities = _.map(data.data, (m)=>{
                        return m.city;
                    });
                    this.cities = [...new Set(acities)];
                });
            },
            onChangeRegion(event){
                this.markers = _.map(this.markersAll, function(m) {
                    if (m[event.target.name] == event.target.value) return m;
                });
                this.markers = _.without(this.markers, undefined);
                this.coords = this.markers[0].capacity;
            },
            onClick(e) {
                this.coords = e.get('coords');
            }
        }
    }
</script>

<style>

    .ymap-container {
        height: 600px;
    }
</style>