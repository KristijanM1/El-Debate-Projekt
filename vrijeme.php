<?php
session_start();
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vrijeme u Španjolskoj</title>
    <link rel="stylesheet" href="style.css?v=<?php echo filemtime(__DIR__ . '/style.css'); ?>">
    <script src="vrijeme.js" defer></script>
</head>
<body>
<header>
    <div class="logo">
        <img src="img/logo2.png" alt="">
        <h1>debate</h1>
    </div>
    <nav>
        <ul>
            <li><a href="index.php">HOME</a></li>
            <li><a href="kategorija.php?id=MUNDO">MUNDO</a></li>
            <li><a href="kategorija.php?id=DEPORTE">DEPORTE</a></li>
            <li><a href="kategorija.php?id=KULTURA">KULTURA</a></li>
            <li><a href="kategorija.php?id=ZABAVA">ZABAVA</a></li>
            <li><a href="kategorija.php?id=POLITIKA">POLITIKA</a></li>
            <li class="active"><a href="vrijeme.php">VRIJEME</a></li>
            <li><a href="administracija.php">ADMINISTRACIJA</a></li>
            <?php if (isset($_SESSION['razina']) && $_SESSION['razina'] == 1): ?>
            <li><a href="unos.php">UNOS</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<main class="weather-page">
    <section class="weather-intro">
        <span class="article-category">ŠPANJOLSKA</span>
        <h1>Vremenska prognoza</h1>
        <p>Odaberite grad i pogledajte trenutačne uvjete te prognozu za sljedećih sedam dana.</p>

        <div class="weather-city-picker">
            <label for="city">Odaberite grad</label>
            <select id="city" name="city">
                <option value="madrid">Madrid</option>
                <option value="barcelona">Barcelona</option>
                <option value="valencia">Valencia</option>
                <option value="sevilla">Sevilla</option>
                <option value="bilbao">Bilbao</option>
                <option value="malaga">Málaga</option>
            </select>
        </div>
    </section>

    <p id="weather-status" class="weather-status" role="status">Učitavanje prognoze...</p>

    <section id="weather-content" class="weather-content" hidden>
        <article class="current-weather">
            <div>
                <span id="current-city" class="current-city"></span>
                <h2 id="current-condition"></h2>
                <p id="current-time" class="current-time"></p>
            </div>
            <div class="current-temperature">
                <strong id="current-temperature"></strong>
                <span>°C</span>
            </div>
            <dl class="weather-details">
                <div>
                    <dt>Osjećaj</dt>
                    <dd id="apparent-temperature"></dd>
                </div>
                <div>
                    <dt>Vlažnost</dt>
                    <dd id="humidity"></dd>
                </div>
                <div>
                    <dt>Vjetar</dt>
                    <dd id="wind-speed"></dd>
                </div>
            </dl>
        </article>

        <div>
            <div class="section-title weather-section-title">
                <span class="section-marker"></span>
                <h2>Prognoza za 7 dana</h2>
            </div>
            <div id="forecast-grid" class="forecast-grid"></div>
        </div>
    </section>
</main>

<footer>
    <div class="footer-top"></div>
    <div class="footer-bottom">
        <p>© Copyright EL DEBATE. Todos los derechos reservados.</p>
    </div>
</footer>
</body>
</html>
