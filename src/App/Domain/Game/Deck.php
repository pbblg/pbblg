<?php

namespace App\Domain\Game;

class Deck
{
    /**
     * 5 - еденичек
     * 2 - двойки
     * 2 - тройки
     * 2 - четверки
     * 2 - пятерки
     * 6, 7, 8
     * @var array
     */
    private static $nominal = [1, 1, 1, 1, 1, 2, 2, 3, 3, 4, 4, 5, 5, 6, 7, 8];

    /**
     * @var array
     */
    private $deck;

    private function __construct(array $deck)
    {
        $this->deck = $deck;
    }

    /**
     * @return Deck
     */
    public static function generate()
    {
        $deck = self::$nominal;
        shuffle($deck);
        return new self($deck);
    }

    public function shift()
    {
        return array_shift($this->deck);
    }
}
