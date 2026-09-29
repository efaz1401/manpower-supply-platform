<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RateCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id', 'supply_contract_id', 'profession',
        'monthly_rate', 'hourly_rate', 'overtime_rate', 'currency',
    ];

    protected $casts = [
        'monthly_rate' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
        'overtime_rate' => 'decimal:2',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function supplyContract(): BelongsTo
    {
        return $this->belongsTo(SupplyContract::class);
    }
}
