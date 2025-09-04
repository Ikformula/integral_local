<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workflow extends Model
{
    protected $fillable = [
      'title',
      'type',
      'file_path',
      'originator_staff_ara_id',
      'approver_staff_ara_id',
    ];

    protected $dates = [
      'approved_at',
      'rejected_at',
    ];

    public function originator()
    {
        return StaffMember::where('staff_ara_id', $this->originator_staff_ara_id)->first();
    }
    public function approver()
    {
        return StaffMember::where('staff_ara_id', $this->approver_staff_ara_id)->first();
    }
}
