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
                    <x-sidebar.links title='Rencana Kegiatan' icon='ti ti-map' route='maps.index' />

                </ul>
            </div>
        </div>
    </nav>
</div>
