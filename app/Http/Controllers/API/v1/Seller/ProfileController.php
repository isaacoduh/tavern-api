<?php

namespace App\Http\Controllers\API\v1\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $seller = $request->user();
        return response()->json(['success' => true, 'seller' => $seller]);
    }
}
