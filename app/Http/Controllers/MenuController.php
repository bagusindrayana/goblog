<?php

namespace App\Http\Controllers;

use App\Setting;
use Harimayco\Menu\Models\Menus;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {   
        $selectedMenu = Setting::where('setting_name','main_menu')->first();
        $avilabeMenus = Menus::all();
        return view('admin.menu.index',compact('selectedMenu','avilabeMenus'));
    }
}
