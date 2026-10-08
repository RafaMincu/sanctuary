<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_number',
        'wing',
        'service_type',
        'client_name',
        'client_email',
        'client_phone',
        'scheduled_at',
        'notes',
        'metadata',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    /**
     * Generate unique booking code like SNC-A9B4C2
     */
    public static function generateBookingNumber(): string
    {
        do {
            $code = 'SNC-' . strtoupper(Str::random(6));
        } while (static::where('booking_number', $code)->exists());

        return $code;
    }

    public function scopeForWing($query, ?string $wing)
    {
        if ($wing) {
            return $query->where('wing', $wing);
        }
        return $query;
    }

    public function scopeForStatus($query, ?string $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    public function getWingLabelAttribute(): string
    {
        return match ($this->wing) {
            'auto' => 'Garaj Auto (Aripa Stângă)',
            'food' => 'Zona Food (Zona Centrală)',
            'fitness' => 'Sală Fitness (Aripa Dreaptă)',
            default => ucfirst($this->wing),
        };
    }

    public function getWingColorAttribute(): string
    {
        return match ($this->wing) {
            'auto' => 'cyan',
            'food' => 'emerald',
            'fitness' => 'blue',
            default => 'zinc',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'În Așteptare',
            'confirmed' => 'Confirmată',
            'completed' => 'Finalizată',
            'cancelled' => 'Anulată',
            default => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'yellow',
            'confirmed' => 'emerald',
            'completed' => 'cyan',
            'cancelled' => 'rose',
            default => 'zinc',
        };
    }
}
