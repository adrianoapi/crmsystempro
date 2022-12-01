<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankChequeHistory extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function bankCheques()
    {
        return $this->belongsTo(BankCheque::class, 'bank_cheque_id', 'id');
    }

    public function getCreatedAtAttribute($value)
    {
        $value = date('Y-m-d H:i:s', strtotime("$value -180 minutes"));
        return $this->attributes['created_at'] = date("d/m/Y H:i:s", strtotime($value));
    }

}
