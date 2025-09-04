<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PdfFile extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'staff_ara_id',
        'filename',
        'path',
    ];

    public function readLogs()
    {
        return $this->hasMany(PdfRead::class, 'pdf_id');
    }

    public function categories()
    {
        $pdf_cts = PdfCategory::where('pdf_file_id', $this->id)->pluck('content_category_id');
        return ContentCategory::find($pdf_cts);
    }


}
