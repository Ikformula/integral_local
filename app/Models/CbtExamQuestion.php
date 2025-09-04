<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CbtExamQuestion extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cbt_exam_questions';

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
                  'cbt_exam_id',
                  'cbt_question_id'
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
     * Get the CbtExam for this model.
     *
     * @return App\Models\CbtExam
     */
    public function CbtExam()
    {
        return $this->belongsTo('App\Models\CbtExam','cbt_exam_id','id');
    }

    /**
     * Get the CbtQuestion for this model.
     *
     * @return App\Models\CbtQuestion
     */
    public function CbtQuestion()
    {
        return $this->belongsTo('App\Models\CbtQuestion','cbt_question_id','id');
    }

    /**
     * Get the cbtQuestionResponse for this model.
     *
     * @return App\Models\CbtQuestionResponse
     */
    public function cbtQuestionResponse()
    {
        return $this->hasOne('App\Models\CbtQuestionResponse','cbt_exam_question_id','id');
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
