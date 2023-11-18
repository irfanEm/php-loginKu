<?php

namespace PROGAMERANYARAN\PHP\LOGIN\Controller;
use PROGAMERANYARAN\PHP\LOGIN\App\View;

class HomeController
{
    public function index()
    {
        View::view('Home/index',[
            'title' => 'Beranda'
        ]);
    }

    public function contoh()
    {
        echo "HomeController : contoh()";
    }

    public function tentang()
    {
        echo "author : Progammer Anyaran Cilacap.";
    }
}
