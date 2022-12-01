<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankChequeTrading extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function bankCheques()
    {
        return $this->belongsTo(BankCheque::class, 'bank_cheque_id', 'id');
    }

    public function setVencimentoAttribute($value)
    {
        $date = str_replace('/', '-', $value);
        return $this->attributes['vencimento'] = date("Y-m-d", strtotime($date));
    }

    public function getVencimentoAttribute($value)
    {
        return $this->attributes['vencimento'] = date("d/m/Y", strtotime($value));
    }

    public function setValorAttribute($value)
    {
        return $this->attributes['valor'] = str_replace(',', '.', str_replace('.', '', $value));
    }

    public function getValorAttribute($value)
    {
        return $this->attributes['valor'] = number_format($value, 2, ",", ".");
    }

    public function setDtPagamentoAttribute($value)
    {
        $date = str_replace('/', '-', $value);
        return $this->attributes['dt_pagamento'] = date("Y-m-d", strtotime($date));
    }

    public function getDtPagamentoAttribute($value)
    {
        if(strlen($value)){
            return $this->attributes['dt_pagamento'] = date("d/m/Y", strtotime($value));
        }
    }

    public function setValorPagoAttribute($value)
    {
        return $this->attributes['valor_pago'] = str_replace(',', '.', str_replace('.', '', $value));
    }

    public function getValorPagoAttribute($value)
    {
        return $this->attributes['valor_pago'] = number_format($value, 2, ",", ".");
    }
}
