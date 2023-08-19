<?php

namespace App\Http\Controllers\API\v1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        return response()->json(['success' => true, 'categories' => $categories]);
    }

    public function store(Request $request)
    {
        $validated_data = $this->validate($request, [
            'name' => ['required'],
            'description' => [],
            'active' => ['required','boolean'],
        ]);

        $category = new Category($validated_data);
        // save image
        $category->save();
        return response()->json(['success' => true, 'category' =>  $category]);
    }

    public function show(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        return response()->json(['success' => true, 'category' => $category]);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $validated_data = $this->validate($request, [
            'name' => ['required'],
            'description' => [],
            'active' => ['boolean'],
        ]);
        // save image
        $category->update($validated_data);
        $category->save();
        return response()->json(['success' => true, 'message' => 'Category Updated!']);  
    }

    // remove image function.
}
