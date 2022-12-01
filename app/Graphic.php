<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Graphic extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function graphicTradings()
    {
        return $this->hasMany(GraphicTrading::class, 'graphic_id', 'id')->orderBy('parcela', 'asc');
    }

    public function graphicHistories()
    {
        return $this->hasMany(GraphicHistory::class, 'graphic_id', 'id')->orderBy('created_at', 'desc');
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

    public function setTotalAttribute($value)
    {
        return $this->attributes['total'] = str_replace(',', '.', str_replace('.', '', $value));
    }

    public function getTotalAttribute($value)
    {
        return $this->attributes['total'] = number_format($value, 2, ",", ".");
    }
}
