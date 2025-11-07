<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcsTransactionTax extends Model
{
    protected $fillable = [
      'transaction_id',
      'tax_name',
      'amount'
    ];
}
