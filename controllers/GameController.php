<?php

namespace app\controllers;

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

    public function actionPlay($game_id)
    {
        $game = Game::findOne($game_id);

        if (!$game) {
            return self::notFountIdError($game_id);
        }

        return $this->render('play', [
            'game' => $game,
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
}