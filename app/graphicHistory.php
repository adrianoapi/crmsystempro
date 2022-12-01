<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GraphicHistory extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function defaulting()
    {
        return $this->belongsTo(Graphic::class, 'graphic_id', 'id');
    }

    public function getCreatedAtAttribute($value)
    {
        $value = date('Y-m-d H:i:s', strtotime("$value -180 minutes"));
        return $this->attributes['created_at'] = date("d/m/Y H:i:s", strtotime($value));
    }

}
