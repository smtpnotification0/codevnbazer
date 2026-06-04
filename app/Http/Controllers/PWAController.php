<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Services\PWAService;

class PWAController extends Controller
{
    public function manifestJson(PWAService $pwaService)
    {
        $output = $pwaService->generate();
        return response()->json($output);
    }

    public function offline(){
        return view('pwa.offline');
    }
}
