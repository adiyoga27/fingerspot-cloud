@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Detail Webhook #{{ $webhook->id }}</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('webhooks.index') }}">Webhooks</a></li>
                    <li class="breadcrumb-item active">#{{ $webhook->id }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered mb-0">
                    <tr><th style="width:150px">ID</th><td>{{ $webhook->id }}</td></tr>
                    <tr><th>Cloud ID</th><td><code>{{ $webhook->cloud_id }}</code></td></tr>
                    <tr><th>Type Hit</th><td><span class="badge bg-info">{{ $webhook->type_hit }}</span></td></tr>
                    <tr><th>Trans ID</th><td>{{ $webhook->trans_id ?? '-' }}</td></tr>
                    <tr><th>Created</th><td>{{ $webhook->created_at->format('d M Y H:i:s') }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Data Payload</h5>
                <pre class="bg-light p-3 rounded" style="max-height:500px;overflow:auto">{{ json_encode($webhook->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
            </div>
        </div>
    </div>
</div>
@endsection
