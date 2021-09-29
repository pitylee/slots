<?php

namespace App\Console\Commands;

use App\Currencies\EUR;
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
    use MyOwnFun;
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
        $bet['amount'] = intval($this->ask('What is the amount?', 100));

        $this->print(PHP_EOL . '#1 Bet: ');
        $this->printJson($bet);

        //

        $winAmount = 0;
        $many = 0;
        while ($this->confirm('Spin?', true) !== false) {
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
        $this->print('That is ' . $winAmount / 100 . ' EUR.');

        //$this->doFunStuff($bet);

        $this->printMemory();
    }

    private function print(string $line): void
    {
        echo $line . PHP_EOL;
    }

    private function printJson(array $json): void
    {
        echo json_encode($json, JSON_PRETTY_PRINT) . PHP_EOL;
    }

    private function printSlot(array $slots): void
    {
        foreach ($slots as $row) {
            foreach ($row as $slot) {
                echo ($slot !== null ? $slot->symbol() : '?') . ' ';
            }
            echo PHP_EOL;
        }
    }

    private function printWin(bool $win): void
    {
        echo 'Win: ' .
            ($win === true ? 'yes' : 'no')
            . '.' . PHP_EOL;
    }

    private function printMemory(): void
    {
        $mem_usage = memory_get_usage();

        if ($mem_usage < 1024) {
            $memory = $mem_usage . 'bytes';
        } elseif ($mem_usage < 1048576) {
            $memory = round($mem_usage / 1024, 2) . 'KB';
        } else {
            $memory = round($mem_usage / 1048576, 2) . 'MB';
        }

        $this->print('The script is now using: ' . $memory . ' of memory.');
    }
}
