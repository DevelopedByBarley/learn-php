<?php

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
