<?php

namespace PROGAMERANYARAN\PHP\LOGIN\Controller;

use PROGAMERANYARAN\PHP\LOGIN\App\View;
use PROGAMERANYARAN\PHP\LOGIN\Config\Database;
use PROGAMERANYARAN\PHP\LOGIN\Exception\ValidationException;
use PROGAMERANYARAN\PHP\LOGIN\Model\UserDaftarRequest;
use PROGAMERANYARAN\PHP\LOGIN\Model\UserLoginRequest;
use PROGAMERANYARAN\PHP\LOGIN\Model\UserProfileUpdateRequest;
use PROGAMERANYARAN\PHP\LOGIN\Repository\SessionRepository;
use PROGAMERANYARAN\PHP\LOGIN\Repository\UserRepository;
use PROGAMERANYARAN\PHP\LOGIN\Service\SessionService;
use PROGAMERANYARAN\PHP\LOGIN\Service\UserService;

class UserController
{
    private UserService $userService;
    private SessionService $sessionService;

    public function __construct()
    {
        $conn = Database::getConnection();
        $userRepository = new UserRepository($conn);
        $this->userService = new UserService($userRepository);

        $sessionRepository = new SessionRepository($conn);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }
    public function daftar()
    {
        View::view('User/daftar', [
            "title" => "Daftar user baru"
        ]);
    }

    public function postDaftar()
    {
        $request = new UserDaftarRequest();
        $request->id = $_POST['id'];
        $request->username = $_POST['username'];
        $request->password = $_POST['password'];

        try {
            $this->userService->daftar($request);
            View::redirect('/user/login');
        }catch(ValidationException $error){
            View::view('User/daftar', [
                'title' => 'Daftar user baru',
                'error' => $error->getMessage()
            ]);
        }
    }

    public function login()
    {
        View::view('User/login', [
            'title' => 'Login user'
        ]);
    }

    public function postLogin()
    {
        $request = new UserLoginRequest();
        $request->id = $_POST['id'];
        $request->password = $_POST['password'];

        try{
            $response = $this->userService->login($request);
            $this->sessionService->create($response->user->id);
            View::redirect('/');
        }catch(ValidationException $err){
            View::view('User/login',[
                'title' => 'Login user',
                'error' => $err->getMessage()
            ]);
        }
    }

    public function logout()
    {
        $this->sessionService->destroy();
        View::redirect('/');
    }

    public function updateProfile()
    {
        $user = $this->sessionService->current();

        View::view('User/profile', [
            'title' => 'Update profile user',
            'user' => [
                'id' => $user->id,
                'username' => $user->username
            ]
        ]);
    }

    public function postUpdateProfile()
    {
        $user = $this->sessionService->current();

        $request = new UserProfileUpdateRequest();
        $request->id = $_POST['id'];
        $request->username = $_POST['username'];

        try{
            $this->userService->updateProfile($request);
            View::redirect('/');
        }catch(ValidationException $err){
            View::view('User/profile', [
                'title' => 'Update profile user',
                'error' => $err->getMessage(),
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username
                ]
            ]);
        }
    }

    public function updatePassword()
    {
        $user = $this->sessionService->current();

        View::view('User/password', [
            'title' => 'Update password user',
            'user' => [
                'id' => $user->id
            ]
        ]);
    }

}
