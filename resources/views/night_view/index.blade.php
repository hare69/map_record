<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
</head>
<body>
<div id="app" class="p-4">
    <h3>近くの夜景スポットをマップ表示するサンプル</h3>
    <div class="text-secondary mb-1">
        <small>現在地から近い順に最大10件の夜景スポットを取得します（現在地を移動させてみてください）</small>
    </div>
    <div id="map" style="width:100%;height:500px;border:1px solid #000;"></div>
</div>
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>

    new Vue({
        el: '#app',
        data() {
            return {
                nightViews: [],
                markers: [],
                location: {
                    longitude: '135.18671362704455',
                    latitude: '34.68422333531551',
                },
                map: null
            }
        },
        methods: {
            getNightViews() {
                const url = '{{ route('night_view.list') }}';
                const params = {
                    params: this.location
                };
                axios.get(url, params)
                    .then(response => {
                        if(response.data.result === true) {
                            this.nightViews = response.data.night_views;
                            console.log(response.data)
                            this.setMarkers();
                        }
                    });

            },
            setMarkers() {
                this.markers.forEach(marker => this.map.removeLayer(marker));
                this.markers = [];

                this.nightViews.forEach(nightView => {

                    const location = [nightView.latitude, nightView.longitude];
                    const marker = L.marker(location).addTo(this.map)
                        .bindPopup(`<a href="${nightView.map_url}" target="_blank">Googleマップで表示</a>`)
                        .bindTooltip(`${nightView.name}（${nightView.address}）`);
                    this.markers.push(marker);

                });

            },
            setCenter(e) {

                const center = e.target.getCenter();
                this.location = {
                    latitude: center.lat,
                    longitude: center.lng
                };

            }
        },
        watch: {
            location: {
                deep: true,
                immediate: true,
                handler() {

                    Vue.nextTick(() => {

                        this.getNightViews();

                    });

                }
            }
        },
        mounted() {

            // マップを用意
            this.map = L.map('map', {
                zoomAnimation: false
            }).setView([ this.location.latitude, this.location.longitude ], 8);
            const layerUrl = 'https://cyberjapandata.gsi.go.jp/xyz/pale/{z}/{x}/{y}.png';
            const attribution = '<a href="https://www.gsi.go.jp/kikakuchousei/kikakuchousei40182.html" target="_blank" rel="noopener">国土地理院</a>';
            L.tileLayer(layerUrl, { attribution: attribution }).addTo(this.map);
            this.map
                .on('moveend', e => {

                    this.setCenter(e);

                })
                .on('zoomend', e => {

                    this.setCenter(e);

                });

        }
    });

</script>
</body>
</html>
