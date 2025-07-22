<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Document</title>
        <link rel="stylesheet" href="index.scss">
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
            <button onclick="">Přihlášení</button>
            <div class="login-panel">
                <form action="index.php" method="POST">
                    <p>Přihlášení</p>
                    <input type="text" name="uname" placeholder="jméno">
                    <input type="password" name="password" placeholder="heslo">
                    <button type="submit">Přihlásit se</button>
                </form>
            </div>
        </main>
        <footer></footer>
    </body>
</html>

<?php
if ($_POST['uname'] === "admin" && $_POST['password'] === "heslo"){
    echo "admin byl příhlášen";
}
