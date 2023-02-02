<?php
function registerHandler()
{
    //INSERT INTO `users` (`id`, `email`, `password`, `createdAt`) VALUES (NULL, 'asd@asd.com', 'asd1234', '1111111');


    $pdo = getConnection();



    $statement = $pdo->prepare("SELECT * FROM `users` WHERE users . email = ?");
    $statement->execute([$_POST["email"]]);
    $isUserExist = $statement->fetch(PDO::FETCH_ASSOC);

    if ($isUserExist) {
        echo "User is actually exist";
        header("Location:" . getPathWithId($_SERVER["HTTP_REFERER"]) . "&isRegistration=1&info=userExist");
        return;
    }

    if ($_POST["email"]  === "" || $_POST["password"] === "") {
        echo "Invalid input field values!";
        header("Location:" . getPathWithId($_SERVER["HTTP_REFERER"]) . "&isRegistration=1&info=invalidInputValues");
        return;
    }

    $statement = $pdo->prepare("INSERT INTO `users` (`id`, `email`, `password`, `createdAt`) VALUES (NULL, ?, ?, ?)");
    $statement->execute([
        $_POST["email"],
        password_hash($_POST["password"], PASSWORD_DEFAULT),
        time() // -> Unix időbélyeg
    ]);

    header("Location:" . getPathWithId($_SERVER["HTTP_REFERER"]) . "&info=registrationSuccesfull");
}



function loginHandler()
{
    //SELECT * FROM `users` WHERE users . email = "asd@asd.com";



    $pdo = getConnection();
    $statement = $pdo->prepare("SELECT * FROM `users` WHERE users . email = ?");
    $statement->execute([$_POST["email"]]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        header("Location:" . getPathWithId($_SERVER["HTTP_REFERER"]) . "&info=invalidCredentials");
        return;
    }


    $isVerified = password_verify($_POST["password"], $user["password"]);

    if (!$isVerified) {
        header("Location:" . getPathWithId($_SERVER["HTTP_REFERER"]) . "&info=invalidCredentials");
        return;
    }

    session_start(); // -> futása során módositást végez a filerendszerben , header infok közé cookie-kat ír!;
    $_SESSION['userId'] = $user["id"]; // A $_SESSION egy üres asszociativ tömbként kezdi pályafutását , és bármennyi kulcsérték párt hozzáfűzhetünk.
    // De csak akkor , ha a session el van inditva



    // Azt szeretnénk ha a visszajelentkezés után arra az oldalra dobna vissza, ahonnan kiindultunk!
    // EHHEZ A $_SERVER["HTTP_REFERERT"] HASZNÁLJUK , AMI ÖNMAGÁBAN AZÉRT NEM ELÉG, MERT HA A USER TÖBB QUERY-T DOB AZ URL-BE AKKOR AZOKAT IS BELE VESZI
    // EZÉRT KELL AZ, HOGY SZÉTSZEDJÜK AZ URLT- ERRE EGY DEDIKÁLT getPathWithId($url) FUNCTION HOZUNK LÉTRE
    header("Location:" . getPathWithId($_SERVER["HTTP_REFERER"]));
}

function logoutHandler()
{


    session_start(); // Hivjuk meg a sessiont
    session_destroy(); // MAJD Destroyoljuk
    // Ettől a böngészőből nem fog eltünni a session id, ahhoz egy süti fejlécet is meg kell küldeni minek segitségével a lejárati dátumát vissza állítjuk a múltba! Emiatt kitörli a böngésző automatikusan a sütit;


    //Bármilyen sütit akarsz beállítani, azt a secookie functionnel tudod megtenni, amely 1. paraméterként várja annak a sütinek az értékét amit be akarsz állítani,
    // Ehhez a session_name-et fogjuk használn 1. paraméterként.
    // Többi paraméterként a süti adatait kell bedobni : 2. Mi legyen az értéke, 3. Mikor járjon le
    // Ahhoz hogy a cookiet valójában be tudjuk állítani, ahhoz az összes paramétert ugyan úgy kell megadni mint ahogy létre lett hozva
    // Mivel ezt fejből nem tudjuk hiszen a session start hozta létre ezért a session_get_cookie_params() meghivásával ezt megtehetjük.

    $cookieParams = session_get_cookie_params();
    //var_dump($cookieParams);
    // Igy már a $cookiParams-ból kiszedhetjük a paramétereket amikre szükségünk van!
    setcookie(session_name(), "", 0, $cookieParams["path"], $cookieParams["domain"], $cookieParams["secure"], isset($cookieParams["httponly"]));
    header("Location:" . getPathWithId($_SERVER["HTTP_REFERER"]));
}


function isLoggedIn()
{
    if (!isset($_COOKIE[session_name()])) return false;
    session_start();
    if (!isset($_SESSION["userId"])) return false;
    return true;



    /**
  function isLoggedIn()
   {
    if (!$_COOKIE[session_name()]) { // A SESSION NAME SEGÍTSÉGÉVEL MINDIG DINAMIKUSAN A MEGFELELEŐ NÉV KERÜL A KEZEDBE HA ESETLEG A SESSIONT EGYEDI NÉVEN INDÍTANÁD!
        // Kideritjük hogy szerver oldalon ez egy ténylegesen létező munkamenet vagy sem;
        session_start(); // Ezért meghivjuk a session_start-functiont igy ha id alapján létezik ilyen munkafolyamat akkor előkeresi azt , ha nem akkor indit egy újat
        // Innentől tudjuk használni a $_SESSION global valtozot
        if (isset($_SESSION['userId'])) {
            // Vdett tartalom....
        }
    };
    exit;
    // MIVEL KI AKARJUK SZERVEZNI EZÉRT NEGÁLUNK!
}
     * 
     */
}

