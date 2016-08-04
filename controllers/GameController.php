<?php

namespace app\controllers;

use app\models\Board;
use app\models\GameAction;
use Yii;
use yii\web\Controller;
use app\models\Game;

class GameController extends Controller
{
    //TODO: CSRF Validation
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionCreate()
    {
        if (Yii::$app->user->isGuest) {
            $this->redirect('/user/login');
            return;
        }

        $user_id = Yii::$app->user->id;
        $game = Game::findAwaitingGame($user_id);
        if (!$game) {
            $game = Game::create($user_id);
        } else {
            $game->join($user_id);
        }

        $this->redirect("/game/$game->id");
    }

    private function notFountIdError($game_id)
    {
        return $this->render('/site/error', [
            'name' => "Not Found",
            'message' => "There is no game with ID=$game_id",
        ]);
    }

    public function actionPlay($game_id, $possibleMoves = [])
    {
        $game = Game::findOne($game_id);

        if (!$game) {
            return self::notFountIdError($game_id);
        }

        return $this->render('play', [
            'game' => $game,
            'possibleMoves' => $possibleMoves,
        ]);
    }

    public function actionPossibleMoves($game_id, $from_x, $from_y)
    {
        $board = new Board(['game_id' => $game_id]);
        if ($board) {
            $possibleMoves = $board->board[$from_x][$from_y]->getPossibleMoves($board);
            $possibleMoves['from_x'] = $from_x;
            $possibleMoves['from_y'] = $from_y;
        }

        $this->runAction('game/play', [
            'game_id' => $game_id,
            'possibleMoves' => $possibleMoves,
        ]);
    }

    public function actionCancel($game_id)
    {
        //$game_id = Yii::$app->request->post()->game_id;
        $game = Game::findOne($game_id);

        if (!$game) {
            return self::notFountIdError($game_id);
        }

        $user_id = Yii::$app->user->id;
        if (($game->user_id_black === $user_id && $game->user_id_white === 0) ||
            ($game->user_id_white === $user_id && $game->user_id_black === 0)
        ) {

            if ($game->delete()) {
                return $this->render('cancelSuccess');
            } else {
                return $this->render('/site/error', [
                    'name' => "Delete error",
                    'message' => "You have a permission, but something goes wrong",
                ]);
            }
        }

        return $this->render('/site/error', [
            'name' => "Delete error",
            'message' => "You don't have a permission or game already began",
        ]);
    }

    public function actionSurrender($game_id)
    {
        $game = Game::findOne($game_id);

        if (!$game) {
            return self::notFountIdError($game_id);
        }

        $user_id = Yii::$app->user->id;
        if ($game->alreadyBegan()) {
            if ($game->surrender($user_id)) {
                return $this->render('surrenderSuccess');
            } else {
                return $this->render('/site/error', [
                    'name' => "Surrender error",
                    'message' => "You don't have a permission or something goes wrong",
                ]);
            }
        }

        return $this->render('/site/error', [
            'name' => "Surrender error",
            'message' => "Game hasn't began",
        ]);
    }

    public function actionMove($game_id, $from_x, $from_y, $to_x, $to_y)
    {
        $game = Game::findOne($game_id);

        if (!$game) {
            return self::notFountIdError($game_id);
        }

        $user_id = Yii::$app->user->id;
        $board = new Board(['game_id' => $game_id]);
        $piece = $board->board[$from_x][$from_y];

        //TODO: delete 'true ||' after testing
        if (true || $piece->isMovePossible($board, $to_x, $to_y)) {
            $move = new GameAction([
                'game_id' => $game_id,
                'piece_id' => $board->board[$from_x][$from_y]->piece_id,
                'to_x' => $to_x,
                'to_y' => $to_y,
                'effect' => $board->board[$to_x][$to_y]->isEmptyCell() ? GameAction::REGULAR_MOVE : GameAction::CAPTURE,
            ]);
            $move->save();
        }

        $this->redirect("/game/$game->id");
    }
}