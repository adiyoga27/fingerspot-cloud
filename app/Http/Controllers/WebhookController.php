<?php

namespace App\Http\Controllers;

use App\Models\Webhooks;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function index(Request $request)
    {
        $query = Webhooks::query()->orderBy('id', 'desc');

        if ($request->filled('cloud_id')) {
            $query->where('cloud_id', $request->cloud_id);
        }

        if ($request->filled('type_hit')) {
            $query->where('type_hit', $request->type_hit);
        }

        $webhooks = $query->paginate(20)->appends($request->query());

        return view('content.webhooks.index', compact('webhooks'));
    }

    public function show($id)
    {
        $webhook = Webhooks::findOrFail($id);
        return view('content.webhooks.show', compact('webhook'));
    }
}
