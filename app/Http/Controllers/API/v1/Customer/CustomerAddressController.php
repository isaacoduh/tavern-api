<?php

namespace App\Http\Controllers\API\v1\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;

class CustomerAddressController extends Controller
{
    public function index(Request $request)
    {
        $addresses = CustomerAddress::where('customer_id', $request->user()->id)
                    ->where('active',true)->get();
        return response()->json(['addresses' => $addresses]);
    }

    public function store(Request $request)
    {
        $validated_data = $this->validate($request,
            [
                'type' => ['required'],
                'address' => ['required'],
                'city' => ['required'],
                'postcode' => ['required']
            ]
        );

        $address = CustomerAddress::create([
            'address' => $validated_data['address'],
            'type' => $validated_data['type'],
            'city' => $validated_data['city'],
            'postcode' => $validated_data['postcode'],
            'customer_id' => $request->user()->id,
        ]);

        return response()->json(['success' => true, 'message' => 'Address Saved!']);
    }

    public function destroy(Request $request, $id)
    {
        $address = CustomerAddress::where('customer_id', $request->user()->id)->where('id',$id)->first();
        $address->delete();
        return response()->json(['success' => true, 'message' => 'Address Deleted!']);
    }

    public function update(Request $request, $id)
    {
        $address = CustomerAddress::where('customer_id', $request->user()->id)->where('id',$id)->first();
        $validated_data = $this->validate($request,
            [
                'type' => ['required'],
                'address' => ['required'],
                'city' => ['required'],
                'postcode' => ['required']
            ]
        );
        $address->update($validated_data);
        $address->save();
        return response()->json(['success' => true, 'message' => 'Address Updated!']);
    }

    public function selected(Request $request, $id)
    {
        $address = CustomerAddress::where('customer_id', $request->user()->id)->where('id',$id)->first();
        $address->selected = true;
        $address->save();
        return response()->json(['success' => true, 'message' => 'Address Selected!']);

    }
}
