<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcsClient extends Model
{
    protected $fillable = [
        'name',
        'current_balance',
        'approved_balance',
        'service_charge_amount',
        'deal_code',
        'account_type',
        'select_category'
    ];

    public function clientUsers()
    {
        return $this->hasMany(EcsClientUser::class, 'client_id');
    }

    public function getNameAndBalanceAttribute()
    {
//        return $this->name.' | '.$this->deal_code.' (₦'.number_format($this->current_balance).' - ₦'.number_format($this->approved_balance).')';
        return $this->name . ' | ' . $this->deal_code . ' (₦' . number_format($this->current_balance) . ')';
    }

    public function summaries()
    {
        return $this->hasMany(EcsClientAccountSummary::class, 'client_id')->orderBy('for_date', 'ASC');
    }

    public function taxes()
    {
        return json_decode($this->enabled_tax_columns);
    }

    public function fees()
    {
        return json_decode($this->enabled_fee_columns);
    }
}
