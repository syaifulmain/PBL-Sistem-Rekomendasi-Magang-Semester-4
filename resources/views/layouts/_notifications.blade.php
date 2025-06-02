@if (auth()->user()->unreadNotifications->count() > 0)
    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
        <h6 class="dropdown-header">Notifikasi</h6>
        <div class="dropdown-divider"></div>
        @foreach(auth()->user()->unreadNotifications as $notification)
            @php
                $status = $notification->data['status'];
                $lowongan = $notification->data['lowongan'];
                $pengajuanId = $notification->data['pengajuan_id'];
                
                // Determine icon based on status
                $iconClass = match($status) {
                    'disetujui' => 'fas fa-check-circle text-success',
                    'ditolak' => 'fas fa-times-circle text-danger',
                    'batal' => 'fas fa-ban text-warning',
                };
            @endphp
            
            <a class="dropdown-item" href="{{ route('mahasiswa.pengajuan-magang.show', $pengajuanId) }}">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="{{ $iconClass }}"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0">
                            @switch($status)
                                @case('disetujui')
                                    Pengajuan Disetujui
                                    @break
                                @case('ditolak')
                                    Pengajuan Ditolak
                                    @break
                                @case('batal')
                                    Pengajuan Dibatalkan
                                    @break
                            @endswitch
                        </h6>
                        <small class="text-muted">{{ $lowongan }}</small>
                    </div>
                </div>
            </a>
            <div class="dropdown-divider"></div>
        @endforeach
        <a class="dropdown-item text-center" href="{{ route('notifications.read-all') }}">
            Tandai Semua Sebagai Terbaca
        </a>
    </div>
@endif
