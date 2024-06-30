<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turn extends Model {
    use HasFactory;

    protected $fillable = [
        'game_id',
        'turn_count',
        'next_disc'
    ];

    public function game(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
        return $this->belongsTo(Game::class);
    }

    public function squares(): \Illuminate\Database\Eloquent\Relations\HasMany {
        return $this->hasMany(Square::class);
    }
}
