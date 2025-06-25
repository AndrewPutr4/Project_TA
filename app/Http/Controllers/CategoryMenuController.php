<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;

class CategoryMenuController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $menus = Menu::all();
        return view('category_menu.index', compact('categories', 'menus'));
    }
}
