<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class ScoreCardFormField extends Model
{
    use SoftDeletes;

    protected $fillable = ['business_area_id', 'label', 'placeholder', 'form_type', 'unit', 'target_value'];

    public function businessArea()
    {
        return $this->belongsTo(BusinessArea::class, 'business_area_id')->withTrashed();
    }

    protected static function boot()
    {
        parent::boot();

        // Automatically apply the scope unless explicitly disabled
        static::addGlobalScope('openOrNoClosure', function (Builder $query) {
            $query->whereDoesntHave('closures')
                ->orWhereHas('closures', function ($q) {
                    $q->whereRaw('id = (SELECT id FROM score_card_form_field_closures
                                   WHERE score_card_form_field_id = score_card_form_fields.id
                                   ORDER BY created_at DESC LIMIT 1)')
                        ->where('action', 'open');
                });
        });
    }

    public function closures()
    {
        return $this->hasMany(ScoreCardFormFieldClosure::class);
    }

    /**
     * Disable the default scope when needed.
     */
    public static function withoutClosureScope()
    {
        return static::withoutGlobalScope('openOrNoClosure');
    }

}
