<?php

namespace App\Http\Controllers;

use App\Category;
use App\Shop;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $shops = Shop::with(['categories', 'days'])
            ->searchResults()
            ->paginate(9);

        $mapShops = $shops->makeHidden(['active', 'created_at', 'updated_at', 'deleted_at', 'photos', 'media']);
        $latitude = $shops->count() && (request()->filled('category') || request()->filled('search')) ? $shops->average('latitude') : 35.67;
        $longitude = $shops->count() && (request()->filled('category') || request()->filled('search')) ? $shops->average('longitude') : 139.75;

        return view('home', compact('categories', 'shops', 'mapShops', 'latitude', 'longitude'));
    }

    public function show(Shop $shop)
    {
        $shop->load(['categories', 'days']);

        return view('shop', compact('shop'));
    }

    public function top_page()
    {
        return view('top_page');
    }

}
