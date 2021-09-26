<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Classes\Currencies;

class Currency extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'symbol',
    ];

    public function __construct(object $currency)
    {
        $this->code = $currency->code();
        $this->name = $currency->name();
        $this->symbol = $currency->symbol();

        return $currency;
    }
}
