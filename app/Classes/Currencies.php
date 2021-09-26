<?php
namespace App\Classes;

abstract class Currencies
{
    protected $code;
    protected $name;
    protected $symbol;

    public function code(): string
    {
        return $this->code;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function symbol(): string
    {
        return $this->symbol;
    }
}
