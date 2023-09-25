<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function edit($group)
    {
        $settings = Setting::where('group', '=', $group)->pluck('value', 'name');
        return view('settings.edit', [
            'group' => $group,
            'settings' => $settings
        ]);
    }

    public function update(Request $request, $group)
    {

        foreach($request->post('settings') as $name => $value){
            Setting::set($name, $value, $group);
        }

        foreach($request->file('settings') as $name => $file){
            if($file->isValid()){
                Setting::set($name,$file->store('assets', 'public'), $group);
            }
        }

        $settings = Setting::pluck('value', 'name');
        Cache::forget('app-settings');


        return redirect()->back()->with('message', 'Settings updated successfully.');
    }
}
