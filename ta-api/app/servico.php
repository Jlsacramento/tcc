<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class servico extends Model
{
    //
    use SoftDeletes;
    protected $primaryKey = 'ser_id';

    protected $fillable = [
        'ser_id', 'ser_nome', 'ser_preco'
    ];

    protected $dates = ['deleted_at'];
}
