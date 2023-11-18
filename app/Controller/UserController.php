<?php

namespace PROGAMERANYARAN\PHP\LOGIN\Controller;

use PROGAMERANYARAN\PHP\LOGIN\App\View;
use PROGAMERANYARAN\PHP\LOGIN\Model\UserLoginRequest;

class UserController
{
    public function daftar()
    {
        View::view('User/daftar', [
            "title" => "Daftar"
        ]);
    }

    public function postDaftar()
    {
        
    }

}
