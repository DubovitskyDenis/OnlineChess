<?php
/** @var $this yii\web\View */
/** @var \app\models\Game $game */
use yii\helpers\Html;
use app\models\Board;
$this->registerCssFile('/css/board.css',['position'=>yii\web\View::POS_HEAD]);
/*$this->registerCssFile('css/board.css');*/ 
?>

<h1>
    Game <?= $game->id ?>
    <?php if ($game->is_finished) : ?>
        finished. Winner: <?= $game->winner_id ?>
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
<?php $board = Board::getBoardArray($game->id)?>
<div class="boardpart">
    <table border="1px" >
        <?php for ($i = 0; $i < 8; $i++) : ?>
            <tr>
                <?php for ($j = 0; $j < 8; $j++) : ?>
                    <?php $id = $i*10 + $j; ?>
                    <?php /*$name = $board; */?>
                    <?php if (($i + $j) % 2) : ?>
                        <?php  ?>
                        <td class="black">
                            <?php
                            echo Board::getImagePiece($board, 8 - $i, $j + 1);
                            ?>
                        </td>
                    <?php else : ?>
                        <td class="white">
                            <?php
                               echo Board::getImagePiece($board, 8 - $i, $j + 1);
                            ?>
                        </td>
                    <?php endif ?>
                <?php endfor ?>
            </tr>
        <?php endfor ?>
    </table>
</div>
<div class="historypart">
    