<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoServico extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'tip_ser_id';

    protected $fillable = [
        'tip_ser_id', 'tip_ser_nome'
    ];

    protected $dates = ['deleted_at'];
}
