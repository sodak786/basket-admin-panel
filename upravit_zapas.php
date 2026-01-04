<?php
global $conn;
include "db.php";
session_start();

$id = (int)$_GET["id"];

$game = $conn->query("
    SELECT g.*, 
           t1.name AS home_team_name,
           t2.name AS away_team_name
    FROM games g
    JOIN teams t1 ON g.home_team_id = t1.id
    JOIN teams t2 ON g.away_team_id = t2.id
    WHERE g.id = $id
")->fetch_assoc();
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Document</title>
    <link rel="stylesheet" href="index.scss">
    <script src="script.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&display=swap" rel="stylesheet">
</head>

<body id="UPRAVIT_ZAPAS">
    <header></header>
    <main>
        <h1>Upravit zápas</h1>

        <form action="ulozit_upravu.php" method="POST" id="edit-game-form">
            <input type="hidden" name="id" value="<?= $id ?>">
            <h2>
                <?= htmlspecialchars($game['game_date']) ?> |
                <?= htmlspecialchars($game['home_team_name']) ?> vs
                <?= htmlspecialchars($game['away_team_name']) ?>
            </h2>
            <div>
                <label>Domácí (celkem):</label>
                <input type="number" name="home_score" id="home_score">
            </div>
            <div>
                <label>Čtvrtiny: </label>
                <input type="number"
                       name="h1"
                       class="quarter-input"
                       data-side="home"
                       data-quarter="1">

                <input type="number"
                       name="h2"
                       class="quarter-input"
                       data-side="home"
                       data-quarter="2">

                <input type="number"
                       name="h3"
                       class="quarter-input"
                       data-side="home"
                       data-quarter="3">

                <input type="number"
                       name="h4"
                       class="quarter-input"
                       data-side="home"
                       data-quarter="4">
            </div>
            <div>
                <label>Hostující (celkem):</label>
                <input type="number" name="away_score" id="away_score">
            </div>
            <div>
                <label>Čtvrtiny: </label>
                <input type="number"
                       name="a1"
                       class="quarter-input"
                       data-side="away"
                       data-quarter="1">

                <input type="number"
                       name="a2"
                       class="quarter-input"
                       data-side="away"
                       data-quarter="2">

                <input type="number"
                       name="a3"
                       class="quarter-input"
                       data-side="away"
                       data-quarter="3">

                <input type="number"
                       name="a4"
                       class="quarter-input"
                       data-side="away"
                       data-quarter="4">
            </div>
            <button type="submit">Uložit výsledek</button>
        </form>
    </main>

    <script>
        const gameId = <?= (int)$id ?>;

        const homeScoreInput = document.getElementById('home_score');
        const awayScoreInput = document.getElementById('away_score');
        const form = document.getElementById('edit-game-form');

        function getQuarterTotals() {
            let homeTotal = 0;
            let awayTotal = 0;

            document.querySelectorAll('.quarter-input[data-side="home"]').forEach(input => {
                homeTotal += Number(input.value) || 0
                ;
            });

            document.querySelectorAll('.quarter-input[data-side="away"]').forEach(input => {
                awayTotal += Number(input.value) || 0;
            });

            return { home: homeTotal, away: awayTotal };
        }

        function updateScoreInputsFromQuarters() {
            const totals = getQuarterTotals();
            homeScoreInput.value = totals.home || '';
            awayScoreInput.value = totals.away || '';
        }

        document.querySelectorAll('.quarter-input').forEach(input => {
            input.addEventListener('input', () => {
                const value = input.value;
                const side = input.dataset.side;
                const quarter = input.dataset.quarter;

                updateScoreInputsFromQuarters();

                //hned pošleme ajax na uložení čtvrtiny
                fetch('ajax_update_quarter.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
                    },
                    body: new URLSearchParams({
                        game_id: gameId,
                        side: side,
                        quarter: quarter,
                        value: value
                    })
                })
                    .then(resp => resp.text())
                    .then(text => {
                        console.log('Server odpověděl: ', text);
                    })
                    .catch(err => {
                        console.error('Chyba při komunikaci se serverem: ', err);
                        alert('Nepodařilo se uložit čtvrtinu');
                    });
            });
        });

        //validace při odeslání formuláře
        form.addEventListener('submit', (e) => {
            const totals = getQuarterTotals();
            const homeFromInput = Number(homeScoreInput.value) || 0;
            const awayFromInput = Number(awayScoreInput.value) || 0;

            if (totals.home !== homeFromInput || totals.away !== awayFromInput) {
                e.preventDefault();
                alert(
                    `Součet čtvrtin (${totals.home}:${totals.away}) ` +
                    `neodpovídá zadanému skóre (${homeFromInput}:${awayFromInput}).\n` +
                    `oprav to prosím.`
                );
            }
        });
    </script>
</body>
</html>
