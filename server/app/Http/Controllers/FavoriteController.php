<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
             ->action('shopsController@show', $shop->id);
    }

    public function destroy($shopId, $favoriteId) {
        $shop = Shop::findOrFail($shopId);
        $shop->favorite_by()->findOrFail($favoriteId)->delete();

        return redirect()
                ->action('shopsController@show', $shop->id);
    }
}
