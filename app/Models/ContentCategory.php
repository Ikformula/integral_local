<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentCategory extends Model
{
    protected $fillable = [
        'name',
        'parent_category_id',
    ];

    public function parentCategory()
    {
        return $this->belongsTo(ContentCategory::class, 'parent_category_id');
    }

    public function childrenCategory()
    {
        return $this->hasMany(ContentCategory::class, 'parent_category_id');
    }

    public function categories()
    {
        return $this->hasMany(PdfCategory::class, 'content_category_id');
    }

    public function pdfFiles()
    {
        $pdf_cts = PdfCategory::where('content_category_id', $this->id)->pluck('pdf_file_id');
        return PdfFile::find($pdf_cts);
    }
}
