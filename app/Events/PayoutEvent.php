<?php

namespace App\Events;

class PayoutEvent extends Event
{
    public $amount;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array $bet, array $wins)
    {
        $this->amount = 0;

        foreach ($wins as $win) {
            if (is_numeric($win['percentage']) && $win['percentage'] > 0 &&
                is_numeric($bet['amount']) && $bet['amount'] > 0
            ) {
                $this->amount += ($bet['amount'] * $win['percentage']);
            }
        }
    }
}
