<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Client extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'name_en', 'name_ar', 'cr_number', 'establishment_id', 'vat_number',
        'contact_person', 'contact_email', 'contact_phone', 'city', 'address',
        'payment_terms_days', 'status', 'notes',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty()->dontLogEmptyChanges();
    }

    public function name(): string
    {
        return app()->getLocale() === 'ar' ? $this->name_ar : $this->name_en;
    }

    public function supplyContracts(): HasMany
    {
        return $this->hasMany(SupplyContract::class);
    }

    public function rateCards(): HasMany
    {
        return $this->hasMany(RateCard::class);
    }

    public function deployments(): HasMany
    {
        return $this->hasMany(Deployment::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function ajeerPermits(): HasMany
    {
        return $this->hasMany(AjeerPermit::class);
    }
}
