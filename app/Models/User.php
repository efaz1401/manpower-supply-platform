<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'name_ar',
        'email',
        'phone',
        'locale',
        'is_active',
        'client_id',
        'worker_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /** Client company this portal user represents (client role). */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /** Worker this portal user represents (worker role). */
    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class);
    }

    /** Localised display name based on the active locale. */
    public function displayName(): string
    {
        return $this->name();
    }

    /** Localised display name based on the active locale. */
    public function name(): string
    {
        return app()->getLocale() === 'ar' && $this->name_ar ? $this->name_ar : $this->name;
    }

    /** Landing route name for this user's primary role. */
    public function homeRoute(): string
    {
        return match (true) {
            $this->hasRole('client') => 'client.dashboard',
            $this->hasRole('worker') => 'worker.dashboard',
            $this->hasAnyRole(['admin', 'manager']) => 'executive.dashboard',
            default => 'dashboard',
        };
    }
}
