<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
            <a href="{{route('supervisor.dashboard')}}" class="nav-link text-white {{(Request::routeIs('supervisor.dashboard') ? 'active':'')}}">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Dashboard</p>
            </a>
        </li>

           <li class="nav-item">
            <a href="{{route('supervisor.kelola-supervisor.index')}}" class="nav-link text-white {{(Request::routeIs('supervisor.kelola-supervisor.index') ? 'active':'')}}">
                <i class="nav-icon fas fa-users-cog"></i> 
                <p>Kelola Supervisior</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{route('supervisor.kelola-tenagakerja.index')}}" class="nav-link text-white {{(Request::routeIs('supervisor.kelola-tenagakerja.index') ? 'active':'')}}">
                <i class="nav-icon fas fa-users-cog"></i> 
                <p>Kelola Tenaga Kerja</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{route('supervisor.jadwal-pemeliharaan.index')}}" class="nav-link text-white {{(Request::routeIs('supervisor.jadwal-pemeliharaan.index') ? 'active':'')}}">
                <i class="nav-icon fas fa-calendar-alt"></i> 
                <p>Jadwal Pemeliharaan</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="#" class="nav-link text-white">
                <i class="nav-icon fas fa-tools"></i> 
                <p>Pelaksanaan Pemeliharaan</p>
            </a>
        </li>
                        
        <li class="nav-item">
            <a href="{{route('supervisor.stok-onderdil.index')}}" class="nav-link text-white {{(Request::routeIs('supervisor.stok-onderdil.index') ? 'active':'')}}">
                <i class="nav-icon fas fa-warehouse"></i>
                <p>Manajemen Stok</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="#" class="nav-link text-white">
                <i class="nav-icon fas fa-file-alt"></i> 
                <p>Laporan Pemeliharaan</p>
            </a>
        </li>

                                                          
        <li class="nav-item">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" hidden>
                @csrf
            </form>
            <a href="#" class="nav-link text-white @yield('')"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="nav-icon fas fa-sign-out"></i>
                <p>
                    Logout
                </p>
            </a>
        </li>          
    </ul>
</nav>