<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Turn extends Model {
    use HasFactory;

    protected $fillable = [
        'game_id',
        'turn_count',
        'next_disc'
    ];

    public function game(): BelongsTo {
        return $this->belongsTo(Game::class);
    }

    public function move(): HasOne {
        return $this->hasOne(Move::class);
    }

    public function squares(): HasMany {
        return $this->hasMany(Square::class);
    }
}
