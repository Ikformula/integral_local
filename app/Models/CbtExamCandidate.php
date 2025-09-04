<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CbtExamCandidate extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cbt_exam_candidates';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;


    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                  'email',
                  'staff_ara_id',
                  'cbt_exam_id',
                  'surname',
                  'first_name',
                  'other_names',
                  'age',
                  'gender',
                  'state',
                  'address',
                  'phone_number'
              ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Get the staffAra for this model.
     *
     * @return App\Models\StaffAra
     */
    public function staffAra()
    {
        return $this->belongsTo('App\Models\StaffMember','staff_ara_id', 'staff_ara_id');
    }

    /**
     * Get the CbtExam for this model.
     *
     * @return App\Models\CbtExam
     */
    public function CbtExam()
    {
        return $this->belongsTo('App\Models\CbtExam','cbt_exam_id','id');
    }

    /**
     * Get the cbtQuestionResponse for this model.
     *
     * @return App\Models\CbtQuestionResponse
     */
    public function cbtQuestionResponse()
    {
        return $this->hasOne('App\Models\CbtQuestionResponse','cbt_exam_candidate_id','id');
    }


    /**
     * Get created_at in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getCreatedAtAttribute($value)
    {
        return \DateTime::createFromFormat($this->getDateFormat(), $value)->format('j/n/Y g:i A');
    }

    /**
     * Get updated_at in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getUpdatedAtAttribute($value)
    {
        return \DateTime::createFromFormat($this->getDateFormat(), $value)->format('j/n/Y g:i A');
    }

}
