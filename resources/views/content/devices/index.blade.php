@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Devices</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Devices</li>
                </ol>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Daftar Device</h4>
                    <a href="{{ route('devices.create') }}" class="btn btn-primary waves-effect waves-light">
                        <i class="bx bx-plus"></i> Tambah Device
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Cloud ID</th>
                                <th>Thumbnail</th>
                                <th>Tgl Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($devices as $device)
                            <tr>
                                <td>{{ $devices->firstItem() + $loop->index }}</td>
                                <td>{{ $device->name }}</td>
                                <td><code>{{ $device->cloud_id }}</code></td>
                                <td>{{ $device->thumbnail ?? '-' }}</td>
                                <td>{{ $device->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('devices.edit', $device->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('devices.destroy', $device->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus device ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted">Belum ada device</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $devices->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
