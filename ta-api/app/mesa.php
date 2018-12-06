<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class mesa extends Model
{
    //
    use SoftDeletes;

    protected $primaryKey = 'mes_id';

    protected $fillable = [
        'mes_id', 'mes_nome', 'mes_liberada'
    ];

    protected $dates = ['deleted_at'];
}
