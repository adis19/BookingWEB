<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use App\Models\ExtraService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $roomTypes = RoomType::all();
        $extraServices = ExtraService::all();
        
        return view('home', compact('roomTypes', 'extraServices'));
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }
}
