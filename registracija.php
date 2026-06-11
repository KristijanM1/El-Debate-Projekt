<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
include 'connect.php';

$msg = '';
$registriranKorisnik = false;
$ime = '';
$prezime = '';

// Provjera postoji li form submit
if (isset($_POST['registracija'])) {
    $ime = $_POST['ime'];
    $prezime = $_POST['prezime'];
    $username = $_POST['username'];
    $lozinka = $_POST['pass'];
    $lozinkaRep = $_POST['passRep'];
    
    // Provjera da se lozinke poklapaju
    if ($lozinka !== $lozinkaRep) {
        $msg = 'Lozinke se ne poklapaju!';
    } else {
        // Hash lozinka
        $hashed_password = password_hash($lozinka, PASSWORD_BCRYPT);
        $razina = 0;
        
        // Provjera postoji li u bazi već korisnik s tim korisničkim imenom
        $sql = "SELECT korisnicko_ime FROM korisnik WHERE korisnicko_ime = ?";
        $stmt = mysqli_stmt_init($dbc);
        
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, 's', $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
        }
        
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $msg = 'Korisničko ime već postoji!';
        } else {
            // Ako ne postoji korisnik s tim korisničkim imenom - Registracija korisnika u bazi
            $sql = "INSERT INTO korisnik (ime, prezime, korisnicko_ime, lozinka, razina) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_stmt_init($dbc);
            
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, 'ssssi', $ime, $prezime, $username, $hashed_password, $razina);
                mysqli_stmt_execute($stmt);
                $registriranKorisnik = true;
            }
        }
    }
}

mysqli_close($dbc);
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registracija</title>
    <link rel="stylesheet" href="style.css?v=<?php echo filemtime(__DIR__ . '/style.css'); ?>">
</head>
<body>
    <div class="registration-page">
        <?php if ($registriranKorisnik): ?>
            <div class="success-message">
                <h2>Registracija uspješna!</h2>
                <p>Korisnik je uspješno registriran. Sada možete pristupiti administraciji.</p>
                <p><a href="administracija.php">Idite na administraciju →</a></p>
            </div>
        <?php else: ?>
            <h1>Registracija</h1>
            <form method="POST" action="">
                <div class="form-item">
                    <label for="ime">Ime:</label>
                    <input type="text" name="ime" id="ime" value="<?php echo htmlspecialchars($ime, ENT_QUOTES, 'UTF-8'); ?>" required>
                    <div class="form-error" id="porukaIme"></div>
                </div>

                <div class="form-item">
                    <label for="prezime">Prezime:</label>
                    <input type="text" name="prezime" id="prezime" value="<?php echo htmlspecialchars($prezime, ENT_QUOTES, 'UTF-8'); ?>" required>
                    <div class="form-error" id="porukaPrezime"></div>
                </div>

                <div class="form-item">
                    <label for="username">Korisničko ime:</label>
                    <input type="text" name="username" id="username" required <?php if ($msg) echo 'class="error"'; ?>>
                    <div class="form-error<?php if ($msg) echo ' is-visible'; ?>" id="porukaUsername">
                        <?php if ($msg) echo $msg; ?>
                    </div>
                </div>

                <div class="form-item">
                    <label for="pass">Lozinka:</label>
                    <input type="password" name="pass" id="pass" required>
                    <div class="form-error" id="porukaPass"></div>
                </div>

                <div class="form-item">
                    <label for="passRep">Ponovite lozinku:</label>
                    <input type="password" name="passRep" id="passRep" required>
                    <div class="form-error" id="porukaPassRep"></div>
                </div>

                <button type="submit" name="registracija">Registracija</button>
            </form>

            <div class="login-link">
                Već imate račun? <a href="administracija.php">Prijavite se</a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Čisti grešku sa username polja kada korisnik počne pisati
        document.getElementById('username').addEventListener('input', function() {
            if (!document.getElementById('porukaUsername').textContent.includes('već postoji')) {
                this.classList.remove('error');
            }
        });

        document.querySelector('form').addEventListener('submit', function(event) {
            let isValid = true;

            // Ime validacija
            const ime = document.getElementById('ime').value;
            if (ime.length === 0) {
                isValid = false;
                document.getElementById('ime').classList.add('error');
                document.getElementById('porukaIme').textContent = 'Unesite ime!';
                document.getElementById('porukaIme').classList.add('is-visible');
            } else {
                document.getElementById('ime').classList.add('success');
                document.getElementById('porukaIme').classList.remove('is-visible');
            }

            // Prezime validacija
            const prezime = document.getElementById('prezime').value;
            if (prezime.length === 0) {
                isValid = false;
                document.getElementById('prezime').classList.add('error');
                document.getElementById('porukaPrezime').textContent = 'Unesite prezime!';
                document.getElementById('porukaPrezime').classList.add('is-visible');
            } else {
                document.getElementById('prezime').classList.add('success');
                document.getElementById('porukaPrezime').classList.remove('is-visible');
            }

            // Username validacija
            const username = document.getElementById('username').value;
            if (username.length === 0) {
                isValid = false;
                document.getElementById('username').classList.add('error');
                document.getElementById('porukaUsername').textContent = 'Unesite korisničko ime!';
                document.getElementById('porukaUsername').classList.add('is-visible');
            } else {
                document.getElementById('username').classList.add('success');
                if (!document.getElementById('porukaUsername').textContent.includes('već postoji')) {
                    document.getElementById('porukaUsername').classList.remove('is-visible');
                }
            }

            // Lozinka validacija
            const pass = document.getElementById('pass').value;
            const passRep = document.getElementById('passRep').value;
            if (pass.length === 0 || passRep.length === 0 || pass !== passRep) {
                isValid = false;
                document.getElementById('pass').classList.add('error');
                document.getElementById('passRep').classList.add('error');
                document.getElementById('porukaPass').textContent = 'Lozinke se ne poklapaju!';
                document.getElementById('porukaPass').classList.add('is-visible');
                document.getElementById('porukaPassRep').textContent = 'Lozinke se ne poklapaju!';
                document.getElementById('porukaPassRep').classList.add('is-visible');
            } else {
                document.getElementById('pass').classList.add('success');
                document.getElementById('passRep').classList.add('success');
                document.getElementById('porukaPass').classList.remove('is-visible');
                document.getElementById('porukaPassRep').classList.remove('is-visible');
            }

            if (!isValid) {
                event.preventDefault();
            }
        });
    </script>
</body>
</html>
