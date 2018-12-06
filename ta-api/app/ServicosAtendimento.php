<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServicosAtendimento extends Model
{
    //
    use SoftDeletes;

    protected $table = 'servicos_atendimento';

    protected $fillable = [
        'ser_ate_id', 'ate_id', 'ser_id', 'ser_preco'
    ];

    protected $dates = ['deleted_at'];
}
