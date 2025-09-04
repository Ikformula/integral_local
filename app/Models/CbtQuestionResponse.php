<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CbtQuestionResponse extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cbt_question_responses';

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
                  'cbt_exam_question_id',
                  'cbt_option_id',
                  'cbt_exam_candidate_id',
                  'is_history'
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
     * Get the CbtExamQuestion for this model.
     *
     * @return App\Models\CbtExamQuestion
     */
    public function CbtExamQuestion()
    {
        return $this->belongsTo('App\Models\CbtExamQuestion','cbt_exam_question_id','id');
    }

    /**
     * Get the CbtOption for this model.
     *
     * @return App\Models\CbtOption
     */
    public function CbtOption()
    {
        return $this->belongsTo('App\Models\CbtOption','cbt_option_id','id');
    }

    /**
     * Get the CbtExamCandidate for this model.
     *
     * @return App\Models\CbtExamCandidate
     */
    public function CbtExamCandidate()
    {
        return $this->belongsTo('App\Models\CbtExamCandidate','cbt_exam_candidate_id','id');
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
