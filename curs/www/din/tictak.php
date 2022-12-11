<?php
// Подключаем объявление класса игры.
require_once(dirname(__FILE__) . '/classes.php');

session_start();

// Получаем из сессии текущую игру.
// Если игры еще нет, создаём новую.
$game = isset($_SESSION['game'])? $_SESSION['game']: null;
if(!$game || !is_object($game)) {
    $game = new TicTacGame();
}

// Обрабатываем запрос пользователя, выполняя нужное действие.
$params = $_GET + $_POST;
if(isset($params['action'])) {
    $action = $params['action'];
    
    if($action == 'move') {
        // Обрабатываем ход пользователя.
        $game->makeMove((int)$params['x'], (int)$params['y']);
        
    } else if($action == 'newGame') {
        // Пользователь решил начать новую игру.
        $game = new TicTacGame();
    }
}
// Добавляем вновь созданную игру в сессию.
$_SESSION['game'] = $game;


// Отображаем текущее состояние игры в виде HTML страницы.
$width = $game->getFieldWidth();
$height = $game->getFieldHeight();
$field = $game->getField();
$winnerCells = $game->getWinnerCells();

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN"
  "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" version="XHTML+RDFa 1.0" dir="ltr">
<head profile="http://www.w3.org/1999/xhtml/vocab">
<title>Игра</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" href="master.css" type="text/css"/>
</head>

<body>
    
<!-- Отображаем состояние игры и игровое поле. -->

<!-- CSS-стили, задающие внешний вид элементов HTML. -->
<style type="text/css">
    .tictaccss{justify-content: center; display: flex; width: 25%; height: 90%; background: #CCE916; border-radius: 20px; box-shadow: 0px 10px 20px #1687d933; margin-top: 3%; margin-left: auto; margin-right: auto; box-shadow: 0 5px 10px 0 #000000; }
    .ticTacField {overflow:hidden;margin-top: 3%;margin-bottom: 3%;}
    .ticTacRow {clear:both;}
    .ticTacCell {float: left; border: 1px solid #4126C8; width: 100px; height:100px; position:relative; text-align:center;}
    .ticTacCell a {position:absolute; left:0;top:0;right:0;bottom:0}
    .ticTacCell a:hover { background: #aaa; }
    .ticTacCell.winner { background:#f00;}
    .top{font-size: 1.5em; margin-top: 0.83em; margin-bottom: 0.83em; font-weight: bold; display: flex; width: 15%; height: 90%; background: #CCE916; border-radius: 20px; box-shadow: 0px 10px 20px #1687d933; margin-top: 1%; margin-left: auto; margin-right: auto; flex-direction: column; box-shadow: 0 5px 10px 0 #000000;}
    .icon {display: inline-block; }
    .start{font-size: 1.5em; margin-top: 0.83em; margin-bottom: 0.83em; font-weight: bold; display: flex; width: 15%; background: #CCE916; border-radius: 20px; box-shadow: 0px 10px 20px #1687d933; margin-top: 5%; margin-left: auto; margin-right: auto; flex-direction: column; box-shadow: 0 5px 10px 0 #000000; }
    .player1:after { content: 'X'; font-size: 80px;  }
    .player2:after { content: 'O'; font-size: 80px;  }
    .number1:after { content: 'X'; display: block; font-size: 1.5em; margin-top: 0.83em; margin-bottom: 0.83em; font-weight: bold;  }
    .number2:after { content: 'O'; display: block; font-size: 1.5em; margin-top: 0.83em; margin-bottom: 0.83em; font-weight: bold;  }
</style>
    
<header>
            <span><h2><a></a></h2></span>
            <nav>
                <a href="tictak.php" class="button" onclick="click1()">Игра</a>
                <a href="index.php" class="button" onclick="click3()">Таблица побед</a>
            </nav>
        </header>  

<?php if($game->getCurrentPlayer()) { ?>
    <!-- Отображаем приглашение сделать ход. -->
    <div class="top">
    <div align="center" class="icon number<?php echo $game->getCurrentPlayer() ?>"> <a> Ход делает игрок </a><br></div>
    </div>
<?php } ?>

<?php if($game->getWinner()) 

{ ?>
    <!-- Отображаем сообщение о победителе -->
    <div class="top">
        <div align="center"; class="icon number<?php echo $game->getWinner() ?>"> <h2> Победил игрок </h2></div>
    </div>
<?php
    $wins=$game->getWinner();
    $mysqli = new mysqli("data", "user", "password", "appDB");
    $datam=date('Y-m-d H:i:s');
    if ($wins == 1) {
        $wins = 'X';
    }
    else {
        $wins= 'O';
    }
    echo $winner;
    $sql =$mysqli->query( "INSERT INTO users (dat, player) VALUES ('$datam', '$wins')"); 
    $mysqli->close(); } 
    ?>

<!-- Рисуем игровое поле, отображая сделанные ходы
<divи подсвечивая победившую комбинацию. --> 
<div class="tictaccss">
    <div class="ticTacField">
        <?php for($y=0; $y < $height; $y++) { ?>
            <div class="ticTacRow">
                <?php for($x=0; $x < $width; $x++) {
                // $player - игрок, сходивший в эту клетку :), или null, если клетка свободна.
                // $winner - флаг, означающий, что эта клетка должна быть подсвечена при победе.
                    $player = isset($field[$x][$y])? $field[$x][$y]: null;
                    $winner = isset($winnerCells[$x][$y]);
                    $class = ($player? ' player' . $player: '') . ($winner? ' winner': '');
                    ?>
                    <div class="ticTacCell<?php echo $class ?>">
                        <?php if(!$player) { ?>
                        <!-- Клетка свободна. Отображаем здесь ссылку,
                        на которую нужно кликнуть для совершения хода. -->
                            <a href="?action=move&amp;x=<?php echo $x ?>&amp;y=<?php echo $y ?>"></a>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
    </div>
    <div align="center" class="start"> <a href="?action=newGame">Начать новую игру</a><br> </div>
</body>
<footer>2022, copyleft, akd</footer>
</html>