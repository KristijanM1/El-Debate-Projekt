<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
include 'connect.php';

// Provjera je li korisnik admin
$admin = false;
if (isset($_SESSION['razina']) && $_SESSION['razina'] == 1) {
    $admin = true;
}

mysqli_close($dbc);
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unos vijesti</title>
    <link rel="stylesheet" href="style.css?v=<?php echo filemtime(__DIR__ . '/style.css'); ?>">
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
            <li><a href="vrijeme.php">VRIJEME</a></li>
            <li><a href="administracija.php">ADMINISTRACIJA</a></li>
            <?php if ($admin): ?>
                <li><a href="unos.php">UNOS</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<?php if ($admin): ?>
    <main class="article-page">
        <span class="article-category">ADMINISTRACIJA</span>
        <h1>Unos nove vijesti</h1>
        <form name="unosForm" action="skripta.php" method="POST" enctype="multipart/form-data" class="news-form">
            <div class="form-group">
                <label for="title">Naslov vijesti</label>
                <input type="text" id="title" name="title" required placeholder="Unesite naslov vijesti">
            </div>
            <div class="form-group">
                <label for="summary">Kratki sažetak</label>
                <textarea id="summary" name="summary" rows="4" required placeholder="Unesite kratak sažetak vijesti"></textarea>
            </div>
            <div class="form-group">
                <label for="tag">Oznaka vijesti</label>
                <input type="text" id="tag" name="tag" placeholder="Unesite oznaku koja se prikazuje pod slikom" required>
            </div>
            <div class="form-group">
                <label for="content">Tekst vijesti</label>
                <textarea id="content" name="content" rows="8" required placeholder="Unesite puni tekst vijesti"></textarea>
            </div>
            <div class="form-group">
                <label for="category">Kategorija vijesti</label>
                <select id="category" name="category" required>
                    <option value="">Odaberite kategoriju</option>
                    <option value="MUNDO">MUNDO</option>
                    <option value="DEPORTE">DEPORTE</option>
                    <option value="KULTURA">KULTURA</option>
                    <option value="ZABAVA">ZABAVA</option>
                    <option value="POLITIKA">POLITIKA</option>
                </select>
            </div>
            <div class="form-group">
                <label for="image">Odabir slike</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>
            <div class="form-group checkbox-group">
                <input type="checkbox" id="archive" name="archive" value="1">
                <label for="archive">Arhiviraj vijest (spremi ali ne prikazuj)</label>
            </div>
            <div class="form-group">
                <button type="submit">Pošalji vijest</button>
            </div>
        </form>
    </main>

<?php else: ?>
    <main class="restricted-page">
        <h2>Pristup odbijen</h2>
        <p>Stranica za unos vijesti dostupna je samo administratorima.</p>
        <p>Trebate biti prijavljen kao administrator da biste mogli unijeti nove vijesti.</p>
        <a href="administracija.php">Idite na administraciju →</a>
    </main>

<?php endif; ?>

<footer>
    <div class="footer-top"></div>
    <div class="footer-bottom">
        <p>© Copyright EL DEBATE. Todos los derechos reservados.</p>
    </div>
</footer>
</body>
</html>
