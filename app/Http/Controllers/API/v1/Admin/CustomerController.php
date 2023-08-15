<?php

namespace App\Http\Controllers\API\v1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::all();
        return response()->json(['success' => true, 'customers' => $customers]);
    }

    public function show(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        return response()->json(['success' => true, 'customer' => $customer]);
    }

    // TODO: Create an Endpoint to create a customer from ADMIN
    // TODO: Create an Endpoint to update a User from ADMIN
    // TODO: Create an Endpoint to delete a user and or delete avatar
}
