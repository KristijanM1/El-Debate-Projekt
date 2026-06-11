<?php
session_start();
include 'connect.php';
define('UPLPATH', 'img/');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT * FROM vijesti WHERE id = ? LIMIT 1";
$stmt = mysqli_stmt_init($dbc);

if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);
    mysqli_stmt_close($stmt);
} else {
    $row = null;
}

$activeCategory = $row ? strtoupper(trim($row['kategorija'])) : '';
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $row ? htmlspecialchars($row['naslov']) : 'Članak nije pronađen'; ?></title>
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
            <li class="<?php echo trim($activeCategory) === 'MUNDO' ? 'active' : ''; ?>"><a href="kategorija.php?id=MUNDO">MUNDO</a></li>
            <li class="<?php echo trim($activeCategory) === 'DEPORTE' ? 'active' : ''; ?>"><a href="kategorija.php?id=DEPORTE">DEPORTE</a></li>
            <li class="<?php echo trim($activeCategory) === 'KULTURA' ? 'active' : ''; ?>"><a href="kategorija.php?id=KULTURA">KULTURA</a></li>
            <li class="<?php echo trim($activeCategory) === 'ZABAVA' ? 'active' : ''; ?>"><a href="kategorija.php?id=ZABAVA">ZABAVA</a></li>
            <li class="<?php echo trim($activeCategory) === 'POLITIKA' ? 'active' : ''; ?>"><a href="kategorija.php?id=POLITIKA">POLITIKA</a></li>
            <li><a href="vrijeme.php">VRIJEME</a></li>
            <li><a href="administracija.php">ADMINISTRACIJA</a></li>
            <?php if (isset($_SESSION['razina']) && $_SESSION['razina'] == 1): ?>
            <li><a href="unos.php">UNOS</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
<main class="article-page">
    <?php if ($row): ?>
        <span class="article-category"><?php echo htmlspecialchars($row['kategorija']); ?></span>
        <h1><?php echo htmlspecialchars($row['naslov']); ?></h1>
        <h2><?php echo htmlspecialchars($row['sazetak']); ?></h2>
        <p class="date">OBJAVLJENO: <?php echo htmlspecialchars($row['datum']); ?></p>
        <?php if (!empty($row['slika'])): ?>
            <img src="<?php echo UPLPATH . htmlspecialchars($row['slika']); ?>" alt="<?php echo htmlspecialchars($row['naslov']); ?>">
        <?php endif; ?>
        <article class="article-content">
            <p><?php echo nl2br(htmlspecialchars($row['tekst'])); ?></p>
        </article>
    <?php else: ?>
        <p>Članak nije pronađen.</p>
    <?php endif; ?>
</main>
<footer>
    <div class="footer-top"></div>
    <div class="footer-bottom">
        <p>© Copyright EL DEBATE. Todos los derechos reservados.</p>
    </div>
</footer>
</body>
</html>
