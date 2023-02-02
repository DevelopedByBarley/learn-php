<?php
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


