<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PdfRead extends Model
{
    protected $fillable = [
        'staff_ara_id',
        'pdf_id',
        'read_at',
        'opened_at',
    ];

    protected $dates = [
        'read_at',
        'opened_at',
    ];

    public function staff()
    {
        return StaffMember::where('staff_ara_id', $this->staff_ara_id)->first();
    }
}
