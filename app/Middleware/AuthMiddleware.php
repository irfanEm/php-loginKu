<?php

namespace PROGAMERANYARAN\PHP\LOGIN\Middleware;

class AuthMiddleware implements Middleware
{
    public function cek(): void
    {
        session_start();
        if(!isset($_SESSION['user'])){
            header('Location: /login');
            exit();
        }
    }

}
