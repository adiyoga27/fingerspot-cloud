<?php

namespace App\Http\Controllers;

use App\Models\Devices;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index()
    {
        $devices = Devices::orderBy('id', 'desc')->paginate(15);
        return view('content.devices.index', compact('devices'));
    }

    public function create()
    {
        return view('content.devices.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:50',
            'cloud_id' => 'required|string|max:50|unique:devices,cloud_id',
            'thumbnail' => 'nullable|string|max:255',
        ]);

        Devices::create($data);

        return redirect()->route('devices.index')->with('success', 'Device berhasil ditambahkan');
    }

    public function edit($id)
    {
        $device = Devices::findOrFail($id);
        return view('content.devices.form', compact('device'));
    }

    public function update(Request $request, $id)
    {
        $device = Devices::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:50',
            'cloud_id' => 'required|string|max:50|unique:devices,cloud_id,' . $device->id,
            'thumbnail' => 'nullable|string|max:255',
        ]);

        $device->update($data);

        return redirect()->route('devices.index')->with('success', 'Device berhasil diupdate');
    }

    public function destroy($id)
    {
        Devices::destroy($id);
        return redirect()->route('devices.index')->with('success', 'Device berhasil dihapus');
    }
}
