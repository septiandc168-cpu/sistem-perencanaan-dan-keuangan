<div>
    <nav class="pc-sidebar">
        <div class="navbar-wrapper">
            <div class="m-header">
                <a href="../dashboard/index.html" class="b-brand text-primary">
                    <span>Aplikasi Perencanaan Kegiatan</span>
                </a>
            </div>
            <div class="navbar-content">
                <ul class="pc-navbar">

                    <x-sidebar.links title='Home' icon='ti ti-dashboard' route='home' />
                    @if (Auth::user()->role_id == 1)
                        <x-sidebar.links title='Data Users' icon='ti ti-users' route='users.index' />
                    @endif
                    <x-sidebar.links title='Data Pegawai' icon='ti ti-users' route='pegawai.index' />
                    <x-sidebar.links title='Data Bagian' icon='ti ti-users' route='bagian.index' />
                    <x-sidebar.links title='Rencana Kegiatan' icon='ti ti-map' route='rencana_kegiatan.index' />
                    
                    {{-- Menu Laporan Kegiatan untuk Admin dan Supervisor --}}
                    @if(in_array(Auth::user()->role->role_name, ['admin', 'supervisor']))
                        <x-sidebar.links title='Laporan Kegiatan' icon='ti ti-file-text' route='laporan_kegiatan.index' />
                    @endif

                </ul>
            </div>
        </div>
    </nav>
</div>
