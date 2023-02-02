<?php

function compileTemplate($filePath, $params = []): string
{
    ob_start();
    require  dirname(__DIR__, 1) . "/views/" . $filePath;
    return ob_get_clean();
}
