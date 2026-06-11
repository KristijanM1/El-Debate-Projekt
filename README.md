# El Debate

Web-aplikacija za pregled i administraciju vijesti, izrađena kao projekt za
predmete **Programiranje web aplikacija** te **Podatkovna povezanost i digitalna
infrastruktura**.


## Funkcionalnosti

### Javni dio

- prikaz najnovijih vijesti na naslovnici
- pregled svih vijesti unutar odabrane kategorije
- prikaz pojedinačnog članka sa slikom, sažetkom i datumom objave
- kategorije `MUNDO`, `DEPORTE`, `KULTURA`, `ZABAVA` i `POLITIKA`
- registracija novih korisnika
- prijava i odjava korisnika
- responzivan prikaz prilagođen različitim veličinama zaslona
- trenutačno vrijeme i sedmodnevna prognoza za odabrane gradove u Španjolskoj

### Administracija

- pristup zaštićen korisničkom sesijom i administratorskom razinom
- unos novih vijesti
- uređivanje postojećih vijesti
- brisanje vijesti
- upload slika u formatima JPEG, PNG, GIF i WebP
- arhiviranje vijesti bez njihovog trajnog brisanja
- pregled objavljenih i arhiviranih vijesti u administracijskom sučelju

## Tehnologije

- HTML5
- CSS3
- JavaScript
- PHP
- MySQL / MariaDB
- MySQLi i prepared statements
- Open-Meteo API
- XAMPP

Za vremensku prognozu koristi se javni
[Open-Meteo API](https://open-meteo.com/), za koji nije potreban API ključ.

## Preduvjeti

- instaliran [XAMPP]
- pokrenuti Apache i MySQL servise
- internetska veza za prikaz vremenske prognoze

## Pokretanje projekta

1. Preuzmite projekt kao ZIP ili ga klonirajte:

2. Smjestite mapu projekta u `xampp\htdocs` direktorij:

   ```text
   C:\xampp\htdocs\ElDebate
   ```

3. Pokrenite Apache i MySQL u XAMPP Control Panelu.

4. Otvorite `http://localhost/phpmyadmin`.

5. Uvezite datoteku `eldebate.sql`. SQL datoteka stvara bazu `eldebate`,
   potrebne tablice i početne podatke.

6. Otvorite aplikaciju:

   ```text
   http://localhost/ElDebate/
   ```

Postavke za spajanje na bazu nalaze se u datoteci `connect.php`. Zadane
vrijednosti prilagođene su standardnoj XAMPP instalaciji:

```text
server: localhost
korisnik: root
lozinka: prazna
baza: eldebate
```

## Testni računi

| Uloga | Korisničko ime | Lozinka |
|---|---|---|
| Administrator | `admin` | `admin` |
| Korisnik | `test` | `test` |

Obični korisnik može se uspješno prijaviti, ali nema pristup administracijskim
funkcijama. Novi računi registriraju se s korisničkom razinom `0`, dok
administrator ima razinu `1`.


## Struktura projekta

```text
ElDebate/
├── img/                  # Slike članaka i grafički elementi
├── administracija.php    # Prijava i upravljanje vijestima
├── clanak.php            # Prikaz pojedinačnog članka
├── connect.php           # Spajanje na bazu podataka
├── eldebate.sql          # Struktura baze i početni podaci
├── index.php             # Naslovnica
├── kategorija.php        # Prikaz vijesti po kategoriji
├── registracija.php      # Registracija korisnika
├── skripta.php           # Obrada unosa nove vijesti
├── style.css             # Stilovi aplikacije
├── unos.php              # Forma za unos vijesti
├── uredi-vijest.php      # Uređivanje i arhiviranje vijesti
├── vrijeme.js            # Dohvat i prikaz vremenske prognoze
└── vrijeme.php           # Stranica vremenske prognoze
```

## Baza podataka

Aplikacija koristi dvije glavne tablice:

- `vijesti` pohranjuje naslov, sažetak, tekst, oznaku, sliku, kategoriju, datum
  i status arhive
- `korisnik` pohranjuje podatke korisnika, hashiranu lozinku i razinu ovlasti

Lozinke se spremaju pomoću PHP funkcije `password_hash()`, a tijekom prijave
provjeravaju pomoću `password_verify()`. Upiti koji obrađuju korisnički unos
koriste prepared statements.
