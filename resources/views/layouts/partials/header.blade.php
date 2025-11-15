<style>
    .logo-lg {
        font-size: 24px;
        font-weight: 600;
        color: #fff;
        line-height: 1;
        text-transform: uppercase;
        padding-left: 10px;
        margin-left: 10px;
    }

    .logo-lg .light-logo {
        color: #000!important;
    }

    .logo-mini img {
        width: 50px;
        height: 50px;
        max-width: 50px;
        max-height: 50px;
        object-fit: cover;
    }
</style>
@php
    $currentUser = auth()->user();
    $defaultAvatar = asset('hyper/images/avatar/avatar-13.png');
    $userAvatar = $currentUser && !empty($currentUser->user_image)
        ? $currentUser->user_image_url
        : $defaultAvatar;
    $prefersLightMode = $currentUser && $currentUser->view_mode === 'light';
@endphp
<header class="main-header">
    <div class="d-flex align-items-center logo-box justify-content-start">
        <!-- Logo -->
        <a href="#" class="logo">
            <!-- logo-->
            <div class="logo-mini w-40">
                @php
                    $headerLogoUrl = isset($organization) ? $organization->header_logo_url : asset('hyper/images/l-logo-ico.png');
                    $orgPrefixLabel = isset($organization) && $organization->org_prefix
                        ? $organization->org_prefix
                        : (isset($organization) ? $organization->name : config('app.name', 'AJPS'));
                @endphp
                @hasanyrole('organization-admin|member')
                    <span class="light-logo">
                        <img src="{{ $headerLogoUrl }}" alt="Organization Header Logo">
                    </span>
                    <span class="dark-logo">
                        <img src="{{ $headerLogoUrl }}" alt="Organization Header Logo">
                    </span>
                @else
                    <span class="light-logo">
                        <img src="{{ asset('hyper/images/l-logo-ico.png') }}" alt="logo">
                    </span>
                    <span class="dark-logo">
                        <img src="{{ asset('hyper/images/l-logo-ico.png') }}" alt="logo">
                    </span>
                @endhasanyrole
            </div>
            <div class="logo-lg">
                @hasanyrole('organization-admin|member')
                    <span class="light-logo">{{ $orgPrefixLabel }}</span>
                    <span class="dark-logo">{{ $orgPrefixLabel }}</span>
                @else
                    <span class="light-logo">AJPS</span>
                    <span class="dark-logo">AJPS</span>
                @endhasanyrole
            </div>
        </a>
    </div>
    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <div class="app-menu">
            <ul class="header-megamenu nav">
                <li class="btn-group nav-item">
                    <a href="#" class="waves-effect waves-light nav-link push-btn btn-primary-light" data-toggle="push-menu" role="button">
                        <i data-feather="menu"></i>
                    </a>
                </li>
                <!-- <li class="btn-group d-lg-inline-flex d-none">
                    <div class="app-menu">
                        <div class="search-bx mx-5">
                            <form>
                                <div class="input-group">
                                    <input type="search" class="form-control" placeholder="Search">
                                    <div class="input-group-append">
                                        <button class="btn" type="submit" id="button-addon3"><i class="icon-Search"><span class="path1"></span><span class="path2"></span></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </li> -->
            </ul>
        </div>

        <div class="navbar-custom-menu r-side">
            <ul class="nav navbar-nav">
                <li class="dropdown notifications-menu btn-group">
                    <label class="switch">
                        <a class="waves-effect waves-light btn-primary-light svg-bt-icon">
                            <input type="checkbox" data-mainsidebarskin="toggle" id="toggle_left_sidebar_skin" {{ $prefersLightMode ? 'checked' : '' }}>
                            <span class="switch-on"><i data-feather="moon"></i></span>
                            <span class="switch-off"><i data-feather="sun"></i></span>
                        </a>
                    </label>
                </li>

                <li class="btn-group nav-item d-xl-inline-flex d-none">
                    <a href="#" data-provide="fullscreen" class="waves-effect waves-light nav-link btn-primary-light svg-bt-icon" title="Full Screen">
                        <i data-feather="maximize"></i>
                    </a>
                </li>
                <!-- Control Sidebar Toggle Button -->
                <!-- <li class="btn-group nav-item d-xl-inline-flex d-none">
                    <a href="#" data-toggle="control-sidebar" title="Setting" class="waves-effect waves-light nav-link btn-primary-light svg-bt-icon">
                        <i data-feather="sliders"></i>
                    </a>
                </li> -->

                <!-- User Account-->
                <li class="dropdown user user-menu">
                    <a href="#" class="waves-effect waves-light dropdown-toggle w-auto l-h-12 bg-transparent p-0 no-shadow" title="User" data-bs-toggle="modal" data-bs-target="#quick_user_toggle">
                        @hasanyrole('organization-admin|member')
                            <img src="{{ $userAvatar }}" class="avatar rounded-circle bg-primary-light h-40 w-40" alt="User Avatar" />
                        @else
                            <img src="{{ $defaultAvatar }}" class="avatar rounded-circle bg-primary-light h-40 w-40" alt="User Avatar" />
                        @endhasanyrole
                    </a>
                </li>

            </ul>
        </div>
    </nav>
</header>

@auth
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const toggle = document.getElementById('toggle_left_sidebar_skin');
                if (!toggle) {
                    return;
                }

                toggle.addEventListener('change', function () {
                    const mode = this.checked ? 'light' : 'dark';

                    fetch("{{ route('user.view-mode') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({ view_mode: mode })
                    }).catch(function (error) {
                        console.error('Unable to update view mode preference', error);
                    });
                });
            });
        </script>
    @endpush
@endauth
