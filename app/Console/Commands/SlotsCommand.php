<?php

namespace App\Console\Commands;

use App\Models\Bet;
use Exception;
use Illuminate\Console\Command;
use App\Classes\Slots;
use App\Events\PayoutEvent;

/**
 * Class SlotsCommand
 *
 * @category Console_Command
 * @package  App\Console\Commands
 */
class SlotsCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "slots:generate";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Generate random slots.";


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $bet = Bet::factory()->definition();
        $bet['amount'] = 100;
        $this->print('#1 Bet: ' . json_encode($bet, true));

        $slotsRandom = (new Slots(5, 3))->randomize(15);
        $this->print('#2 Slots of 5 columns and 3 rows randomized:');
        $this->printSlot($slotsRandom);

        $slotsPredefined = (new Slots(5, 3))->generate(['J', 'J', 'J', 'Q', 'K', 'cat', 'J', 'Q', 'monkey', 'bird', 'bird', 'bird', 'J', 'Q', 'A']);
        $this->print('#3 Slots for 15 predefined values:');
        $this->printSlot($slotsPredefined);

        $slots = new Slots(5, 5);
        $board = [
            'J', 'J', 'J', 'Q', 'K',
            'cat', 'J', 'Q', 'monkey', 'bird',
            'bird', 'bird', 'J', 'Q', 'A',
            //from here below differs from 3
            'J', 'J', 'J', 'monkey', 'K',
            'bird', 'J', 'J', 'monkey', 'A',
        ];
        $slotsForPayout = $slots->generate($board);
        $this->print('#4 Slots for payout double win:');
        $this->printSlot($slotsForPayout);

        $this->print('#5 Payout the amount won:');
        if ($win = $slots->isWin() && $wins = $slots->wins()) {
            $this->printWin(true);
            $winAmount = (new PayoutEvent($bet, $wins))->amount;
            $this->print($bet['client'] . ' got a payout of ' . $winAmount . ' ' . $bet['currency']);
        }

        $this->print('#6 Json version:');
        $this->print(json_encode([
            'board' => $board,
            'paylines' => array_map(function($win) {
                return [
                    implode(' ', $win['row']) => $win['occurence'],
                ];
            }, $wins),
            'bet_amount' => $bet['amount'],
            'total_win' => $winAmount,
        ], JSON_PRETTY_PRINT));

    }

    private function print (string $line): void
    {
        echo $line . PHP_EOL;
    }

    private function printSlot (array $slots): void
    {
        foreach ($slots as $row) {
            foreach ($row as $slot) {
                echo ($slot !== null ? $slot->symbol() : '?') . ' ';
            }
            echo PHP_EOL;
        }
    }

    private function printWin (bool $win): void
    {
        echo 'Win: ' .
            ($win === true ? 'yes' : 'no')
            . '.' . PHP_EOL;
    }
}
