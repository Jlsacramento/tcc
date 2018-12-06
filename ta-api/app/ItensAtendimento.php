<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItensAtendimento extends Model
{
    //
    use SoftDeletes;

    protected $primaryKey = 'ite_ate_id';

    protected $fillable = [
        'ite_ate_id', 'ate_id', 'ite_preco', 'ite_id'
    ];

    protected $dates = ['deleted_at'];
}
