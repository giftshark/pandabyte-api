<?php

namespace App\Models;

use App\Enums\MediaCollectionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Task extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'description',
        'due_date',
        'completed_at'
    ];

    /*
   |--------------------------------------------------------------------------
   | Relations
   |--------------------------------------------------------------------------
   */

    public function user(): BelongsTo
    {
       return $this->belongsTo(User::class);
    }

    public function photos(): MorphMany
    {
        return $this->morphMany(Media::class, 'model')
            ->where('collection_name', $this->defaultCollectionName());
    }


    /*
   |--------------------------------------------------------------------------
   | Media Collection
   |--------------------------------------------------------------------------
   */

    /**
     * Register media collections
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection($this->defaultCollectionName())
            ->registerMediaConversions(function () {
                $this->addMediaConversion('thumb')->width(254);
            });
    }

    /**
     * The default collection name to be use.
     *
     * @return string
     */
    public function defaultCollectionName() : string
    {
        return MediaCollectionType::TASK_PHOTOS->value;
    }
}
