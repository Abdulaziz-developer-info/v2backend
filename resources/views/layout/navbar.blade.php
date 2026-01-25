        <header class="navbar navbar-expand-md d-print-none">
            <div class="container-xl">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
                    aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                    <a href="{{ url('/') }}" aria-label="Tabler">
                        <img src="{{ asset('logo01.png') }}" width="80" alt="">
                    </a>
                </div>
                <div class="navbar-nav flex-row order-md-last">
                    <div class="nav-item d-none d-md-flex me-3">
                        <div class="btn-list">
                            <a href="" class="btn btn-5" target="_blank" rel="noreferrer">
                                <i class="ti ti-world-www" style="font-size: 20px; color: #7d8491;"></i>&nbsp;
                                Tursino
                            </a>
                        </div>
                    </div>
                    <div class="d-none d-md-flex">
                        <div class="nav-item">
                            <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" title="Enable dark mode"
                                data-bs-toggle="tooltip" data-bs-placement="bottom">
                                <i class="ti ti-moon" style="font-size: 20px; color: #7d8491;"></i>
                            </a>
                            <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="Enable light mode"
                                data-bs-toggle="tooltip" data-bs-placement="bottom">
                                <i class="ti ti-sun" style="font-size: 20px; color: #7d8491;"></i>
                            </a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link d-flex lh-1 p-0 px-2" data-bs-toggle="dropdown"
                            aria-label="Open user menu">
                            <span class="avatar avatar-sm"
                                style="background-image: url('{{ auth('admin')->user()->avatar ? asset('storage/' . auth('admin')->user()->avatar) : asset('logo01.png') }}')">
                            </span>

                            <div class="d-none d-xl-block ps-2">
                                <div>{{ auth('admin')->user()->name }}</div>
                                <div class="mt-1 small text-secondary">{{ auth('admin')->user()->telegram }}</div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <a href="#" class="dropdown-item">Status</a>
                            <a href="./profile.html" class="dropdown-item">Profile</a>
                            <a href="#" class="dropdown-item">Feedback</a>
                            <div class="dropdown-divider"></div>
                            <a href="./settings.html" class="dropdown-item">Settings</a>
                            <a href="{{ url('/logout') }}" class="dropdown-item">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <header class="navbar-expand-md">
            <div class="collapse navbar-collapse" id="navbar-menu">
                <div class="navbar">
                    <div class="container-xl">
                        <div class="row flex-column flex-md-row flex-fill align-items-center">
                            <div class="col">
                                <ul class="navbar-nav">

                                    <!-- Bosh sahifa -->
                                    <li class="nav-item active">
                                        <a class="nav-link" href="{{ url('/') }}">
                                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                                <i class="ti ti-home" style="font-size: 20px; color: #7d8491;"></i>
                                            </span>
                                            <span class="nav-link-title">Bosh sahifa</span>
                                        </a>
                                    </li>

                                    <!-- Adminlar (dropdown EMAS) -->
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ url('admins') }}">
                                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                                <i class="ti ti-user" style="font-size: 20px; color: #7d8491;"></i>
                                            </span>
                                            <span class="nav-link-title">Adminlar</span>
                                        </a>
                                    </li>

                                    <!-- Tashkilot (dropdown) -->
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"
                                            data-bs-auto-close="outside" role="button" aria-expanded="false">
                                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                                <i class="ti ti-clipboard" style="font-size: 20px; color: #7d8491;"></i>
                                            </span>
                                            <span class="nav-link-title">Tashkilot</span>
                                        </a>

                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('organizations.create') }}">
                                                Yangi tashkilot
                                                <span class="badge badge-sm bg-green-lt ms-auto">Account ochish</span>
                                            </a>

                                            <a class="dropdown-item" href="{{ route('organizations.index') }}">
                                                Barcha tashkilotlar
                                            </a>

                                            <a class="dropdown-item"
                                                href="{{ route('organizations.index', ['status' => 'active']) }}">
                                                Doimiy tashkilotlar
                                            </a>

                                            <a class="dropdown-item"
                                                href="{{ route('organizations.index', ['status' => 'trial']) }}">
                                                Sinovdagi tashkilotlar
                                            </a>

                                            <a class="dropdown-item"
                                                href="{{ route('organizations.index', ['status' => 'deleted']) }}">
                                                Bekor bo‘lgan tashkilotlar
                                            </a>
                                        </div>
                                    </li>

                                    <!-- Default menu -->
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ url('/default-menus') }}">
                                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                                <i class="ti ti-layout-sidebar-right-inactive"
                                                    style="font-size: 20px; color: #7d8491;"></i>
                                            </span>
                                            <span class="nav-link-title">Default menu</span>
                                        </a>
                                    </li>

                                    <!-- Foydalanuvchilar (dropdown) -->
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"
                                            data-bs-auto-close="outside" role="button" aria-expanded="false">
                                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                                <i class="ti ti-clipboard"
                                                    style="font-size: 20px; color: #7d8491;"></i>
                                            </span>
                                            <span class="nav-link-title">Foydalanuvchilar</span>
                                        </a>

                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ url('/users/create') }}">
                                                Yangi foydalanuvchi
                                                <span class="badge badge-sm bg-green-lt ms-auto">Account ochish</span>
                                            </a>

                                            <a class="dropdown-item" href="{{ url('/users/0') }}">
                                                Barcha foydalanuvchilar
                                            </a>
                                        </div>
                                    </li>

                                </ul>
                            </div>

                            <!-- Sozlamalar -->
                            <div class="col col-md-auto">
                                <ul class="navbar-nav">
                                    <li class="nav-item">
                                        <a class="nav-link" href="#" data-bs-toggle="offcanvas"
                                            data-bs-target="#offcanvasSettings">
                                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                                <i class="ti ti-settings"
                                                    style="font-size: 20px; color: #7d8491;"></i>
                                            </span>
                                            <span class="nav-link-title">Sozlamalar</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </header>
