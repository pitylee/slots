<?php

namespace App\Classes;

use Exception;

class Slots
{
    private $slots;
    private $slotSymbols;
    private $symbols;
    private $columns;
    private $rows;
    private $win;
    private $payouts;
    private $wins;

    public function __construct(int $columns = 5, int $rows = 3)
    {
        $this->setColumns($columns);
        $this->setRows($rows);

        $this->slotSymbols = config('app.symbols');
        if (empty($this->slotSymbols)) {
            throw new Exception('Slot symbols not set in configuration or misconfigured!');
        }

        $this->slotSymbols = array_combine(array_map(function ($slot) {
            if (empty($slot->code()) || empty($slot->name()) || empty($slot->symbol())) {
                throw new Exception('Slot symbol misconfigured!');
            }

            return $slot->code();
        }, $this->slotSymbols), array_values($this->slotSymbols));

        $this->symbols = array_map(function ($slot) {
            return $slot->symbol();
        }, $this->slotSymbols);

        $this->win = false;
        $this->payouts = [
            3 => 20 / 100,
            4 => 200 / 100,
            5 => 1000 / 100,
        ];
    }

    public function setColumns(int $value)
    {
        $this->columns = $value;
    }

    public function setRows(int $value)
    {
        $this->rows = $value;
    }

    public function randomize(int $length = 5): array
    {
        $slotSymbols = [];

        while (count($slotSymbols) < $length) {
            $slotSymbols = array_merge($slotSymbols, array_keys($this->slotSymbols));
        }

        $rand = array_values(array_intersect_key(
            $slotSymbols,
            array_flip(array_rand($slotSymbols, $length))
        ));
        shuffle($rand);

        $this->generate($rand);

        return $this->get();
    }

    public function generate(array $slots = []): array
    {
        $slot = 0;

        for ($row = 0; $row < $this->rows; $row++) {
            for ($column = 0; $column < $this->columns; $column++) {

                if (!isset($slots[$slot]) ||
                    (isset($slots[$slot]) && !isset($this->slotSymbols[$slots[$slot]]))
                ) {
                    throw new Exception('Slot #' . $row . '/' . $column . ' (' . (isset($slots[$slot]) ? $slots[$slot] : '?') . ') empty or symbol not existing!');
                }

                $this->slots[$row][$column] = $this->slotSymbols[$slots[$slot]];

                $slot++;
            }
        }

        return $this->get();
    }

    public function get(): array
    {
        return $this->slots;
    }

    public function isWin(): bool
    {
        foreach ($this->slots as $rowNr => $row) {
            $occurences = [];
            $carry = [];
            foreach ($row as $slot) {
                if ($carry && key($carry) === $slot->code()) {
                    ++$carry[$slot->code()];
                } else {
                    unset($carry);
                    $carry = [$slot->code() => 1];
                    $occurences[$slot->code()] =& $carry[$slot->code()];
                }
            }
            unset($carry);

            foreach ($this->payouts as $payout => $percentage) {
                foreach ($occurences as $symbol => $occurence) {
                    if ($occurence === $payout) {
                        $this->win = true;
                        $win = [
                            'occurence' => $occurence,
                            'payout' => $payout,
                            'percentage' => $percentage,
                            'row' => array_map(function ($slot) {
                                return $slot->code();
                            }, $row),
                        ];
                        $this->wins[] = $win;
                    }
                }
            }
        }

        return $this->win;
    }

    public function wins(): array
    {
        return $this->wins;
    }
}
