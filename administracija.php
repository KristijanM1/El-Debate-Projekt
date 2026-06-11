<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
include 'connect.php';

define('UPLPATH', 'img/');

$uspjesnaPrijava = false;
$admin = false;
$imeKorisnika = '';
$levelKorisnika = 0;

// Provjera je li korisnik već prijavljen
if (isset($_SESSION['korisnicko_ime']) && isset($_SESSION['razina'])) {
    $uspjesnaPrijava = true;
    $imeKorisnika = $_SESSION['korisnicko_ime'];
    $levelKorisnika = $_SESSION['razina'];
    if ($levelKorisnika == 1) {
        $admin = true;
    }
}

// Obrada login forme
if (isset($_POST['prijava'])) {
    $prijavaImeKorisnika = $_POST['username'];
    $prijavaLozinkaKorisnika = $_POST['lozinka'];
    
    // Provjera da li korisnik postoji u bazi uz zaštitu od SQL injectiona
    $sql = "SELECT korisnicko_ime, lozinka, razina FROM korisnik WHERE korisnicko_ime = ?";
    $stmt = mysqli_stmt_init($dbc);
    
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, 's', $prijavaImeKorisnika);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
    }
    
    mysqli_stmt_bind_result($stmt, $imeKorisnika, $lozinkaKorisnika, $levelKorisnika);
    mysqli_stmt_fetch($stmt);
    
    // Provjera lozinke
    if (password_verify($prijavaLozinkaKorisnika, $lozinkaKorisnika) && mysqli_stmt_num_rows($stmt) > 0) {
        $uspjesnaPrijava = true;
        
        // Provjera da li je admin
        if ($levelKorisnika == 1) {
            $admin = true;
        }
        
        // Postavljanje session varijabli
        $_SESSION['korisnicko_ime'] = $imeKorisnika;
        $_SESSION['razina'] = $levelKorisnika;
    } else {
        $uspjesnaPrijava = false;
    }
}

// Obrada logout
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: administracija.php");
    exit;
}

// Obrada delete - samo admin smije brisati
if (isset($_POST['delete'])) {
    if (!$admin) {
        header("Location: administracija.php");
        exit;
    }

    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    $sql = "DELETE FROM vijesti WHERE id = ?";
    $stmt = mysqli_stmt_init($dbc);

    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    header("Location: administracija.php");
    exit;
}

// Dohvati vijesti samo ako je korisnik admin
$result = null;
if ($admin) {
    $result = mysqli_query($dbc, "SELECT * FROM vijesti ORDER BY id DESC");
}

mysqli_close($dbc);
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administracija</title>
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
<main class="admin-page">
    <?php if ($admin): ?>
        <!-- ADMIN PANEL -->
        <div class="admin-header">
            <h1>Administracija vijesti</h1>
            <div class="admin-user-info">
                <p>Dobrodošli, <strong><?php echo htmlspecialchars($imeKorisnika); ?></strong>!</p>
                <form method="POST" action="" class="inline-form">
                    <button type="submit" name="logout" class="logout-btn">Odjava</button>
                </form>
            </div>
        </div>
        
        <div class="news-grid">
            <?php while ($row = mysqli_fetch_array($result)): ?>
                <div class="admin-card">
                    <?php if (!empty($row['slika'])): ?>
                        <img class="admin-card-image" src="<?php echo UPLPATH . htmlspecialchars($row['slika']); ?>" alt="<?php echo htmlspecialchars($row['naslov']); ?>">
                    <?php else: ?>
                        <div class="admin-card-image"></div>
                    <?php endif; ?>
                    <div class="admin-card-content">
                        <?php if (!empty($row['tag'])): ?>
                            <span class="tag"><?php echo htmlspecialchars($row['tag']); ?></span>
                        <?php endif; ?>
                        <h3><?php echo htmlspecialchars($row['naslov']); ?></h3>
                        <p><?php echo htmlspecialchars(substr($row['sazetak'], 0, 80)); ?>...</p>
                        <div class="admin-card-meta">
                            <span><?php echo htmlspecialchars($row['kategorija']); ?></span>
                            <span><?php echo htmlspecialchars($row['datum']); ?></span>
                            <?php if ($row['arhiva']): ?>
                                <span class="archive-badge">ARHIVIRANO</span>
                            <?php endif; ?>
                        </div>
                        <div class="admin-card-actions">
                            <a href="uredi-vijest.php?id=<?php echo $row['id']; ?>" class="edit">Uredi</a>
                            <form method="POST" action="administracija.php">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="delete" class="delete" onclick="return confirm('Jeste li sigurni da želite izbrisati ovu vijest?');">Obriši</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

    <?php elseif ($uspjesnaPrijava && !$admin): ?>
        <!-- OBIČNA PRIJAVA - NEMA PRAVA -->
        <div class="message error">
            <h2>Nema pristupa</h2>
            <p>Bok <strong><?php echo htmlspecialchars($imeKorisnika); ?></strong>! Uspješno ste prijavljeni, ali nemate administratorska prava za pristup ovoj stranici.</p>
            <form method="POST" action="" class="inline-form">
                <button type="submit" name="logout" class="logout-btn">Odjava</button>
            </form>
        </div>

    <?php else: ?>
        <!-- LOGIN FORMA -->
        <div class="login-page">
            <h1>Prijava</h1>
            <form method="POST" action="">
                <div class="form-item">
                    <label for="username">Korisničko ime:</label>
                    <input type="text" name="username" id="username" required>
                </div>

                <div class="form-item">
                    <label for="lozinka">Lozinka:</label>
                    <input type="password" name="lozinka" id="lozinka" required>
                </div>

                <?php if (isset($_POST['prijava'])): ?>
                    <div class="message error">
                        <p>Pogrešno korisničko ime ili lozinka! <a href="registracija.php">Prijavite se prvi put</a></p>
                    </div>
                <?php endif; ?>

                <button type="submit" name="prijava">Prijava</button>
            </form>

            <div class="register-link">
                Prvi put? <a href="registracija.php">Registrirajte se ovdje</a>
            </div>
        </div>

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
