<?php

// útvonalválasztó
// https://kodbazis.hu/php-az-alapoktol/termek-listazo-website

$method = $_SERVER["REQUEST_METHOD"];
$parsed = parse_url($_SERVER['REQUEST_URI']);
$path = $parsed['path'];

$routes = [
    'GET' => [
        '/learn-php/13Authentication/server/' => 'countryListHandler',
        '/learn-php/13Authentication/server/check-country' => 'singleCountryHandler',
        '/learn-php/13Authentication/server/check-city' => 'singleCityHandler'
    ],
    'POST' => [
        '/learn-php/13Authentication/server/register' => 'registerHandler',
        '/learn-php/13Authentication/server/login' => 'loginHandler',
        '/learn-php/13Authentication/server/logout' => 'logoutHandler'
    ],
];

$handlerFunction = $routes[$method][$path] ?? "notFoundHandler";

$handlerFunction();









function registerHandler()
{
    //INSERT INTO `users` (`id`, `email`, `password`, `createdAt`) VALUES (NULL, 'asd@asd.com', 'asd1234', '1111111');


    $pdo = getConnection();
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



function getPathWithId($url): string
{
    $parsed = parse_url($url); // MEGKAPJUK AZ URLT-T AMIT PARSE-OLUNK
    if (!isset($parsed["query"])) { // A PARSEOLT URL-BŐL KI KÉRJÜK A QUERY-T AMI HA NEM LÉTEZIK EARLY-RETURN
        return $url . "?id=";
    }

    $queryParams = []; // SZÉT AKARJUK SZEDNI A QUERYT , ASSZOCIATIV TÖMBÖKRE EZÉRT LÉTRE HOZUNK NEKI EGY ÜRES TÖMBÖT
    parse_str($parsed['query'], $queryParams); // AMIBE A parse_str(parseolni kivánt string, kimeneti változó) függvényt meghivjuk

    return $parsed["path"] . "?id=" . $queryParams["id"]; // MAJD A VISSZATÉRÉSI STRINGET A LOCATION HEADERNEK MEGFELELŐEN ÖSSZEKONKATENÁLJUK!

    // ----> !!!!! EZT A FÜGGVÉNYT BÁRMELYIK LOCATION HEADER ÁTIRÁNYITÁSNÁL HASZNÁLHATJUK ÉS KONKATENÁLHATUNK HOZZÁ BÁRMIT
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













function singleCountryHandler()
{

    if (!isLoggedIn()) {
        echo compileTemplate("wrapper.phtml", [
            'content' => compileTemplate("subscriptionForm.phtml", [
                'info' => $_GET["info"] ?? "",
                'isRegistration' => isset($_GET['isRegistration']),
                'url' => getPathWithId($_SERVER["REQUEST_URI"])
            ]),

        ]);
        return;
    }

    $countryId = $_GET['id'] ?? '';
    $pdo = getConnection();
    $statement = $pdo->prepare('SELECT * FROM countries WHERE id = ?');
    $statement->execute([$countryId]);
    $country = $statement->fetch(PDO::FETCH_ASSOC);

    $statement = $pdo->prepare('SELECT * FROM `cities` WHERE countryId = ?');
    $statement->execute([$countryId]);
    $cities = $statement->fetchAll(PDO::FETCH_ASSOC);

    $statement = $pdo->prepare(
        'SELECT * FROM `countryLanguages` 
        JOIN languages ON languageId = languages.id 
        WHERE countryId = ?'
    );
    $statement->execute([$countryId]);
    $languages = $statement->fetchAll(PDO::FETCH_ASSOC);

    echo compileTemplate('wrapper.phtml', [
        'content' => compileTemplate('countrySingle.phtml', [
            'country' => $country,
            'cities' => $cities,
            'languages' => $languages,
        ]),
        'isAuthorized' => true
    ]);
}

function countryListHandler()
{

    if (!isLoggedIn()) {

        echo compileTemplate("wrapper.phtml", [
            'content' => compileTemplate("subscriptionForm.phtml", [
                'info' => $_GET["info"] ?? "",
                'isRegistration' => isset($_GET['isRegistration']),
                'url' => getPathWithId($_SERVER["REQUEST_URI"])
            ]),
            'isAuthorized' => isLoggedIn(),
        ]);


        //'invalidCredentials' => isset($_GET["invalidCredentials"])
        return;
    }

    $pdo = getConnection();

    $statement = $pdo->prepare('SELECT * FROM countries');
    $statement->execute();
    $countries = $statement->fetchAll(PDO::FETCH_ASSOC);

    echo compileTemplate('wrapper.phtml', [
        'content' => compileTemplate('countryList.phtml', [
            'countries' => $countries
        ]),
        'isAuthorized' => true
    ]);
}

function singleCityHandler()
{

    if (!isLoggedIn()) {
        echo compileTemplate("wrapper.phtml", [
            'content' => compileTemplate("subscriptionForm.phtml", [
                'info' => $_GET["info"] ?? "",
                'isRegistration' => isset($_GET['isRegistration']),
                'url' => getPathWithId($_SERVER["REQUEST_URI"])
            ])
        ]);
        return;
    }

    $id = $_GET["id"];

    $pdo = getConnection();
    $statement = $pdo->prepare("SELECT * FROM `cities` WHERE id = ?");
    $statement->execute([$id]);
    $cities = $statement->fetchAll(PDO::FETCH_ASSOC);

    echo compileTemplate("wrapper.phtml", [
        "content" => compileTemplate("citySingle.phtml", [
            "cities" => $cities
        ]),
        'isAuthorized' => true
    ]);
}

function getConnection()
{
    return new PDO(
        'mysql:host=' . $_SERVER['DB_HOST'] . ';dbname=' . $_SERVER['DB_NAME'],
        $_SERVER['DB_USER'],
        $_SERVER['DB_PASSWORD']
    );
}

// https://kodbazis.hu/php-az-alapoktol/sablonok-szoftvertervezes-es-kompozicio
function compileTemplate($filePath, $params = []): string
{
    ob_start();
    require __DIR__ . "/views/" . $filePath;
    return ob_get_clean();
}

function notFoundHandler()
{
    echo "Oldal nem található";
}