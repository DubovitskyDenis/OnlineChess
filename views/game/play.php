<?php
/** @var $this yii\web\View */
/** @var \app\models\Game $game */
use yii\helpers\Html;
use app\models\Board;
use app\models\user\User;
use app\models\GameAction;
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
<?php
function getImagePiece($board, $i, $j)
{
    $figure = $board[$j][$i]['piece_id'];
    if ($figure == '') {
        return '';
    }
    $figure = $figure[0] . $figure[1];
    switch($figure) {
        case 'bb':
            return '<a href ="/possible_moves/x:y"><img src="/Figures_Icons/bb.svg"></a>';
        case 'bw':
            return '<a href ="/possible_moves/x:y"><img src="/Figures_Icons/bw.svg"></a>';
        case 'kb':
            return '<a href ="/possible_moves/x:y"><img src="/Figures_Icons/kb.svg"></a>';
        case 'kw':
            return '<a href ="/possible_moves/x:y"><img src="/Figures_Icons/kw.svg"></a>';
        case 'nb':
            return '<a href ="/possible_moves/x:y"><img src="/Figures_Icons/nb.svg"></a>';
        case 'nw':
            return '<a href ="/possible_moves/x:y"><img src="/Figures_Icons/nw.svg"></a>';
        case 'pb':
            return '<a href ="/possible_moves/x:y"><img src="/Figures_Icons/pb.svg"></a>';
        case 'pw':
            return '<a href ="/possible_moves/x:y"><img src="/Figures_Icons/pw.svg"></a>';
        case 'qb':
            return '<a href ="/possible_moves/x:y"><img src="/Figures_Icons/qb.svg"></a>';
        case 'qw':
            return '<a href ="/possible_moves/x:y"><img src="/Figures_Icons/qw.svg"></a>';
        case 'rb':
            return '<a href ="/possible_moves/x:y"><img src="/Figures_Icons/rb.svg"></a>';
        case 'rw':
            return '<a href ="/possible_moves/x:y"><img src="/Figures_Icons/rw.svg"></a>';
    }
}
?>
<?php $board = Board::getBoardArray($game->id)?>
<?php /*$gameAction = GameAction:: findOne($game->id) */?>

<div class="wrapper">
<div class="leftblock">
    <table border="1px" >
        <?php for ($i = 0; $i < 8; $i++) : ?>
            <tr>
                <?php for ($j = 0; $j < 8; $j++) : ?>
                    <?php $id = $i*10 + $j; ?>
                    <?php if (($i + $j) % 2) : ?>
                        <td class="black">
                            <?php
                                echo getImagePiece($board, 8 - $i, $j + 1);
                            ?>
                        </td>
                    <?php else : ?>
                        <td class="white">
                            <?php
                                echo getImagePiece($board, 8 - $i, $j + 1);
                            ?>
                        </td>
                    <?php endif ?>
                <?php endfor ?>
            </tr>
        <?php endfor ?>
    </table>
</div>
    
    
    
    
    
