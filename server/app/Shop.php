<?php

namespace App;

use App\Traits\MultiTenantModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\OpeningHours\OpeningHours;

class Shop extends Model implements HasMedia
{
    use SoftDeletes, MultiTenantModelTrait, InteractsWithMedia;

    public $table = 'shops';

    protected $appends = [
        'photos', 'thumbnail'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'active',
        'address',
        'created_at',
        'updated_at',
        'deleted_at',
        'description',
        'latitude',
        'longitude',
        'created_by_id',
    ];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->width(325)->height(210);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function days()
    {
        return $this->belongsToMany(Day::class)->withPivot('from_hours', 'from_minutes', 'to_hours', 'to_minutes');
    }

    public function getPhotosAttribute()
    {
        $files = $this->getMedia('photos');
        $files->each(function ($item) {
            $item->url       = $item->getUrl();
            $item->thumbnail = $item->getUrl('thumb');
        });

        return $files;
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function getWorkingHoursAttribute()
    {
        $hours = $this->days
            ->pluck('pivot', 'name')
            ->map(function($pivot) {
                return [
                    $pivot['from_hours'].':'.$pivot['from_minutes'].'-'.$pivot['to_hours'].':'.$pivot['to_minutes']
                ];
            });

        return OpeningHours::create($hours->toArray());
    }

    public function getThumbnailAttribute()
    {
        return $this->getFirstMediaUrl('photos', 'thumb');
    }

    public function scopeSearchResults($query)
    {
        return $query->where('active', 1)
            ->when(request()->filled('search'), function($query) {
                $query->where(function($query) {
                    $search = request()->input('search');
                    $query->where('name', 'LIKE', "%$search%")
                        ->orWhere('description', 'LIKE', "%$search%")
                        ->orWhere('address', 'LIKE', "%$search%");
                });
            })
            ->when(request()->filled('category1'), function($query) {
                $query->whereHas('categories', function($query) {
                    $query->where('id', request()->input('category1'));
                });
            })
            ->when(request()->filled('category2'), function($query) {
                $query->whereHas('categories', function($query) {
                    $query->where('id', request()->input('category2'));
                });
            })
            ->when(request()->filled('category3'), function($query) {
                $query->whereHas('categories', function($query) {
                    $query->where('id', request()->input('category3'));
                });
            })
            ->when(request()->filled('category4'), function($query) {
                $query->whereHas('categories', function($query) {
                    $query->where('id', request()->input('category4'));
                });
            });
    }

    // お気に入り登録ここから
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function favorites()
    {
        return $this->hasMany('App\Favorite');
    }

    public function favorite_by()
    {
        return Favorite::where('user_id', \Auth::user()->id)->first();
    }
    // お気に入り登録ここまで
}
