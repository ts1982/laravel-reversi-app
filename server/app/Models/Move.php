<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Move extends Model {
    use HasFactory;

    protected $fillable = [
        'turn_id',
        'disc',
        'x',
        'y'
    ];
}
