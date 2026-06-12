@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Karyawan</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Karyawan</li>
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
                    <h4 class="card-title mb-0">Daftar Karyawan</h4>
                    <a href="{{ route('employees.create') }}" class="btn btn-primary waves-effect waves-light">
                        <i class="bx bx-plus"></i> Tambah Karyawan
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Avatar</th>
                                <th>Nama</th>
                                <th>PIN</th>
                                <th>Device (Cloud ID)</th>
                                <th>Tgl Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($employees as $emp)
                            <tr>
                                <td>{{ $employees->firstItem() + $loop->index }}</td>
                                <td>
                                    @if($emp->avatar)
                                        <img src="{{ $emp->avatar }}" alt="" class="rounded-circle" width="32" height="32">
                                    @else
                                        <span class="avatar-title bg-primary rounded-circle font-size-12">-</span>
                                    @endif
                                </td>
                                <td>{{ $emp->name }}</td>
                                <td><code>{{ $emp->pin }}</code></td>
                                <td>{{ $emp->device->name ?? '-' }} <small class="text-muted">({{ $emp->client_id }})</small></td>
                                <td>{{ $emp->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('employees.edit', $emp->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('employees.destroy', $emp->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus karyawan ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center text-muted">Belum ada karyawan</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $employees->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
