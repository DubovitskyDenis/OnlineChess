<?php
/**
 * Created by PhpStorm.
 * User: kirill
 * Date: 8/2/16
 * Time: 1:37 PM
 */

namespace app\models\pieces;


use app\models\Board;

class Knight extends Piece
{
    public function __construct(array $config)
    {
        parent::__construct($config);
    }

    public function getPossibleMoves(Board $board)
    {
        $possibleMoves = [];
        $x = $this->x;
        $y = $this->y;

        for ($i = -1; $i <= 1; $i += 2) {
            for ($j = -1; $j <= 1; $j += 2) {

                $to_x = $x + 2*$i;
                $to_y = $y + $j;
                if (!self::onBoard($to_x, $to_y)) { continue; }

                $piece = $board->board[$to_x][$to_y];
                if ($piece->isEmptyCell() || !self::areSameColor($this, $piece)) {
                    self::addMoveToArray($possibleMoves, $to_x, $to_y);
                }

                $to_x = $x + $i;
                $to_y = $y + 2*$j;
                if (!self::onBoard($to_x, $to_y)) { continue; }

                $piece = $board->board[$to_x][$to_y];
                if ($piece->isEmptyCell() || !self::areSameColor($this, $piece)) {
                    self::addMoveToArray($possibleMoves, $to_x, $to_y);
                }
            }
        }

        return $possibleMoves;
    }
}