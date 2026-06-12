@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">API Transaction Log</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Trans</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Hits (Request)</th>
                                <th>Results Count</th>
                                <th>Waktu</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($trans as $tran)
                            <tr>
                                <td>{{ $trans->firstItem() + $loop->index }}</td>
                                <td>{{ $tran->title }}</td>
                                <td><small>{{ Str::limit(json_encode($tran->hits), 50) }}</small></td>
                                <td>
                                    @php $results = is_array($tran->results) ? $tran->results : json_decode($tran->results, true); @endphp
                                    <span class="badge bg-info">{{ is_array($results) ? count($results) : 0 }}</span>
                                </td>
                                <td>{{ $tran->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('trans.show', $tran->id) }}" class="btn btn-sm btn-info">Detail</a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted">Belum ada transaksi</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $trans->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
