<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo mr-5" href="{{route('index')}}"><img src="{{ asset('images/logo.svg') }}"
                                                                               class="mr-2" alt="logo"/></a>
        <a class="navbar-brand brand-logo-mini" href="{{route('index')}}"><img src="{{ asset('images/logo-mini.svg') }}"
                                                                               alt="logo"/></a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="icon-menu text-white"></span>
        </button>
        <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item dropdown">
                @if (has_any_role('DOSEN','MAHASISWA'))
                <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#"
                   data-toggle="dropdown">
                    <i class="icon-bell mx-0"></i>
                    <span class="count">{{ auth()->user()->unreadNotifications->count() }}</span>
                </a>
                @endif
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list my-0"
                     aria-labelledby="notificationDropdown">
                    @if (auth()->user()->unreadNotifications->count() > 0)
                        <p class="mb-0 font-weight-normal float-left dropdown-header">Notifikasi</p>
                        @foreach(auth()->user()->unreadNotifications as $notification)
                            @php
                                $data = $notification->data;
                                $type = $notification->type;
                                $title = $data['title'] ?? '';
                                $message = $data['message'] ?? '';
                                $url = $data['url'] ?? '#';
                                
                                // Tentukan ikon dan warna berdasarkan tipe notifikasi
                                $iconClass = 'bg-info';
                                $icon = 'ti-info';
                                
                                if (strpos($type, 'PengajuanMagang') !== false) {
                                    if (strpos($message, 'disetujui') !== false) {
                                        $iconClass = 'bg-success';
                                        $icon = 'ti-check';
                                    } elseif (strpos($message, 'ditolak') !== false) {
                                        $iconClass = 'bg-danger';
                                        $icon = 'ti-close';
                                    }
                                } elseif (strpos($type, 'Bimbingan') !== false) {
                                    $iconClass = 'bg-primary';
                                    $icon = 'ti-user';
                                }
                            @endphp
                            
                            <a class="dropdown-item preview-item" href="{{ $url }}" onclick="markNotificationRead('{{ $notification->id }}')">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon {{ $iconClass }}">
                                        <i class="{{ $icon }} mx-0"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <h6 class="preview-subject font-weight-normal">{{ $title }}</h6>
                                    <p class="font-weight-light small-text mb-0 text-muted">
                                        {{ $message }}
                                    </p>
                                </div>
                            </a>
                        @endforeach
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item preview-item justify-content-center" onclick="readAllNotifications()" style="padding: 8px 16px; cursor: pointer;">
                            <h6 class="preview-subject font-weight-normal mb-0">Tandai Semua Sebagai Terbaca</h6>
                        </a>
                    @else
                        <div class="preview-item justify-content-center">
                            <div class="preview-item-content">
                                <p class="font-weight-light small-text mb-0 text-muted">
                                    Tidak ada notifikasi baru
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </li>
            <li class="nav-item nav-profile dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                    <img src="{{auth()->user()->getFotoProfilPath()}}" alt="profile"/>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                    <div class="dropdown-item">
                        <div class="d-flex align-items-center">
                            <img src="{{ auth()->user()->getFotoProfilPath() }}" alt="profile" class="rounded-circle mr-3" style="width: 40px; height: 40px;">
                            <div>
                                <h6 class="mb-1">{{ get_user_name(auth()->user()) }}</h6>
                                <small class="text-muted">
                                    @if(has_role('MAHASISWA'))
                                        NIM: {{ auth()->user()->mahasiswa->nim }}
                                    @elseif(has_role('DOSEN'))
                                        NIP: {{ auth()->user()->dosen->nip }}
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('profil.index') }}">
                        <i class="ti-user text-primary mr-2"></i>
                        Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="dropdown-item" type="submit">
                            <i class="ti-power-off text-primary mr-2"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                data-toggle="offcanvas">
            <span class="icon-menu"></span>
        </button>
    </div>
    <script>
        function markNotificationRead(notificationId) {
            $.ajax({
                url: `/notifications/mark-read/${notificationId}`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        // Update the notification count
                        const countElement = $('.count');
                        const currentCount = parseInt(countElement.text()) || 0;
                        countElement.text(currentCount - 1);
                        if (currentCount <= 1) {
                            countElement.hide();
                        }
                    }
                }
            });
        }

        function readAllNotifications() {
            $.ajax({
                url: '{{ route('notifications.read-all') }}',
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        // Hide all notifications
                        $('.preview-list').html(
                            '<div class="preview-item justify-content-center">' +
                            '<div class="preview-item-content">' +
                            '<p class="font-weight-light small-text mb-0 text-muted">' +
                            'Tidak ada notifikasi baru' +
                            '</p>' +
                            '</div>' +
                            '</div>');
                        
                        // Hide the count badge
                        $('.count').hide();
                    }
                }
            });
        }
    </script>
</nav>
