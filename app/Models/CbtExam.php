<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CbtExam extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cbt_exams';

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
                  'title',
                  'start_at',
                  'duration_in_minutes',
                  'creator_user_id'
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
     * Get the creatorUser for this model.
     *
     * @return App\Models\Auth\User
     */
    public function creatorUser()
    {
        return $this->belongsTo('App\Models\Auth\User','creator_user_id');
    }

    /**
     * Get the cbtExamCandidate for this model.
     *
     * @return App\Models\CbtExamCandidate
     */
    public function cbtExamCandidates()
    {
        return $this->hasMany('App\Models\CbtExamCandidate','cbt_exam_id','id');
    }

    /**
     * Get the cbtExamQuestion for this model.
     *
     * @return App\Models\CbtExamQuestion
     */
    public function cbtExamQuestion()
    {
        return $this->hasOne('App\Models\CbtExamQuestion','cbt_exam_id','id');
    }

    /**
     * Set the start_at.
     *
     * @param  string  $value
     * @return void
     */
//    public function setStartAtAttribute($value)
//    {
//        $this->attributes['start_at'] = !empty($value) ? \DateTime::createFromFormat('j/n/Y g:i A', $value) : null;
//    }

    /**
     * Get start_at in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getStartAtAttribute($value)
    {
        return \DateTime::createFromFormat($this->getDateFormat(), $value)->format('j/n/Y g:i A');
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
