<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Favorite;
use App\Shop;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function store(Request $request, $shopId)
    {
        Favorite::create(
          array(
            'user_id' => Auth::user()->id,
            'shop_id' => $shopId
          )
        );

        $shop = Shop::findOrFail($shopId);

        return redirect()
             ->action('Admin\ShopsController@show', $shop->id);
    }

    public function destroy($shopId, $favoriteId) {
        $shop = Shop::findOrFail($shopId);
        $shop->favorite_by()->findOrFail($favoriteId)->delete();

        return redirect()
                ->action('Admin\ShopsController@show', $shop->id);
    }
}
