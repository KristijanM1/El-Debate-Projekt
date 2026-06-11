<?php
session_start();
include 'connect.php';
define('UPLPATH', 'img/');

// Zaštita: samo admin smije unositi vijesti
if (!isset($_SESSION['razina']) || $_SESSION['razina'] != 1) {
    header("Location: administracija.php");
    exit;
}

// Dozvoli samo POST slanje forme
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: unos.php");
    exit;
}

$allowedCategories = ['MUNDO', 'DEPORTE', 'KULTURA', 'ZABAVA', 'POLITIKA'];

$title = trim($_POST['title'] ?? '');
$summary = trim($_POST['summary'] ?? '');
$tag = trim($_POST['tag'] ?? '');
$content = trim($_POST['content'] ?? '');
$category = trim($_POST['category'] ?? '');
$archive = isset($_POST['archive']) ? 1 : 0;
$imageName = '';
$date = date('d.m.Y.');

// Provjera kategorije da netko ne šalje svoje vrijednosti
if (!in_array($category, $allowedCategories)) {
    $category = 'MUNDO';
}

// Upload slike
if (!empty($_FILES['image']['name'])) {
    $originalName = basename($_FILES['image']['name']);
    $imageName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);

    $target_dir = UPLPATH . $imageName;

    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $fileType = mime_content_type($_FILES['image']['tmp_name']);

    if (in_array($fileType, $allowedTypes)) {
        move_uploaded_file($_FILES['image']['tmp_name'], $target_dir);
    } else {
        $imageName = '';
    }
}

// Prepared statement za unos vijesti
if (!empty($title) && !empty($summary) && !empty($content)) {
    $sql = "INSERT INTO vijesti 
            (datum, naslov, sazetak, tekst, tag, slika, kategorija, arhiva) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_stmt_init($dbc);

    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param(
            $stmt,
            "sssssssi",
            $date,
            $title,
            $summary,
            $content,
            $tag,
            $imageName,
            $category,
            $archive
        );

        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prikaz vijesti</title>
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
<main class="article-page">
    <span class="article-category"><?php echo htmlspecialchars($category); ?></span>
    <h1><?php echo htmlspecialchars($title); ?></h1>
    <h2><?php echo htmlspecialchars($summary); ?></h2>
    <p class="date">
        Status objave: <?php echo $archive === 0 ? 'Vidljivo' : 'Arhivirano'; ?>
        <?php if ($imageName): ?>| Odabrana slika: <?php echo htmlspecialchars($imageName); ?><?php endif; ?>
    </p>
    <?php if ($archive === 1): ?>
        <p class="notice">Ova vijest je spremljena u arhivu i neće se prikazivati na naslovnici.</p>
    <?php endif; ?>
    <?php if ($imageName): ?>
        <img src="<?php echo UPLPATH . htmlspecialchars($imageName); ?>" alt="<?php echo htmlspecialchars($title); ?>">
    <?php endif; ?>
    <article class="article-content">
        <p><?php echo nl2br(htmlspecialchars($content)); ?></p>
    </article>
</main>
<footer>
    <div class="footer-top"></div>
    <div class="footer-bottom">
        <p>© Copyright EL DEBATE. Todos los derechos reservados.</p>
    </div>
</footer>
</body>
</html>
