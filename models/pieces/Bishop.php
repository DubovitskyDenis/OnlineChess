<?php
/**
 * Created by PhpStorm.
 * User: kirill
 * Date: 8/2/16
 * Time: 1:37 PM
 */

namespace app\models\pieces;


use app\models\Board;

class Bishop extends Piece
{
    public function __construct(array $config)
    {
        parent::__construct($config);
    }

    public function getPossibleMoves(Board $board)
    {
        $possibleMoves = $this->getPossibleDiagonalMoves($board);
        return $possibleMoves;
    }
}