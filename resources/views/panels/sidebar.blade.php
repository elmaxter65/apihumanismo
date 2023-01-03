@php
$configData = Helper::applClasses();
@endphp
<div class="main-menu menu-fixed {{ $configData['theme'] === 'dark' || $configData['theme'] === 'semi-dark' ? 'menu-dark' : 'menu-light' }} menu-accordion menu-shadow"
    data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item me-auto">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <div class="brand-logo">

                    </div>
                </a>
            </li>
            <li class="nav-item nav-toggle">
                <a class="nav-link modern-nav-toggle pe-0" data-toggle="collapse">
                    <i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i>
                    <i class="d-none d-xl-block collapse-toggle-icon font-medium-4 text-primary" data-feather="disc"
                        data-ticon="disc"></i>
                </a>
            </li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main mt-3" id="main-menu-navigation" data-menu="menu-navigation">
            {{-- Foreach menu item starts --}}
            @if (isset($menuData[0]))
                @foreach ($menuData[0]->menu as $menu)
                    @if (isset($menu->navheader))
                        <li class="navigation-header">
                            <span>{!!__('locale.' . $menu->navheader) !!}</span>
                            <i data-feather="more-horizontal"></i>
                        </li>
                    @else
                        {{-- Add Custom Class with nav-item --}}
                        @php
                            $custom_classes = '';
                            if (isset($menu->classlist)) {
                                $custom_classes = $menu->classlist;
                            }
                        @endphp
                        <li
                            class="nav-item {{ $custom_classes }} {{ Route::currentRouteName() === $menu->slug ? 'active' : '' }}">
                            <a href="{{ isset($menu->url) ? url($menu->url) : 'javascript:void(0)' }}"
                                class="d-flex align-items-center"
                                target="{{ isset($menu->newTab) ? '_blank' : '_self' }}">
                                <i data-feather="{{ $menu->icon }}"></i>
                                <span class="menu-title text-truncate">{{ __('locale.' . $menu->name) }}</span>
                                @if (isset($menu->badge))
                                    <?php $badgeClasses = 'badge rounded-pill badge-light-primary ms-auto me-1'; ?>
                                    <span
                                        class="{{ isset($menu->badgeClass) ? $menu->badgeClass : $badgeClasses }}">{{ $menu->badge }}</span>
                                @endif
                            </a>
                            @if (isset($menu->submenu))
                                @include('panels/submenu', ['menu' => $menu->submenu])
                            @endif
                        </li>
                    @endif
                @endforeach
            @endif
            {{-- Foreach menu item ends --}}
        </ul>
        <style>
            .navbar-brand {
                width: 13rem;
                height: 5rem;
                margin: 0!important;
                padding: 0!important;
            }
            .brand-logo {
                top: 0;
                background-image: url("/images/logo/logo-alt-2.svg");
                width: 100%;
                height: 3rem;
                background-repeat: no-repeat;
                background-size: contain;
            }
            .avatar-segment {
                display: flex!important;
                position: absolute!important;
                bottom: 1rem!important;
                left: 1.5rem!important;
            }
            .text-container {
                padding-left: 1rem!important;
            }
            .line-avatar {
                display: flex!important;
                position: absolute!important;
                bottom: 5rem!important;
                width: 100%!important;
                justify-content: center!important;
            }

            .line-avatar hr {
                width: 16rem!important;
            }

            .menu-up-avatar {
                top: calc(100% - 32rem);
            }
        </style>
        <div class="line-avatar">
            <hr />
        </div>
        <div class="avatar-segment">
            @if (Auth::check())

                <a
                    class="d-flex"
                    data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <div class="avatar avatar-lg">
                        @if ( Auth::user()->avatar )
                        <img src="data:image/jpg;base64,{{base64_encode( Auth::user()->avatar )}}" alt="avatar">
                        <span class="avatar-status-online"></span>
                        @else
                        <img src="{{ asset( 'images/no-image.png' ) }}" alt="Image preview..." />
                        @endif
                    </div>
                    <div>
                        <div class="text-container d-flex">
                            {{ Auth::user()->name }}
                        </div>
                        <div class="text-container d-flex">
                            {{ Auth::user()->role->name }}
                        </div>
                    </div>
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="me-50" data-feather="power"></i> Logout
                    </a>
                    <form method="POST" id="logout-form" action="{{ route('logout') }}">
                        @csrf
                    </form>
                    <a class="dropdown-item" href="{{ route('users.profile') }}">
                        <i class="me-50" data-feather="user"></i>{{__('locale.profile')}}
                    </a>
                </div>
            @else
                <a class="dropdown-item"
                    href="{{ Route::has('login') ? route('login') : 'javascript:void(0)' }}">
                    <i class="me-50" data-feather="log-in"></i> Login
                </a>
            @endif

        </div>
    </div>
</div>
@section('page-script')
<script src="{{asset('js/scripts/components/components-dropdowns.js')}}"></script>
@endsection
<!-- END: Main Menu-->
