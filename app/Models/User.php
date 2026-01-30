<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'password',
        'role',
        'is_active'
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
        ];
    }

    public function isDeliveryBoy(): bool {
        return $this->role === 'delivery_boy';
    }

    public function isActiveDeliveryBoy(): bool {
        return $this->isDeliveryBoy() && $this->is_active;
    }

    /* Scope: only active delivery boys */
    public function scopeActiveDeliveryBoys($query)
    {
        return $query->where('role', 'delivery_boy')
            ->where('is_active', true);
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class, 'deliveryboy_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'deliveryboy_id');
    }
}
