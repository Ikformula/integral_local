<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class StaffMemberOccasion extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $appends = [
        'messages_name'
    ];

    public function messages()
    {
        return $this->hasMany(OccasionMessage::class, 'occasion_id');
    }

    public function getMessagesNameAttribute()
    {
        if($this->occasion_type == 'death'){
            return 'Condolence';
        }

        return 'wishes';
    }

    public function occasion_images()
    {
        return $this->hasMany(OccasionImage::class, 'staff_member_occasion_id');
    }

    public function gallery_images()
    {
        return $this->occasion_images->where('category', 'gallery');
    }
}
