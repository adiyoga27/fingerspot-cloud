@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ isset($employee) ? 'Edit' : 'Tambah' }} Karyawan</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Karyawan</a></li>
                    <li class="breadcrumb-item active">{{ isset($employee) ? 'Edit' : 'Tambah' }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ isset($employee) ? route('employees.update', $employee->id) : route('employees.store') }}" method="POST">
                    @csrf
                    @if(isset($employee)) @method('PUT') @endif

                    <div class="mb-3">
                        <label class="form-label">Nama Karyawan</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $employee->name ?? '') }}" placeholder="Masukkan nama karyawan" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">PIN</label>
                        <input type="number" name="pin" class="form-control @error('pin') is-invalid @enderror"
                            value="{{ old('pin', $employee->pin ?? '') }}" placeholder="12345" required>
                        @error('pin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Device (Cloud ID)</label>
                        <input type="text" name="client_id" class="form-control @error('client_id') is-invalid @enderror"
                            value="{{ old('client_id', $employee->client_id ?? '') }}" placeholder="C2630450C31E1824" required>
                        @error('client_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Avatar URL</label>
                        <input type="text" name="avatar" class="form-control @error('avatar') is-invalid @enderror"
                            value="{{ old('avatar', $employee->avatar ?? '') }}" placeholder="https://...">
                        @error('avatar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save"></i> {{ isset($employee) ? 'Update' : 'Simpan' }}
                        </button>
                        <a href="{{ route('employees.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
