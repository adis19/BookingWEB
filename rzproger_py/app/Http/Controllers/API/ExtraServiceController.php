<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ExtraService;
use Illuminate\Http\Request;

class ExtraServiceController extends Controller
{
    /**
     * Получить список всех дополнительных услуг
     */
    public function index()
    {
        $extraServices = ExtraService::all();
        
        return response()->json([
            'data' => $extraServices
        ]);
    }
} 