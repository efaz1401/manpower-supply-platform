<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlertRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_type', 'thresholds', 'email_enabled', 'is_active',
    ];

    protected $casts = [
        'thresholds' => 'array',
        'email_enabled' => 'boolean',
        'is_active' => 'boolean',
    ];
}
