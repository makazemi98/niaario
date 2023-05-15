<?php

namespace App\Models;

use App\Traits\HasCreator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\ModelStatus\HasStatuses;

class Inquiry extends Model implements HasMedia
{
    use HasFactory, HasStatuses, InteractsWithMedia, HasCreator, SoftDeletes;

    protected $fillable = ['client_id', 'title', 'description', 'remark', 'assigned_to', 'created_by'];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    |
    */

    /**
     * The inquiry's comments
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Inquiry client
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id', 'id')->withDefault();
    }

    /**
     * The user responsible for handling the inquiry
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to', 'id')->withDefault();
    }

    /**
     * Inquiry calculations
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function calculations()
    {
        return $this->hasMany(Calculation::class)->orderByDesc('id')->withTrashed();
    }

    /**
     * Inquiry future dues
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function futureDues()
    {
        return $this->hasMany(FutureDue::class)->orderByDesc('id')->withTrashed();
    }

    /**
     * The inquiry payments
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Functions
    |--------------------------------------------------------------------------
    |
    */



}
