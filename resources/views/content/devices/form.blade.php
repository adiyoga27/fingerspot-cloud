@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ isset($device) ? 'Edit' : 'Tambah' }} Device</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('devices.index') }}">Devices</a></li>
                    <li class="breadcrumb-item active">{{ isset($device) ? 'Edit' : 'Tambah' }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ isset($device) ? route('devices.update', $device->id) : route('devices.store') }}" method="POST">
                    @csrf
                    @if(isset($device)) @method('PUT') @endif

                    <div class="mb-3">
                        <label class="form-label">Nama Device</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $device->name ?? '') }}" placeholder="Masukkan nama device" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Cloud ID</label>
                        <input type="text" name="cloud_id" class="form-control @error('cloud_id') is-invalid @enderror"
                            value="{{ old('cloud_id', $device->cloud_id ?? '') }}" placeholder="C2630450C31E1824" required>
                        @error('cloud_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Thumbnail URL</label>
                        <input type="text" name="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror"
                            value="{{ old('thumbnail', $device->thumbnail ?? '') }}" placeholder="https://...">
                        @error('thumbnail') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save"></i> {{ isset($device) ? 'Update' : 'Simpan' }}
                        </button>
                        <a href="{{ route('devices.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
