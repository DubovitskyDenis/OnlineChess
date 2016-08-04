<?php
/**
 * Created by PhpStorm.
 * User: kirill
 * Date: 8/2/16
 * Time: 1:37 PM
 */

namespace app\models\pieces;


use app\models\Board;

class King extends Piece
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

        for ($i = -1; $i <= 1; $i++) {
            for ($j = -1; $j <= 1; $j++) {

                if ($i == 0 && $j == 0)          { continue; }
                if (self::onBoard($x+$i, $y+$j)) { continue; }

                $piece = $board->board[$x+$i][$y+$j];
                if ($piece->isEmptyCell() || !self::areSameColor($this, $piece)) {
                    self::addMoveToArray($possibleMoves, $x+$i, $y+$j);
                }
            }
        }

        return $possibleMoves;
    }
}