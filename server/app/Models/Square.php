<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Square extends Model {
    use HasFactory;

    protected $fillable = [
        'turn_id',
        'x',
        'y',
        'disc'
    ];

    public function turn(): BelongsTo {
        return $this->belongsTo(Turn::class);
    }
}
