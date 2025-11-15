@php
    $modalUser = auth()->user();
    $defaultAvatar = asset('hyper/images/user_icon.png');
    $modalAvatar = $modalUser && !empty($modalUser->user_image)
        ? $modalUser->user_image_url
        : $defaultAvatar;
@endphp

<footer class="main-footer">
    &copy; <script>
        document.write(new Date().getFullYear())
    </script> <a href="https://member.org.in/">member.org.in</a>. All Rights Reserved.
</footer>

<!-- quick_user_toggle -->
<div class="modal modal-right fade" id="quick_user_toggle" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content slim-scroll3">
            <div class="modal-body p-30 bg-white">
                <div class="d-flex align-items-center justify-content-between pb-30">
                    <a href="#" class="btn btn-icon btn-danger-light btn-sm no-shadow" data-bs-dismiss="modal">
                        <span class="fa fa-close"></span>
                    </a>
                </div>
                <div>
                    <div class="d-flex flex-row">
                        <div class=""><img src="{{ $modalAvatar }}" alt="user" class="rounded bg-danger-light w-150" width="100"></div>
                        <div class="ps-20">
                            <h5 class="mb-0">{{ Auth::user()->name }}</h5>
                            <p class="my-5 text-fade">{{ Auth::user()->getRoleNames()->implode(', ') }}</p>
                            <a href="mailto:dummy@gmail.com"><span class="icon-Mail-notification me-5 text-success"><span class="path1"></span><span class="path2"></span></span> {{ Auth::user()->email }}</a>
                            @if(auth()->user()->hasRole('super-admin'))
                                <form method="POST" action="{{ route('superadmin.logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm mt-5">
                                        <span>Logout</span>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('custom_logout', $organization->slug) }}" class="btn btn-danger btn-sm mt-5">
                                    <span>Logout</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="dropdown-divider my-30"></div>
            </div>
        </div>
    </div>
</div>
<!-- /quick_user_toggle -->
