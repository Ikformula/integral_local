<?php


namespace App\Models\Traits;

use App\Models\DataChangeHistory;
use Illuminate\Support\Facades\Auth;

trait TracksDataChanges
{
    public static function bootTracksDataChanges()
    {
        static::updating(function ($model) {
            $user = Auth::user();
            $original = $model->getOriginal();

            DataChangeHistory::create([
                'user_id' => $user ? $user->id : null,
                'staff_ara_id' => $user && $user->staff_member ? $user->staff_member->staff_ara_id : null,
                'table_name' => $model->getTable(),
                'model_name' => get_class($model),
                'record_id' => $model->getKey(),
                'previous_data' => json_encode($original),
            ]);
        });
    }
}

