<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bet extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $amount;
    protected $currency;
    protected $client;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount',
        'currency',
        'client',
    ];

    public function getAmountAttribute(): float
    {
        return floatval($this->amount);
    }

    public function setAmountAttribute(float $value): void
    {
        $this->amount = $value;
    }

    public function getCurrencyAttribute(): string
    {
        return $this->currency;
    }

    public function setCurrencyAttribute($value): void
    {
        $this->currency = $value;
    }

    public function getClientAttribute(): string
    {
        return $this->client;
    }

    public function setClientAttribute(string $value): void
    {
        $this->client = $value;
    }

    public function create(): array
    {
        return [
            'amount' => $this->amount,
            'currency' => $this->currency,
            'client' => $this->client,
        ];
    }
}
