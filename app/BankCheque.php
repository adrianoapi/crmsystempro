<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankCheque extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function bankChequeTradings()
    {
        return $this->hasMany(BankChequeTrading::class, 'bank_cheque_id', 'id')->orderBy('parcela', 'asc');
    }

    public function BankChequePlots()
    {
        return $this->hasMany(BankChequePlot::class, 'bank_cheque_id', 'id')->orderBy('vencimento', 'asc');
    }

    public function bankChequeHistories()
    {
        return $this->hasMany(BankChequeHistory::class, 'bank_cheque_id', 'id')->orderBy('created_at', 'desc');
    }

    public function setDtVencimentoAttribute($value)
    {
        $date = str_replace('/', '-', $value);
        return $this->attributes['dt_vencimento'] = date("Y-m-d", strtotime($date));
    }

    public function getDtVencimentoAttribute($value)
    {
        return $this->attributes['dt_vencimento'] = date("d/m/Y", strtotime($value));
    }

    public function setValorAttribute($value)
    {
        return $this->attributes['valor'] = str_replace(',', '.', str_replace('.', '', $value));
    }

    public function getValorAttribute($value)
    {
        return $this->attributes['valor'] = number_format($value, 2, ",", ".");
    }
}
