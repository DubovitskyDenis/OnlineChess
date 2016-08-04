<?php

namespace app\models;

use app\models\pieces\Piece;
use yii\base\Exception;
use yii\base\Model;

class Board extends Model
{
    const PIECES = [
        //white pieces
        'rw1' => ['default_x' => 1, 'default_y' => 1],
        'nw1' => ['default_x' => 2, 'default_y' => 1],
        'bw1' => ['default_x' => 3, 'default_y' => 1],
        'kw1' => ['default_x' => 4, 'default_y' => 1],
        'qw1' => ['default_x' => 5, 'default_y' => 1],
        'bw2' => ['default_x' => 6, 'default_y' => 1],
        'nw2' => ['default_x' => 7, 'default_y' => 1],
        'rw2' => ['default_x' => 8, 'default_y' => 1],

        'pw1' => ['default_x' => 1, 'default_y' => 2],
        'pw2' => ['default_x' => 2, 'default_y' => 2],
        'pw3' => ['default_x' => 3, 'default_y' => 2],
        'pw4' => ['default_x' => 4, 'default_y' => 2],
        'pw5' => ['default_x' => 5, 'default_y' => 2],
        'pw6' => ['default_x' => 6, 'default_y' => 2],
        'pw7' => ['default_x' => 7, 'default_y' => 2],
        'pw8' => ['default_x' => 8, 'default_y' => 2],

        //black pieces
        'rb1' => ['default_x' => 1, 'default_y' => 8],
        'nb1' => ['default_x' => 2, 'default_y' => 8],
        'bb1' => ['default_x' => 3, 'default_y' => 8],
        'kb1' => ['default_x' => 4, 'default_y' => 8],
        'qb1' => ['default_x' => 5, 'default_y' => 8],
        'bb2' => ['default_x' => 6, 'default_y' => 8],
        'nb2' => ['default_x' => 7, 'default_y' => 8],
        'rb2' => ['default_x' => 8, 'default_y' => 8],

        'pb1' => ['default_x' => 1, 'default_y' => 7],
        'pb2' => ['default_x' => 2, 'default_y' => 7],
        'pb3' => ['default_x' => 3, 'default_y' => 7],
        'pb4' => ['default_x' => 4, 'default_y' => 7],
        'pb5' => ['default_x' => 5, 'default_y' => 7],
        'pb6' => ['default_x' => 6, 'default_y' => 7],
        'pb7' => ['default_x' => 7, 'default_y' => 7],
        'pb8' => ['default_x' => 8, 'default_y' => 7],
    ];


    public $game_id;
    public $board;


    public function __construct(array $config)
    {
        parent::__construct($config);

        $game = Game::findOne($this->game_id);
        if ($game == null) {
            throw new Exception('Game isn\'t founded');
        }

        $this->board = [];
        for ($i = 1; $i <= 8; $i++) {
            $this->board[$i] = [];
        }
        $boardArray = self::getBoardArray($this->game_id);


        for ($i = 1; $i <= 8; $i++) {
            for ($j = 1; $j <= 8; $j++) {
                $this->board[$i][$j] = Piece::pieceFactory($boardArray[$i][$j]['piece_id'], $i, $j);
            }
        }
    }

    public static function getPiecesArray($game_id)
    {
        $game = Game::findOne($game_id);
        if (!$game) {
            return null;
        }

        $pieces = [];
        foreach (self::PIECES as $key => $item) {
            $lastMove = GameAction::find()
                ->where(['game_id' => $game_id, 'piece_id' => $key])
                ->orderBy('id DESC')
                ->limit(1)
                ->one();

            $captured = false;
            if ($lastMove && GameAction::find()
                    ->where(['game_id' => $game_id])
                    ->andWhere(['>', 'id', $lastMove->id])
                    ->andWhere(['to_x' => $lastMove->to_x])
                    ->andWhere(['to_y' => $lastMove->to_y])
                    ->one()
            ) {

                $captured = true;
            } else if (!$lastMove && GameAction::find()
                    ->where(['game_id' => $game_id])
                    ->andWhere(['to_x' => self::PIECES[$key]['default_x']])
                    ->andWhere(['to_y' => self::PIECES[$key]['default_y']])
                    ->one()
            ) {

                $captured = true;
            }

            if ($captured) {
            } else if (!$lastMove) {
                array_push($pieces, [
                    'id' => $key,
                    'x' => self::PIECES[$key]['default_x'],
                    'y' => self::PIECES[$key]['default_y'],
                ]);
            } else {
                array_push($pieces, [
                    'id' => $key,
                    'x' => $lastMove->to_x,
                    'y' => $lastMove->to_y,
                ]);
            }
        }

        return $pieces;
    }

    public static function getBoardArray($game_id)
    {
        $game = Game::findOne($game_id);
        if (!$game) {
            return null;
        }

        $board = [];

        for ($i = 1; $i <= 8; $i++) {
            $board[$i] = [];
            for ($j = 1; $j <= 8; $j++) {
                $board[$i][$j] = [
                    'piece_id' => '',
                    'isSelected' => false,
                ];
            }
        }

        foreach (self::getPiecesArray($game_id) as $piece) {
            $x = $piece['x'];
            $y = $piece['y'];
            $board[$x][$y]['piece_id'] = $piece['id'];
        }

        return $board;
    }
}