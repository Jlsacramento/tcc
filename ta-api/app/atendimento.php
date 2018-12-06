<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class atendimento extends Model
{
    //
    use SoftDeletes;
    
    protected $table = 'atendimento';
    protected $primaryKey = 'ate_id';

    protected $fillable = [
        'ate_id', 'mes_id', 'usu_id', 'fun_id'
    ];

    protected $dates = ['deleted_at'];
}
