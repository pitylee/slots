<?php

namespace App\Console\Commands;

use App\Currencies\EUR;
use App\Models\Bet;
use Exception;
use Illuminate\Console\Command;
use App\Classes\Slots;
use App\Events\PayoutEvent;
use App\Traits\MyOwnFun;

/**
 * Class SlotsCommand
 *
 * @category Console_Command
 * @package  App\Console\Commands
 */
class SlotsInteractiveCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "slots:interactive";

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
        $bet = [
            'amount' => 100,
            'currency' => (new EUR())->symbol(),
            'client' => 'whoami',
        ];
        $bet['amount'] = $this->ask('What is the amount?', 100);

        $this->print('Bet: ' . json_encode($bet, true));

        //

        $winAmount = 0;
        $many = 0;
        while ($this->confirm('Spin?', true) !== false)
        {
            $slot = new Slots(5, 5);
            $slotsRandom = $slot->randomize(5 * 5);
            $this->printSlot($slotsRandom);
            if ($win = $slot->isWin() && $wins = $slot->wins()) {
                $this->printWin(true);
                $winAmount += (new PayoutEvent($bet, $wins))->amount;
                $this->print($bet['client'] . ' got a payout of ' . $winAmount . ' ' . $bet['currency']);
            }
            $many++;
        }

        $this->print('With ' . $many . ' spins a total amount of ' . $winAmount . ' ' . $bet['currency'] . ' can be won.');
        $this->print('That is ' . $winAmount/100 . ' EUR.');

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
