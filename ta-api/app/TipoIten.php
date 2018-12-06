<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoIten extends Model
{
    //
    use SoftDeletes;

    protected $primaryKey = 'tip_ite_id';

    protected $fillable = [
        'tip_ite_id', 'tip_ite_nome'
    ];

    protected $dates = ['deleted_at'];
}
