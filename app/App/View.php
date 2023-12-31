<?php

namespace PROGAMERANYARAN\PHP\LOGIN\App;

class View
{
    public static function view(string $view, $model): void
    {
        require __DIR__ . "/../View/templates/header.php";
        require __DIR__ . "/../View/" . $view . ".php";
        require __DIR__ . "/../View/templates/footer.php";
    }

    public static function redirect(string $url)
    {
        header("Location: $url");
        if(getenv("mode") != "test")
        {
            exit();
        }
    }

}
