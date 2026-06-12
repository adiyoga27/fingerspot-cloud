@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Detail Transaksi #{{ $tran->id }}</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trans.index') }}">Trans</a></li>
                    <li class="breadcrumb-item active">#{{ $tran->id }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Request (Hits)</h5></div>
            <div class="card-body">
                <pre class="bg-light p-3 rounded" style="max-height:400px;overflow:auto">{{ json_encode($tran->hits, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Response (Results)</h5></div>
            <div class="card-body">
                @php $results = is_string($tran->results) ? json_decode($tran->results, true) : $tran->results; @endphp
                <pre class="bg-light p-3 rounded" style="max-height:400px;overflow:auto">{{ json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
            </div>
        </div>
    </div>
</div>
@endsection
