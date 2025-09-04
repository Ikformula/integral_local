<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CbtDataHistory extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cbt_data_histories';

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
                  'model_type',
                  'model_id',
                  'previous_value',
                  'changed_by_user_id'
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
     * Get the model for this model.
     *
     * @return App\Models\Model
     */
    public function model()
    {
        return $this->belongsTo('App\Models\Model','model_id');
    }

    /**
     * Get the changedByUser for this model.
     *
     * @return App\Models\ChangedByUser
     */
    public function changedByUser()
    {
        return $this->belongsTo('App\Models\ChangedByUser','changed_by_user_id');
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
