<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessArea extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'name_code', 'department'];

    public function co_presenters()
    {
        return $this->hasMany(CoPresenter::class);
    }
}
