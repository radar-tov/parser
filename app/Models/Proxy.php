<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proxy extends Model
{
    protected $table = 'proxys';
    protected $fillable = [
        'ip',
        'port',
        'type_id',
        'anonymi_level_id',
        'status_id'
    ];
}
