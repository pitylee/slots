<?php

use App\Currencies\EUR;
use App\Currencies\USD;
use App\Models\Currency;
use App\SlotSymbols\Nine;
use App\SlotSymbols\Ten;
use App\SlotSymbols\Jack;
use App\SlotSymbols\Queen;
use App\SlotSymbols\King;
use App\SlotSymbols\Ace;
use App\SlotSymbols\Cat;
use App\SlotSymbols\Dog;
use App\SlotSymbols\Monkey;
use App\SlotSymbols\Bird;

return [
    'currencies' => [
        (new Currency(new USD())),
        (new Currency(new EUR())),
    ],
    'symbols' => [
        (new Nine()),
        (new Ten()),
        (new Jack()),
        (new Queen()),
        (new King()),
        (new Ace()),
        (new Cat()),
        (new Dog()),
        (new Monkey()),
        (new Bird()),
    ],
];
