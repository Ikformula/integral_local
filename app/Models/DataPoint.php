<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class DataPoint extends Model
{
    protected $fillable = [
        'week_range_id', 'name', 'score_card_form_field_id',
        'business_area_id', 'time_title', 'data_value',
        'is_computed', 'presenter_id', 'for_date'
    ];

    protected $dates = ['for_date'];

    public function weekRange()
    {
        return $this->belongsTo(WeekRange::class, 'week_range_id');
    }

    public function scoreCardFormField()
    {
        return $this->belongsTo(ScoreCardFormField::class, 'score_card_form_field_id');
    }

    /**
     * Scope to filter only data points where the related ScoreCardFormField is open
     */
    public function scopeWithOpenScoreCardFormField(Builder $query)
    {
        return $query->whereHas('scoreCardFormField', function ($q) {
            $q->whereDoesntHave('closures') // No closures exist
            ->orWhereHas('closures', function ($q2) {
                $q2->whereRaw('id = (
                        SELECT id FROM score_card_form_field_closures
                        WHERE score_card_form_field_id = score_card_form_fields.id
                        AND created_at <= data_points.for_date
                        ORDER BY created_at DESC
                        LIMIT 1
                    )')->where('action', 'open');
            });
        });
    }
}

