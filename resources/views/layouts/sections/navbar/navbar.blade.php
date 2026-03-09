<ul class="navbar-nav flex-row align-items-center ms-auto">
    <li class="nav-item navbar-dropdown dropdown-user dropdown">
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
            <div class="avatar avatar-online bg-primary d-flex align-items-center justify-content-center rounded-circle text-white fw-bold" style="width:40px; height:40px;">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
            <li>
                <a class="dropdown-item" href="javascript:void(0);">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-online bg-primary d-flex align-items-center justify-content-center rounded-circle text-white fw-bold" style="width:40px; height:40px;">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <span class="fw-semibold d-block">{{ Auth::user()->name }}</span>
                            <small class="text-muted">Admin del Taller</small>
                        </div>
                    </div>
                </a>
            </li>
            <li>
                <div class="dropdown-divider"></div>
            </li>
            <li>
                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                    <i class="mdi mdi-account-outline me-2 text-primary"></i>
                    <span class="align-middle">Editar Perfil</span>
                </a>
            </li>
            <li>
                <div class="dropdown-divider"></div>
            </li>
            <li>
                <a class="dropdown-item text-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="mdi mdi-logout me-2"></i>
                    <span class="align-middle fw-bold">Cerrar Sesión</span>
                </a>
            </li>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </ul>
    </li>
</ul>