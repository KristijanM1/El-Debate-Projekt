<?php
session_start();
include 'connect.php';
define('UPLPATH', 'img/');

$allowed = ['MUNDO', 'DEPORTE', 'KULTURA', 'ZABAVA', 'POLITIKA'];
$category = isset($_GET['id']) && in_array($_GET['id'], $allowed) ? $_GET['id'] : 'MUNDO';

$sql = "SELECT * FROM vijesti WHERE arhiva = 0 AND kategorija = ? ORDER BY id DESC";
$stmt = mysqli_stmt_init($dbc);

if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, "s", $category);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    $result = false;
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategorija <?php echo htmlspecialchars($category); ?></title>
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
            <h2><?php echo htmlspecialchars($category); ?></h2>
        </div>
        <div class="news-grid">
            <?php
            if ($result) {
                while ($row = mysqli_fetch_array($result)) {
                    echo '<article class="card">';
                    echo '<a href="clanak.php?id=' . $row['id'] . '">';
                    echo '<div class="card-image">';
                    echo '<img src="' . UPLPATH . htmlspecialchars($row['slika']) . '" alt="' . htmlspecialchars($row['naslov']) . '">';
                    echo '</div>';
                    echo '<span class="tag">' . htmlspecialchars($row['tag']) . '</span>';
                    echo '<h3>' . htmlspecialchars($row['naslov']) . '</h3>';
                    echo '<p>' . htmlspecialchars($row['sazetak']) . '</p>';
                    echo '</a></article>';
                }
            } else {
                echo '<p>Greška pri dohvaćanju vijesti.</p>';
            }
            ?>
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
