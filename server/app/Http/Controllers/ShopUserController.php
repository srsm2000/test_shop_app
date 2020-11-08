<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ShopUser;
use App\Shop;
use Illuminate\Support\Facades\Auth;

class ShopUserController extends Controller
{
    public function store(Request $request, $shopId)
    {
        ShopUser::create(
          array(
            'user_id' => Auth::user()->id,
            'shop_id' => $shopId
          )
        );

        $shop = Shop::findOrFail($shopId);

        return redirect()
             ->action('HomeController@show', $shop->id);
    }

    public function destroy($shopId, $userId) {
        ShopUser::where('shop_id', $shopId)->where('user_id', $userId)->delete();
        // $shop->relationshipWithUsers->where('user_id', $userId)->delete;

        return redirect()
                ->action('HomeController@show', $shopId);
    }
}
