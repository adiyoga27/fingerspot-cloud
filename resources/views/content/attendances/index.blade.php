@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Absensi</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Absensi</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" class="row g-3 mb-3">
                    <div class="col-md-3">
                        <input type="date" name="date" class="form-control" value="{{ request('date') }}" placeholder="Filter Tanggal">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="employee_name" class="form-control" value="{{ request('employee_name') }}" placeholder="Nama Karyawan">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="cloud_id" class="form-control" value="{{ request('cloud_id') }}" placeholder="Cloud ID">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary me-1"><i class="bx bx-filter-alt"></i> Filter</button>
                        <a href="{{ route('attendances.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>PIN</th>
                                <th>Device</th>
                                <th>Cloud ID</th>
                                <th>Status</th>
                                <th>Verify</th>
                                <th>Waktu Scan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($attendances as $att)
                            @php
                                $badges = ['0'=>'success','1'=>'warning','2'=>'info','3'=>'primary'];
                                $labels = ['0'=>'MASUK','1'=>'PULANG','2'=>'ISTIRAHAT','3'=>'KEMBALI'];
                            @endphp
                            <tr>
                                <td>{{ $attendances->firstItem() + $loop->index }}</td>
                                <td>{{ $att->employee_name }}</td>
                                <td><code>{{ $att->pin }}</code></td>
                                <td>{{ $att->device_name }}</td>
                                <td><code>{{ $att->cloud_id }}</code></td>
                                <td><span class="badge bg-{{ $badges[$att->scan_status] ?? 'secondary' }}">{{ $labels[$att->scan_status] ?? $att->scan_status }}</span></td>
                                <td>{{ $att->scan_verify }}</td>
                                <td>{{ \Carbon\Carbon::parse($att->scan_at)->format('d M Y H:i') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="8" class="text-center text-muted">Belum ada data absensi</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $attendances->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
