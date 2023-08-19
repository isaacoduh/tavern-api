<?php

namespace App\Http\Controllers\API\v1\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $categories = $request->query('categories');
        if(isset($categories)){
            $categories = explode(',',$categories);
        }
        $min_rating = $request->query('min_rating');
        $min_price = $request->query('min_price');
        $max_price = $request->query('max_price');

        $productQuery = Product::withAll()->active();
        $shopQuery = Shop::withAll()->active();

        if(isset($q)){
            $productQuery = $productQuery->where('name','like', "%".$q."%");
            $shopQuery = $shopQuery->where('name','like', "%".$q."%");
        }

        if(isset($categories)){
            $productQuery = $productQuery->whereIn('category_id', $categories);
            $shopQuery->whereHas('categories', function($q) use ($categories){
                $q->whereIn('id', $categories);
            });
        }

        if(isset($min_rating)){
            $productQuery = $productQuery->where('rating', '>=', $min_rating);
            $shopQuery = $shopQuery->where('rating', '>=', $min_rating);
        }

        // maxprice with calculated price

        return response()->json(['success' => true, 'products' => $productQuery->get()]);
    }
}
