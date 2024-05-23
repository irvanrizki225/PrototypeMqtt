<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index()
    {
        $devices = Device::all();
        return view('test', compact('devices'));
    }

    public function store(Request $request)
    {
        $device = new Device();
        $device->name = $request->name;
        $device->topic = $request->topic;
        $device->save();
        return redirect()->route('home');
    }

    public function destroy($id)
    {
        $device = Device::find($id);
        $device->delete();
        return redirect()->route('home');
    }

    public function edit($id)
    {
        $device = Device::find($id);
        return view('edit', compact('device'));
    }

    public function update(Request $request, $id)
    {
        $device = Device::find($id);
        $device->name = $request->name;
        $device->save();
        return redirect()->route('home');
    }

}
