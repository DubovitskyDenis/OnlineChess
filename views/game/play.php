<?php
/** @var $this yii\web\View */
/** @var \app\models\Game $game */
use yii\helpers\Html;
use app\models\Board;
use app\models\user\User;
use app\models\GameAction;
$this->registerCssFile('/css/board.css',['position'=>yii\web\View::POS_HEAD]);
/*$this->registerCssFile('css/board.css');*/
$user = User::findOne($game->winner_id)
?>
<h1>
    Game <?= $game->id ?>
    <?php if ($game->is_finished) : ?>
        finished. Winner: <?= ($user) ? $user->email : $game->winner_id ?>
    <?php elseif (!$game->alreadyBegan()) : ?>
        <?= Html::beginForm(["/game/cancel/$game->id"], 'post', ['class' => '']) ?>
        <?= Html::submitButton('Cancel', ['class' => 'btn btn-primary']) ?>
        <?= Html::endForm() ?>
    <?php else : ?>
        <?= Html::beginForm(["/game/surrender/$game->id"], 'post', ['class' => '']) ?>
        <?= Html::submitButton('Surrender', ['class' => 'btn btn-danger']) ?>
        <?= Html::endForm() ?>
    <?php endif ?>
</h1>
<!--<p>
<pre><?/*= print_r(Board::getBoardArray($game->id)) */?></pre>
</p>-->
<?php
function getImagePiece($board, $i, $j)
{
    $figure = $board[$j][$i]['piece_id'];
    if ($figure == '') {
        return '';
    }
    $figure = $figure[0] . $figure[1];
    return "/Figures_Icons/$figure.svg";
}
?>
<?php $board = Board::getBoardArray($game->id)?>
<div class="leftblock">
    <table border="1px" >
        <?php for ($i = 0; $i < 8; $i++) : ?>
            <tr>
                <?php for ($j = 0; $j < 8; $j++) : ?>
                    <?php if (in_array(['x' => 1 +  $j, 'y' => 8 - $i], $possibleMoves)) : ?>
                            <?php $a = 8 - $i; $b = 1 + $j ?>
                            <?php $x = $possibleMoves['from_x']; $y = $possibleMoves['from_y']?>
                                <td class="possible-moves">
                                    <a href = "<?="/game/$game->id/move/$x:$y:$b:$a"?>">
                                        <img src=" <?= getImagePiece($board, 8 - $i, 1 + $j); ?>">
                                    </a>
                                </td>
                    <?php else :?>
                        <td class="<?= (($i + $j) % 2) ? 'black' : 'white' ?>">
                            <?php $a = 8 - $i; $b = 1 + $j ?>
                            <a href = <?="/game/$game->id/possible_moves/$b:$a"?>>
                                <img src=" <?= getImagePiece($board, 8 - $i, 1 + $j); ?>">
                            </a>
                        </td>
                    <?php endif; ?>
                <?php endfor ?>
            </tr>
        <?php endfor ?>
    </table>
</div>