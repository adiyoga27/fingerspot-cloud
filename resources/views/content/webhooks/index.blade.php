@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Webhook Log</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Webhooks</li>
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
                        <input type="text" name="cloud_id" class="form-control" value="{{ request('cloud_id') }}" placeholder="Cloud ID">
                    </div>
                    <div class="col-md-3">
                        <select name="type_hit" class="form-select">
                            <option value="">Semua Type</option>
                            <option value="attlog" {{ request('type_hit')=='attlog'?'selected':'' }}>attlog</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary"><i class="bx bx-filter-alt"></i> Filter</button>
                        <a href="{{ route('webhooks.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Cloud ID</th>
                                <th>Type</th>
                                <th>Trans ID</th>
                                <th>Data</th>
                                <th>Waktu</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($webhooks as $webhook)
                            <tr>
                                <td>{{ $webhooks->firstItem() + $loop->index }}</td>
                                <td><code>{{ $webhook->cloud_id }}</code></td>
                                <td><span class="badge bg-info">{{ $webhook->type_hit }}</span></td>
                                <td>{{ $webhook->trans_id ?? '-' }}</td>
                                <td><small>{{ Str::limit(json_encode($webhook->data), 60) }}</small></td>
                                <td>{{ $webhook->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('webhooks.show', $webhook->id) }}" class="btn btn-sm btn-info">Detail</a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center text-muted">Belum ada webhook</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $webhooks->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
