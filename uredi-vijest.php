<?php
session_start();
include 'connect.php';
define('UPLPATH', 'img/');

// Zaštita: samo admin smije otvoriti ovu stranicu
if (!isset($_SESSION['razina']) || $_SESSION['razina'] != 1) {
    header("Location: administracija.php");
    exit;
}

$allowedCategories = ['MUNDO', 'DEPORTE', 'KULTURA', 'ZABAVA', 'POLITIKA'];

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Dohvat vijesti preko prepared statementa
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

if (!$row) {
    header("Location: administracija.php");
    exit;
}

// Spremanje promjena
if (isset($_POST['update'])) {
    $title = trim($_POST['title'] ?? '');
    $summary = trim($_POST['summary'] ?? '');
    $tag = trim($_POST['tag'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $archive = isset($_POST['archive']) ? 1 : 0;
    $picture = $row['slika'];

    if (!in_array($category, $allowedCategories)) {
        $category = 'MUNDO';
    }

    if (!empty($_FILES['image']['name'])) {
        $originalName = basename($_FILES['image']['name']);
        $picture = preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);

        $target_dir = UPLPATH . $picture;

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $fileType = mime_content_type($_FILES['image']['tmp_name']);

        if (in_array($fileType, $allowedTypes)) {
            move_uploaded_file($_FILES['image']['tmp_name'], $target_dir);
        } else {
            $picture = $row['slika'];
        }
    }

    $sql = "UPDATE vijesti 
            SET naslov = ?, sazetak = ?, tag = ?, tekst = ?, slika = ?, kategorija = ?, arhiva = ? 
            WHERE id = ?";

    $stmt = mysqli_stmt_init($dbc);

    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param(
            $stmt,
            "ssssssii",
            $title,
            $summary,
            $tag,
            $content,
            $picture,
            $category,
            $archive,
            $id
        );

        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    header("Location: administracija.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uredi vijest</title>
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
<main class="edit-page">
    <div class="edit-form">
        <h1>Uredi vijest</h1>
        <form enctype="multipart/form-data" method="POST" action="uredi-vijest.php?id=<?php echo $id; ?>">
            <div class="form-group">
                <label for="title">Naslov vijesti</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($row['naslov']); ?>" required>
            </div>

            <div class="form-group">
                <label for="summary">Kratki sažetak</label>
                <textarea id="summary" name="summary" required><?php echo htmlspecialchars($row['sazetak']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="tag">Oznaka vijesti</label>
                <input type="text" id="tag" name="tag" value="<?php echo htmlspecialchars($row['tag']); ?>" required>
            </div>

            <div class="form-group">
                <label for="content">Sadržaj vijesti</label>
                <textarea id="content" name="content" required><?php echo htmlspecialchars($row['tekst']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="category">Kategorija</label>
                <select id="category" name="category" required>
                    <option value="MUNDO" <?php echo $row['kategorija'] === 'MUNDO' ? 'selected' : ''; ?>>MUNDO</option>
                    <option value="DEPORTE" <?php echo $row['kategorija'] === 'DEPORTE' ? 'selected' : ''; ?>>DEPORTE</option>
                    <option value="KULTURA" <?php echo $row['kategorija'] === 'KULTURA' ? 'selected' : ''; ?>>KULTURA</option>
                    <option value="ZABAVA" <?php echo $row['kategorija'] === 'ZABAVA' ? 'selected' : ''; ?>>ZABAVA</option>
                    <option value="POLITIKA" <?php echo $row['kategorija'] === 'POLITIKA' ? 'selected' : ''; ?>>POLITIKA</option>
                </select>
            </div>

            <div class="form-group">
                <label for="image">Slika</label>
                <input type="file" id="image" name="image" accept="image/*">
                <?php if (!empty($row['slika'])): ?>
                    <div class="current-image">
                        <p>Trenutna slika:</p>
                        <img src="<?php echo UPLPATH . htmlspecialchars($row['slika']); ?>" alt="<?php echo htmlspecialchars($row['naslov']); ?>">
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group form-checkbox">
                <input type="checkbox" id="archive" name="archive" value="1" <?php echo $row['arhiva'] ? 'checked' : ''; ?>>
                <label for="archive" class="checkbox-label">Arhiviraj vijest</label>
            </div>

            <div class="form-actions">
                <button type="submit" name="update" class="save">Spremi promjene</button>
                <a href="administracija.php" class="cancel">Otkaži</a>
            </div>
        </form>
    </div>
</main>
<footer>
    <div class="footer-top"></div>
    <div class="footer-bottom">
        <p>© Copyright EL DEBATE. Todos los derechos reservados.</p>
    </div>
</footer>
</body>
</html>
