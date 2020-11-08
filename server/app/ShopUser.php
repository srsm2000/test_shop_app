<?php

namespace App;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopUser extends Model
{
    protected $fillable = ['user_id', 'shop_id'];

    public function Shop()
    {
      return $this->belongsTo('App\Shop');
    }

    public function User()
    {
      return $this->belongsTo('App\User');
    }
}
