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
            })
            ->when(request()->filled('category5'), function($query) {
                $query->whereHas('categories', function($query) {
                    $query->where('id', request()->input('category5'));
                });
            })
            ->when(request()->filled('category6'), function($query) {
                $query->whereHas('categories', function($query) {
                    $query->where('id', request()->input('category6'));
                });
            })
            ->when(request()->filled('category7'), function($query) {
                $query->whereHas('categories', function($query) {
                    $query->where('id', request()->input('category7'));
                });
            })
            ->when(request()->filled('category8'), function($query) {
                $query->whereHas('categories', function($query) {
                    $query->where('id', request()->input('category8'));
                });
            })
            ->when(request()->filled('category9'), function($query) {
                $query->whereHas('categories', function($query) {
                    $query->where('id', request()->input('category9'));
                });
            })
            ->when(request()->filled('category10'), function($query) {
                $query->whereHas('categories', function($query) {
                    $query->where('id', request()->input('category10'));
                });
            })
            ->when(request()->filled('category11'), function($query) {
                $query->whereHas('categories', function($query) {
                    $query->where('id', request()->input('category11'));
                });
            })
            ->when(request()->filled('category12'), function($query) {
                $query->whereHas('categories', function($query) {
                    $query->where('id', request()->input('category12'));
                });
            })
            ->when(request()->filled('category13'), function($query) {
                $query->whereHas('categories', function($query) {
                    $query->where('id', request()->input('category13'));
                });
            })
            ->when(request()->filled('category14'), function($query) {
                $query->whereHas('categories', function($query) {
                    $query->where('id', request()->input('category14'));
                });
            })
            ->when(request()->filled('category15'), function($query) {
                $query->whereHas('categories', function($query) {
                    $query->where('id', request()->input('category15'));
                });
            })
            ->when(request()->filled('category16'), function($query) {
                $query->whereHas('categories', function($query) {
                    $query->where('id', request()->input('category16'));
                });
            })
            ->when(request()->filled('category17'), function($query) {
                $query->whereHas('categories', function($query) {
                    $query->where('id', request()->input('category17'));
                });
            })
            ->when(request()->filled('category18'), function($query) {
                $query->whereHas('categories', function($query) {
                    $query->where('id', request()->input('category18'));
                });
            })
            ->when(request()->filled('category19'), function($query) {
                $query->whereHas('categories', function($query) {
                    $query->where('id', request()->input('category19'));
                });
            })
            ->when(request()->filled('category20'), function($query) {
                $query->whereHas('categories', function($query) {
                    $query->where('id', request()->input('category20'));
                });
            });
    }

    // お気に入り登録ここから
    public function favoriteUsers()
    {
        return $this->belongsToMany('App\User');
    }

    public function relationshipWithUsers()
    {
        return $this->hasMany('App\ShopUser');
    }

    public function favorite_by()
    {
        return ShopUser::where('user_id', \Auth::user()->id);
    }

    public function favorite_user($favorite_user_id)
    {
        return User::where('id', $favorite_user_id->favorite_by()->id)->name;
    }

    
    // お気に入り登録ここまで
}
