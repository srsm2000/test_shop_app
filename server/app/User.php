<?php

namespace App;

use App\Notifications\VerifyUserNotification;
use Carbon\Carbon;
use Hash;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use SoftDeletes, Notifiable, HasApiTokens;

    public $table = 'users';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = [
        'updated_at',
        'created_at',
        'deleted_at',
        'email_verified_at',
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'created_at',
        'updated_at',
        'deleted_at',
        'remember_token',
        'email_verified_at',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        self::created(function (User $user) {
            $registrationRole = config('panel.registration_default_role');

            if (!$user->roles()->get()->contains($registrationRole)) {
                $user->roles()->attach($registrationRole);
            }
        });
    }

    public function shops()
    {
        return $this->hasMany(Shop::class, 'created_by_id', 'id');
    }

    public function getEmailVerifiedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setEmailVerifiedAtAttribute($value)
    {
        $this->attributes['email_verified_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    // 中略
    /**
    * このユーザがお気に入りしてる1以上のshop。
    */
    public function favoritesShops()
    {
        return $this->belongsToMany(User::class, 'favorites', 'user_id', 'shop_id')->withTimestamps();
    }

    /**
    * shopをお気に入りにしてる1以上のユーザ。
    */
    public function favoritesUser()
    {
        return $this->belongsToMany(User::class, 'favorites', 'shop_id', 'user_id')->withTimestamps();
    }

    /**
    * $shop_idで指定されたshopをお気に入り登録する。
    *
    * @param int $userId
    * @return bool
    */
    public function favorite($userId)
    {
        // すでにお気に入りしているかの確認
        $exist = $this->is_favorite($shopId);
        // 相手が自分自身かどうかの確認
        $its_me = $this->id == $shopId;

        if ($exist || $its_me) {
        // すでにお気に入り登録していればお気に入り登録を外す
            return false;
        } else {
    // お気に入り登録していなければお気に入り登録をする
            $this->favorite()->attach($userId);
            return true;
        }

    }
    /**
    * $shop_idで指定されたshopをお気に入り登録を外す。
    *
    * @param int $userId
    * @return bool
    */
    public function unfavorite($userId)
    {
        // すでにフォローしているかの確認
        $exist = $this->is_favorite($userId);
        // 相手が自分自身かどうかの確認
        $its_me = $this->id == $userId;

        if ($exist && !$its_me) {
            // すでにフォローしていればフォローを外す
            $this->favorite()->detach($shopId);
            return true;
        } else {
            // 未フォローであれば何もしない
            return false;
        }
    }

    /**
    * 指定された $userIdのユーザをこのユーザがフォロー中であるか調べる。フォロー中ならtrueを返す。
    *
    * @param int $userId
    * @return bool
    */
    public function is_favorite($userId)
    {
    // フォロー中ユーザの中に $userIdのものが存在するか
        return $this->favorite()->where('shop_id', $userId)->exists();
    }
}
