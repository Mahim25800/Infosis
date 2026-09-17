<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Url extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'original_url',
        'short_code',
        'click_count',
    ];

    protected function casts(): array
    {
        return [
            'click_count' => 'integer',
        ];
    }

    /**
     * The owner of this short link.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
