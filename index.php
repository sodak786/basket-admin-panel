<?php
session_start();

$isLoggedin=false;

if(isset($_POST["uname"], $_POST["password"])){
    if($_POST["uname"]=="admin" && $_POST["password"]=="admin"){
        $_SESSION["loggedIn"]=true;
    }else{
        $_SESSION["loggedIn"]=false;
    }
}

if(isset($_POST["logout"])){
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;

}

if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"]==true){
    $isLoggedin=true;
}
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Document</title>
        <link rel="stylesheet" href="index.scss">
        <script src="script.js"></script>
    </head>
    <body id="MAIN">
        <header>
            <div class="index-button">micek</div>
            <div class="header-pages">
                <a href="/vysledky.php">Výsledky</a>
                <a href="/kalendar.php">Kalendář</a>
                <a href="/statistika.php">Statistika</a>
            </div>
        </header>
        <main>
            <h1 class="header-text">Basketball Admin Panel</h1>
            <?php if(!$isLoggedin): ?>
            <button id="login-button" onclick="login();">Přihlášení</button>
            <div class="login-panel" id="login-panel">
                <form action="" method="POST">
                    <p>Přihlášení</p>
                    <input type="text" name="uname" placeholder="jméno">
                    <input type="password" name="password" placeholder="heslo">
                    <button type="submit">Přihlásit se</button>
                </form>
            </div>
            <?php else: ?>
            <p>Admin byl přihlášen</p>
                <form action="" method="POST">
                    <button type="submit" name="logout">Odhlásit</button>
                </form>
            <?php endif; ?>
        </main>
        <footer></footer>
    </body>
</html>

