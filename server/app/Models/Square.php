<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Square extends Model {
    use HasFactory;

    protected $fillable = [
        'turn_id',
        'x',
        'y',
        'disc'
    ];
}
