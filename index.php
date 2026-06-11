<?php
session_start();
include 'connect.php';
define('UPLPATH', 'img/');

function renderArticles($dbc, $category) {
$sql = "SELECT * FROM vijesti
        WHERE arhiva = 0 AND kategorija = ?
        ORDER BY id DESC
        LIMIT 4";
    $stmt = mysqli_stmt_init($dbc);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return '<p>Greška pri dohvaćanju vijesti.</p>';
    }

    mysqli_stmt_bind_param($stmt, "s", $category);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $html = '';

    while ($row = mysqli_fetch_array($result)) {
        $html .= '<article class="card">';
        $html .= '<a href="clanak.php?id=' . intval($row['id']) . '">';
        $html .= '<div class="card-image">';
        $html .= '<img src="' . UPLPATH . htmlspecialchars($row['slika']) . '" alt="' . htmlspecialchars($row['naslov']) . '">';
        $html .= '</div>';
        $html .= '<span class="tag">' . htmlspecialchars($row['tag']) . '</span>';
        $html .= '<h3>' . htmlspecialchars($row['naslov']) . '</h3>';
        $html .= '<p>' . htmlspecialchars($row['sazetak']) . '</p>';
        $html .= '</a></article>';
    }

    mysqli_stmt_close($stmt);

    return $html;
}

?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EL Debate</title>
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
            <?php if (isset($_SESSION['razina']) && $_SESSION['razina'] == 1): ?>
            <li><a href="unos.php">UNOS</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
<main>
    <section class="category">
        <div class="section-title">
            <span class="section-marker"></span>
            <h2>MUNDO</h2>
        </div>
        <div class="news-grid">
            <?php echo renderArticles($dbc, 'MUNDO'); ?>
        </div>
    </section>
    <section class="category">
        <div class="section-title">
            <span class="section-marker"></span>
            <h2>DEPORTE</h2>
        </div>
        <div class="news-grid">
            <?php echo renderArticles($dbc, 'DEPORTE'); ?>
        </div>
    </section>
    <section class="category">
        <div class="section-title">
            <span class="section-marker"></span>
            <h2>KULTURA</h2>
        </div>
        <div class="news-grid">
            <?php echo renderArticles($dbc, 'KULTURA'); ?>
        </div>
    </section>
    <section class="category">
        <div class="section-title">
            <span class="section-marker"></span>
            <h2>ZABAVA</h2>
        </div>
        <div class="news-grid">
            <?php echo renderArticles($dbc, 'ZABAVA'); ?>
        </div>
    </section>
    <section class="category">
        <div class="section-title">
            <span class="section-marker"></span>
            <h2>POLITIKA</h2>
        </div>
        <div class="news-grid">
            <?php echo renderArticles($dbc, 'POLITIKA'); ?>
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
