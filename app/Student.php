<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function setNascimentoAttribute($value)
    {
        $date = str_replace('/', '-', $value);
        return $this->attributes['nascimento'] = date("Y-m-d", strtotime($date));
    }

    public function getNascimentoAttribute($value)
    {
        return $this->attributes['nascimento'] = date("d/m/Y", strtotime($value));
    }
}
