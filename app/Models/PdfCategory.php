<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PdfCategory extends Model
{
    public function category()
    {
        return $this->belongsTo(ContentCategory::class, 'content_category_id');
    }

    public function pdfFile()
    {
        return $this->hasOne(PdfFile::class, 'pdf_file_id', 'id');
    }
}
