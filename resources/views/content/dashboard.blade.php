@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Dashboard</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-truncate font-size-14 mb-2">Total Karyawan</p>
                        <h4 class="mb-2">{{ $stats['employees'] }}</h4>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-primary rounded-3">
                            <i class="bx bx-user font-size-22"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-truncate font-size-14 mb-2">Total Device</p>
                        <h4 class="mb-2">{{ $stats['devices'] }}</h4>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-success rounded-3">
                            <i class="bx bx-devices font-size-22"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-truncate font-size-14 mb-2">Absensi Hari Ini</p>
                        <h4 class="mb-2">{{ $stats['attendance_today'] }}</h4>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-info rounded-3">
                            <i class="bx bx-calendar-check font-size-22"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-truncate font-size-14 mb-2">Total Absensi</p>
                        <h4 class="mb-2">{{ $stats['attendance_total'] }}</h4>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-warning rounded-3">
                            <i class="bx bx-list-check font-size-22"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Absensi Terbaru</h4>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>PIN</th>
                                <th>Device</th>
                                <th>Status</th>
                                <th>Waktu Scan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentAttendances as $att)
                            <tr>
                                <td>{{ $att->employee_name }}</td>
                                <td>{{ $att->pin }}</td>
                                <td>{{ $att->device_name }}</td>
                                <td>
                                    @php
                                        $badges = ['0' => 'success', '1' => 'warning', '2' => 'info', '3' => 'primary'];
                                        $labels = ['0' => 'MASUK', '1' => 'PULANG', '2' => 'ISTIRAHAT', '3' => 'KEMBALI'];
                                        $badge = $badges[$att->scan_status] ?? 'secondary';
                                        $label = $labels[$att->scan_status] ?? $att->scan_status;
                                    @endphp
                                    <span class="badge bg-{{ $badge }}">{{ $label }}</span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($att->scan_at)->format('d M Y H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada data absensi</td>
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
