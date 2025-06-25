<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $categories = \App\Models\Category::all();
        $categoryId = $request->query('category');
        $selectedCategory = null;
        if ($categoryId) {
            $selectedCategory = \App\Models\Category::find($categoryId);
            $foods = \App\Models\Category::where('category_id', $categoryId)->get();
        } else {
            $foods = \App\Models\Category::all();
        }
        return view('welcome', compact('categories', 'selectedCategory', 'foods'));
    }
}