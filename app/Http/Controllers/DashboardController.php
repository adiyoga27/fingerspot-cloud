<?php

namespace App\Http\Controllers;

use App\Models\Devices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index(Request $request) {
        $result = Http::withHeaders([
            'Authorization' => 'Bearer C613PAKIHDWXJK5D'
        ])->post("https://developer.fingerspot.io/api/get_attlog",[
            'trans_id' => 2,
            'cloud_id' => Devices::first()->cloud_id,
            'start_date' => '2024-07-06',
            'end_date' => '2024-07-07',
        ]);

        // dd($result->json());
    }
}
