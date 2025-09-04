<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\TracksDataChanges;

class SpiSectorUserPermission extends Model
{
    use TracksDataChanges;

    protected $table = 'spi_sector_user_permissions';

    protected $fillable = [
        'user_id',
        'sector_id'
    ];

    public function sector()
    {
        return $this->belongsTo(SpiSector::class, 'sector_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
