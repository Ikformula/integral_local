<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class LegalTeamDocument extends Model
{
    protected $fillable = [
        'title',
        'description',
        'remarks',
        'user_id',
        'file_name',
        'firm',
        'folder_id',
        'size_in_kilobytes',
        'size_in_megabytes',
        'process_type',
        'case_id'
    ];

    public function user_idRelation()
    {
        return $this->belongsTo(Auth\User::class, 'user_id');
    }

    public function folder_idRelation()
    {
        return $this->belongsTo(LegalTeamFolder::class, 'folder_id');
    }

    public function getFileSizeAttribute()
    {
        if($this->size_in_kilobytes < 1024)
            return $this->size_in_kilobytes.'Kb';
        return $this->size_in_megabytes.'Mb';
    }

    public function url()
    {
        return Storage::url($this->file_name);
    }
}
