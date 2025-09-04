<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CbtQuestion extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cbt_questions';

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
                  'question',
                  'cbt_subject_id'
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
     * Get the CbtSubject for this model.
     *
     * @return App\Models\CbtSubject
     */
    public function CbtSubject()
    {
        return $this->belongsTo('App\Models\CbtSubject','cbt_subject_id','id');
    }

    /**
     * Get the cbtExamQuestion for this model.
     *
     * @return App\Models\CbtExamQuestion
     */
    public function cbtExamQuestion()
    {
        return $this->hasOne('App\Models\CbtExamQuestion','cbt_question_id','id');
    }

    /**
     * Get the cbtOption for this model.
     *
     * @return App\Models\CbtOption
     */
    public function cbtOptions()
    {
        return $this->hasMany('App\Models\CbtOption','cbt_question_id','id');
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
