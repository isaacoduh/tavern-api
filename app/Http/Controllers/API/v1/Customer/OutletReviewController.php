<?php

namespace App\Http\Controllers\API\v1\Customer;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use App\Models\OutletReview;
use Illuminate\Http\Request;

class OutletReviewController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->merge(['outlet_id' => $id]);
        $validated_data = $this->validate($request, [
            
            'rating' => ['required', 'in:1,2,3,4,5'],
            'review' => []
        ]);

        $outlet = Outlet::findOrFail($id);

        $ratings_total = $outlet->ratings_total;
        $ratings_count = $outlet->ratings_count;

        $outlet_review = OutletReview::where('outlet_id', $id)->where('customer_id', $request->user()->id)->first();

        if($outlet_review){
            $outlet->ratings_total = $ratings_total + $validated_data['rating'] - $outlet_review->rating;
        } else {
            $outlet_review = new OutletReview();
            $outlet_review->customer_id = $request->user()->id;
            $outlet_review->outlet_id = $outlet->id;
            $outlet->ratings_total = $ratings_total + $validated_data['rating'];
            $outlet->ratings_count = $ratings_count + 1;
        }

        $outlet_review->fill($validated_data);
        $outlet_review->save();
        $outlet->save();


        return response()->json(['success' => true, 'review' => $outlet_review]);
    }

    public function me(Request $request, $id)
    {
        $outlet_reviews = OutletReview::where('customer_id', $request->user()->id)->where('outlet_id', $id)->get();
        return response()->json(['success' => true, 'reviews' => $outlet_reviews]);
    }
}
