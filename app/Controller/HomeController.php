<?php

namespace PROGAMERANYARAN\PHP\LOGIN\Controller;
use PROGAMERANYARAN\PHP\LOGIN\App\View;
use PROGAMERANYARAN\PHP\LOGIN\Config\Database;
use PROGAMERANYARAN\PHP\LOGIN\Repository\SessionRepository;
use PROGAMERANYARAN\PHP\LOGIN\Repository\UserRepository;
use PROGAMERANYARAN\PHP\LOGIN\Service\SessionService;

class HomeController
{
    private SessionService $sessionService;

    public function __construct()
    {
        $connection = Database::getConnection();
        $sessionRepository = new SessionRepository($connection);
        $userRepository = new UserRepository($connection);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }
    public function index()
    {
        $user = $this->sessionService->current();
        if($user == null) {
            View::view('home/index',[
                'title' => 'PHP Login'
            ]);
        }else{
            View::view('home/index',[
                'title' => 'Beranda PHP Login',
                'user' => [
                    'name' => $user->username
                ]
            ]);
        }
    }
}
