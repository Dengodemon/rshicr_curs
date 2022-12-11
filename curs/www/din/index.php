<html lang="en">
<head>
<title>Таблица побед</title>
    <link rel="stylesheet" href="master.css" type="text/css"/>
    <link rel="stylesheet" href="style.css" type="text/css"/>
</head>
<body>
    <header>
            <span><h2><a></a></h2></span>
            <nav>
                <a href="tictak.php" class="button" onclick="click1()">Игра</a>
                <a href="index.php" class="button" onclick="click3()">Таблица побед</a>
            </nav>
        </header> 
<h1>Таблица побед</h1>
<table>
    <tr><th>Id</th><th>Дата и время</th><th>Победитель</th></tr>
<?php
$mysqli = new mysqli("data", "user", "password", "appDB");
$result = $mysqli->query("SELECT * FROM users");
foreach ($result as $row){
    echo "<tr><td>{$row['ID']}</td><td>{$row['dat']}</td><td>{$row['player']}</td></tr>";
}
?>
</table>
</body>
<footer>2022, copyleft, akd</footer>
</html>