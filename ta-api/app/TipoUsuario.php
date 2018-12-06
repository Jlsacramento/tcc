<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoUsuario extends Model
{
    //

    protected $fillable = [
        'tip_usu_id', 'tip_nome'
    ];
}
