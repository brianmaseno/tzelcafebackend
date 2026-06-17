<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'body',
        'audience',
        'sent_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }
}
