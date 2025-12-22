@extends('layouts.app')

@section('content')
    <div class="container-fluid p-0" style="position:relative;">
        <h3 class="mb-3 ps-3">Lokasi Kegiatan</h3>
        <div id="front-map" style="width:100%; height:75vh; border:1px solid #ddd; border-radius:4px;"></div>

        <div id="map-filter"
            style="position:absolute; top:70px; right:12px; width:340px; max-height:72vh; overflow:auto; background:#fff; border:1px solid #ddd; padding:12px; box-shadow:0 2px 8px rgba(0,0,0,.15); z-index:1000;">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <strong>Filter</strong>
                <button id="collapse-filters" class="btn btn-sm btn-light">Toggle</button>
            </div>

            <div id="filter-body">
                <div class="mb-2">
                    <strong>Base Layer</strong>
                    <div id="baselayers" class="mt-1"></div>
                </div>

                <div class="mb-2">
                    <strong>Jenis Kegiatan</strong>
                    <div id="filter-kategori" style="margin-top:6px"></div>
                </div>

                <div class="mb-2">
                    <strong>Tahun Kegiatan</strong>
                    <div id="filter-year" style="margin-top:6px"></div>
                </div>

                <div class="mb-2">
                    <strong>Periode Kegiatan</strong>
                    <div id="filter-periode" style="margin-top:6px"></div>
                </div>

                <div class="mb-2">
                    <strong>Desa</strong>
                    <div id="filter-desa" style="margin-top:6px; max-height:140px; overflow:auto"></div>
                </div>

                <div class="mb-2">
                    <strong>Zona Kawasan</strong>
                    <div id="filter-zona" style="margin-top:6px"></div>
                </div>

                <div class="mb-2">
                    <label class="form-label">Cari</label>
                    <input id="filter-text" class="form-control form-control-sm" placeholder="Judul atau kegiatan">
                </div>

                <div class="d-flex justify-content-between">
                    <button id="apply-filters" class="btn btn-sm btn-primary">Terapkan</button>
                    <button id="reset-filters" class="btn btn-sm btn-secondary">Reset</button>
                </div>
            </div>
        </div>

        <p class="mt-2 text-muted ps-3">Klik marker untuk melihat detail kegiatan.</p>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <style>
            /* small adjustments for control panel */
            #map-filter .form-label {
                font-size: 0.85rem;
                margin-bottom: 4px
            }

            #map-filter .form-control-sm {
                font-size: 0.85rem;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const reports = @json($reports ?? []);
                const showUrlBase = "{{ url('rencana_kegiatan') }}";
                const defaultCenter = reports.length ? [reports[0].lat, reports[0].lng] : [-6.200000, 106.816666];

                const street = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors'
                });
                const satellite = L.tileLayer(
                    'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                        maxZoom: 19,
                        attribution: 'Tiles &copy; Esri'
                    });

                const map = L.map('front-map', {
                    layers: [street]
                }).setView(defaultCenter, reports.length ? 6 : 5);

                const baseMaps = {
                    'Street': street,
                    'Satellite': satellite
                };
                L.control.layers(baseMaps).addTo(map);

                // populate base layer radios in panel
                const baseEl = document.getElementById('baselayers');
                baseEl.innerHTML = `
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="basemap" id="base-street" value="street" checked>
                        <label class="form-check-label" for="base-street">Street</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="basemap" id="base-sat" value="sat">
                        <label class="form-check-label" for="base-sat">Satellite</label>
                    </div>`;

                document.querySelectorAll('input[name="basemap"]').forEach(r => r.addEventListener('change', function(
                    e) {
                    if (e.target.value === 'sat') {
                        map.addLayer(satellite);
                        map.removeLayer(street);
                    } else {
                        map.addLayer(street);
                        map.removeLayer(satellite);
                    }
                }));

                // marker management
                const markersGroup = L.layerGroup().addTo(map);

                function addMarkers(list) {
                    markersGroup.clearLayers();
                    list.forEach(r => {
                        if (!r.lat || !r.lng) return;
                        const m = L.marker([r.lat, r.lng]);

                        const formatDate = (d) => {
                            if (!d) return '-';
                            try {
                                return new Date(d).toLocaleDateString('id-ID');
                            } catch (e) {
                                return d;
                            }
                        };

                        const tanggal = r.tanggal_mulai ? (formatDate(r.tanggal_mulai) + (r.tanggal_selesai ?
                            ' - ' + formatDate(r.tanggal_selesai) : '')) : '-';
                        const anggaran = r.estimasi_anggaran ? new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR'
                        }).format(r.estimasi_anggaran) : '-';
                        const nama = r.nama_kegiatan ?? r.judul ?? '-';
                        const jenis = r.jenis_kegiatan ?? r.kategori ?? '-';

                        const popup = `
                            <div style="min-width:200px">
                                <strong>${nama}</strong>
                                <div><small class="text-muted">${jenis}</small></div>
                                <table class="table table-sm mt-2 mb-1">
                                    <tbody>
                                        <tr><td style="width:35%">Tanggal</td><td>${tanggal}</td></tr>
                                        <tr><td>Desa</td><td>${r.desa ?? '-'}</td></tr>
                                        <tr><td>Penanggung Jawab</td><td>${r.penanggung_jawab ?? '-'}</td></tr>
                                        <tr><td>Peserta</td><td>${r.estimasi_peserta ?? '-'}</td></tr>
                                        <tr><td>Anggaran</td><td>${anggaran}</td></tr>
                                        <tr><td>Status</td><td>${r.status ? (r.status.charAt(0).toUpperCase()+r.status.slice(1)) : '-'}</td></tr>
                                    </tbody>
                                </table>
                                <div><a href="${showUrlBase}/${r.id}">Lihat detail</a></div>
                            </div>
                        `;

                        m.bindPopup(popup);
                        markersGroup.addLayer(m);
                    });
                }

                // build filter options
                const kategoriSet = [...new Set(reports.map(r => r.kategori).filter(Boolean))];
                const yearsSet = [...new Set(reports.map(r => r.created_at ? new Date(r.created_at).getFullYear() :
                    null).filter(Boolean))].sort((a, b) => b - a);
                const periodeSet = [...new Set(reports.map(r => r.periode).filter(Boolean))];
                const desaSet = [...new Set(reports.map(r => r.desa).filter(Boolean))];
                const zonaSet = [...new Set(reports.map(r => r.zona).filter(Boolean))];

                const kContainer = document.getElementById('filter-kategori');
                kategoriSet.forEach(k => {
                    const id = 'k-' + k.replace(/\s+/g, '_');
                    const div = document.createElement('div');
                    div.className = 'form-check';
                    div.innerHTML =
                        `<input class="form-check-input" type="checkbox" value="${k}" id="${id}"><label class="form-check-label" for="${id}">${k}</label>`;
                    kContainer.appendChild(div);
                });

                const yContainer = document.getElementById('filter-year');
                yearsSet.forEach(y => {
                    const id = 'y-' + y;
                    const div = document.createElement('div');
                    div.className = 'form-check';
                    div.innerHTML =
                        `<input class="form-check-input" type="checkbox" value="${y}" id="${id}"><label class="form-check-label" for="${id}">${y}</label>`;
                    yContainer.appendChild(div);
                });

                const pContainer = document.getElementById('filter-periode');
                // fallback to common period names if none in data
                const defaultPeriode = ['Periode Awal', 'Periode Tengah', 'Periode Akhir'];
                const periodes = periodeSet.length ? periodeSet : defaultPeriode;
                periodes.forEach(p => {
                    const id = 'p-' + p.replace(/\s+/g, '_');
                    const div = document.createElement('div');
                    div.className = 'form-check';
                    div.innerHTML =
                        `<input class="form-check-input" type="checkbox" value="${p}" id="${id}"><label class="form-check-label" for="${id}">${p}</label>`;
                    pContainer.appendChild(div);
                });

                const dContainer = document.getElementById('filter-desa');
                // if no desa in data, leave empty (or add placeholders)
                desaSet.forEach(d => {
                    const id = 'ds-' + d.replace(/\s+/g, '_');
                    const div = document.createElement('div');
                    div.className = 'form-check';
                    div.innerHTML =
                        `<input class="form-check-input" type="checkbox" value="${d}" id="${id}"><label class="form-check-label" for="${id}">${d}</label>`;
                    dContainer.appendChild(div);
                });

                const zContainer = document.getElementById('filter-zona');
                zonaSet.forEach(z => {
                    const id = 'zn-' + z.replace(/\s+/g, '_');
                    const div = document.createElement('div');
                    div.className = 'form-check';
                    div.innerHTML =
                        `<input class="form-check-input" type="checkbox" value="${z}" id="${id}"><label class="form-check-label" for="${id}">${z}</label>`;
                    zContainer.appendChild(div);
                });

                function applyFilters() {
                    const text = (document.getElementById('filter-text').value || '').toLowerCase().trim();
                    const selectedKategori = Array.from(document.querySelectorAll(
                        '#filter-kategori input[type=checkbox]:checked')).map(i => i.value);
                    const selectedYears = Array.from(document.querySelectorAll(
                        '#filter-year input[type=checkbox]:checked')).map(i => i.value.toString());
                    const selectedPeriode = Array.from(document.querySelectorAll(
                        '#filter-periode input[type=checkbox]:checked')).map(i => i.value);
                    const selectedDesa = Array.from(document.querySelectorAll(
                        '#filter-desa input[type=checkbox]:checked')).map(i => i.value);
                    const selectedZona = Array.from(document.querySelectorAll(
                        '#filter-zona input[type=checkbox]:checked')).map(i => i.value);

                    const filtered = reports.filter(r => {
                        if (selectedKategori.length && !(r.kategori && selectedKategori.includes(r.kategori)))
                            return false;
                        if (selectedYears.length) {
                            const y = r.created_at ? new Date(r.created_at).getFullYear().toString() : '';
                            if (!selectedYears.includes(y)) return false;
                        }
                        if (selectedPeriode.length && !(r.periode && selectedPeriode.includes(r.periode)))
                            return false;
                        if (selectedDesa.length && !(r.desa && selectedDesa.includes(r.desa))) return false;
                        if (selectedZona.length && !(r.zona && selectedZona.includes(r.zona))) return false;
                        if (text) {
                            const hay = ((r.judul || '') + ' ' + (r.kategori || '') + ' ' + (r.desa || ''))
                                .toLowerCase();
                            if (!hay.includes(text)) return false;
                        }
                        return true;
                    });

                    addMarkers(filtered);
                    if (markersGroup.getLayers().length) {
                        try {
                            map.fitBounds(markersGroup.getBounds().pad(0.1));
                        } catch (e) {
                            map.setView(defaultCenter, 6);
                        }
                    } else {
                        map.setView(defaultCenter, 5);
                    }
                }

                // initial load
                addMarkers(reports);
                if (markersGroup.getLayers().length) map.fitBounds(markersGroup.getBounds().pad(0.1));

                document.getElementById('apply-filters').addEventListener('click', applyFilters);
                document.getElementById('reset-filters').addEventListener('click', function() {
                    document.getElementById('filter-text').value = '';
                    document.querySelectorAll(
                        '#filter-kategori input, #filter-year input, #filter-periode input, #filter-desa input, #filter-zona input'
                    ).forEach(i => i.checked = false);
                    addMarkers(reports);
                    if (markersGroup.getLayers().length) map.fitBounds(markersGroup.getBounds().pad(0.1));
                });

                // toggle collapse
                const collapseBtn = document.getElementById('collapse-filters');
                collapseBtn.addEventListener('click', function() {
                    const body = document.getElementById('filter-body');
                    if (body.style.display === 'none') body.style.display = '';
                    else body.style.display = 'none';
                });

                setTimeout(() => map.invalidateSize(), 200);
            });
        </script>
    @endpush
@endsection
