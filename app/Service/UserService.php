<?php

namespace PROGAMERANYARAN\PHP\LOGIN\Service;

use Exception;
use PROGAMERANYARAN\PHP\LOGIN\Config\Database;
use PROGAMERANYARAN\PHP\LOGIN\Domain\User;
use PROGAMERANYARAN\PHP\LOGIN\Exception\ValidationException;
use PROGAMERANYARAN\PHP\LOGIN\Model\UserDaftarRequest;
use PROGAMERANYARAN\PHP\LOGIN\Model\UserDaftarResponse;
use PROGAMERANYARAN\PHP\LOGIN\Repository\UserRepository;

class UserService
{
    private UserRepository $userRepository;

    /**
     * Class constructor.
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    private function validateUserRegistrationRequest(UserDaftarRequest $request)
    {
        if($request->id == null || $request->username == null || $request->password == null || trim($request->id) == '' || trim($request->username) == '' || trim($request->password) == '')
        {
            throw new ValidationException("id, username dan password tidak boleh kosong!.");
        }
    }

    public function daftar(UserDaftarRequest $request): UserDaftarResponse
    {
        $this->validateUserRegistrationRequest($request);

        try{
            Database::beginTransaction();
            $user = $this->userRepository->findById($request->id);
            if($user == null){
                throw new ValidationException("User Id sudah ada!");
            }

            $user = new User();
            $user->id = $request->id;
            $user->username = $request->username;
            $user->password = password_hash($request->password, PASSWORD_BCRYPT);

            $this->userRepository->save($user);

            $response = new UserDaftarResponse();
            $response->user = $user;

            Database::commit();

            return $response;

        }catch(Exception $err){

        }
    }

}
