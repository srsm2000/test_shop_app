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
             ->action('Admin\ShopsController@show', $shop->id);
    }

    public function destroy($shopId, $shopUserId) {
        $shop = Shop::findOrFail($shopId);
        $shop->favorite_by()->findOrFail($shopUserId)->delete();

        return redirect()
                ->action('Admin\ShopsController@show', $shop->id);
    }
}
