<?php

namespace App\Http\Controllers;

use App\Models\Tran;
use Illuminate\Http\Request;

class TransController extends Controller
{
    public function index(Request $request)
    {
        $trans = Tran::orderBy('id', 'desc')->paginate(20);
        return view('content.trans.index', compact('trans'));
    }

    public function show($id)
    {
        $tran = Tran::findOrFail($id);
        return view('content.trans.show', compact('tran'));
    }
}
