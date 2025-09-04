<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItAsset extends Model
{
    public $timestamps = true;

    use SoftDeletes;
    protected $fillable = [
      'user_id',
      'staff_ara_id',
      'model',
      'brand',
      'group',
      'office_location',
      'department_name',
      'device_type',
      'serial_number',
      'asset_tag',
      'status',
      'remarks',
      'sophos_endpoint'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function staffMember()
    {
        return $this->belongsTo(StaffMember::class, 'staff_ara_id', 'staff_ara_id');
    }

    public function assetMeta()
    {
        return $this->hasMany(AssetMeta::class, 'asset_id');
    }
    public function assetHistories()
    {
        return $this->hasMany(ItAssetHistory::class, 'asset_id');
    }
}
