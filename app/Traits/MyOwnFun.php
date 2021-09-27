<?php
namespace App\Traits;
use App\Classes\Slots;
use App\Events\PayoutEvent;

trait MyOwnFun {
    public function doFunStuff (array $bet) {
        $this->print('---- This is for my own fun and probability calculation ----');

        $winAmount = 0;
        $many = 1000;

        for ($i = 1; $i < $many; $i++) {
            $rand = rand(3, 15);
            $slot = new Slots(5, $rand);
            $slotsRandom = $slot->randomize(5 * $rand);
            $this->printSlot($slotsRandom);
            if ($win = $slot->isWin() && $wins = $slot->wins()) {
                $winAmount += (new PayoutEvent($bet, $wins))->amount;
            }
            $this->print('#endRandom');
        }
        $this->print('With ' . $many . ' spins a total amount of ' . $winAmount . ' ' . $bet['currency'] . ' can be won.');
        $this->print('That is ' . $winAmount / 100 . ' EUR.');

        $this->print('---- This is for my own fun and probability calculation ----');
    }
}
