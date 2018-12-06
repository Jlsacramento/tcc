<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class iten extends Model
{
    //
    use SoftDeletes;

    protected $primaryKey = 'ite_id';

    protected $fillable = [
        'ite_id', 'ite_nome', 'ite_preco', 'tip_ite_id'
    ];

    protected $dates = ['deleted_at'];
}
