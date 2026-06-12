@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Firebase Notifications</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Notifications</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Riwayat Notifikasi FCM <span class="badge bg-info ms-1">{{ count($notifications) }}</span></h4>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width:5%">#</th>
                                <th style="width:25%">Judul</th>
                                <th style="width:30%">Pesan</th>
                                <th style="width:8%">Tipe</th>
                                <th style="width:10%">Target</th>
                                <th style="width:22%">Waktu (WITA)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($notifications as $i => $notif)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>
                                    <strong>{{ $notif['title'] ?? '-' }}</strong>
                                </td>
                                <td>{{ $notif['message'] ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $notif['type'] ?? 'info' }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $notif['to'] ?? '-' }}</span>
                                </td>
                                <td>
                                    @if(isset($notif['timestamp']))
                                        {{ \Carbon\Carbon::createFromTimestampMs($notif['timestamp'])->setTimezone('Asia/Makassar')->format('d M Y H:i:s') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bx bx-bell-off font-size-20 d-block mb-1"></i>
                                    Belum ada notifikasi
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
