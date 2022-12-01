<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Defaulting extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function defaultingTradings()
    {
        return $this->hasMany(DefaultingTrading::class, 'defaulting_id', 'id')->orderBy('parcela', 'asc');
    }

    public function defaultingHistories()
    {
        return $this->hasMany(DefaultingHistory::class, 'defaulting_id', 'id')->orderBy('created_at', 'desc');
    }

    public function setMultaAttribute($value)
    {
        return $this->attributes['multa'] = str_replace(',', '.', str_replace('.', '', $value));
    }

    public function getMultaAttribute($value)
    {
        return $this->attributes['multa'] = number_format($value, 2, ",", ".");
    }

    public function setMParcelaValorAttribute($value)
    {
        return $this->attributes['m_parcela_valor'] = str_replace(',', '.', str_replace('.', '', $value));
    }

    public function getMParcelaValorAttribute($value)
    {
        return $this->attributes['m_parcela_valor'] = number_format($value, 2, ",", ".");
    }

    public function setSParcelaValorAttribute($value)
    {
        return $this->attributes['s_parcela_valor'] = str_replace(',', '.', str_replace('.', '', $value));
    }

    public function getSParcelaValorAttribute($value)
    {
        return $this->attributes['s_parcela_valor'] = number_format($value, 2, ",", ".");
    }

    public function setDtInadimplenciaAttribute($value)
    {
        $date = str_replace('/', '-', $value);
        return $this->attributes['dt_inadimplencia'] = date("Y-m-d", strtotime($date));
    }

    public function getDtInadimplenciaAttribute($value)
    {
        return $this->attributes['dt_inadimplencia'] = date("d/m/Y", strtotime($value));
    }
}
