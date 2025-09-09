<div class="container py-4">
    <h2 class="mb-3">Carte - Positions récentes</h2>

    <div id="map" style="height: 500px;" class="mb-3"></div>
    <button wire:click="refresh" class="btn btn-sm btn-outline-primary">Rafraîchir</button>

    <script>
        document.addEventListener('livewire:navigated', initMap);
        document.addEventListener('livewire:init', initMap);

        function initMap() {
            if (!window.L) return;
            const mapEl = document.getElementById('map');
            if (!mapEl) return;
            const map = L.map('map').setView([0, 0], 2);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
            }).addTo(map);

            const points = @json($locations);
            if (points.length) {
                const markers = points.map(p => L.marker([p.latitude, p.longitude]).bindPopup(new Date(p.recorded_at)
                    .toLocaleString()));
                const group = L.featureGroup(markers).addTo(map);
                map.fitBounds(group.getBounds(), {
                    padding: [20, 20]
                });
            }
        }
    </script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</div>
