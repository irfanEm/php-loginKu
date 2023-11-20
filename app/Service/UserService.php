<?php

namespace PROGAMERANYARAN\PHP\LOGIN\Service;

use Exception;
use PROGAMERANYARAN\PHP\LOGIN\Config\Database;
use PROGAMERANYARAN\PHP\LOGIN\Domain\User;
use PROGAMERANYARAN\PHP\LOGIN\Exception\ValidationException;
use PROGAMERANYARAN\PHP\LOGIN\Model\UserDaftarRequest;
use PROGAMERANYARAN\PHP\LOGIN\Model\UserDaftarResponse;
use PROGAMERANYARAN\PHP\LOGIN\Model\UserLoginRequest;
use PROGAMERANYARAN\PHP\LOGIN\Model\UserLoginResponse;
use PROGAMERANYARAN\PHP\LOGIN\Model\UserPasswordUpdateRequest;
use PROGAMERANYARAN\PHP\LOGIN\Model\UserPasswordUpdateResponse;
use PROGAMERANYARAN\PHP\LOGIN\Model\UserProfileUpdateRequest;
use PROGAMERANYARAN\PHP\LOGIN\Model\UserProfileUpdateResponse;
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

    private function validateUserLoginRequest(UserLoginRequest $request)
    {
        if($request->id == null || $request->password == null || 
        trim($request->id) == '' || trim($request->password) == '')
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
            Database::rollback();
            throw $err;
        }
    }

    public function login(UserLoginRequest $request): UserLoginResponse
    {
        $this->validateUserLoginRequest($request);

        $user = $this->userRepository->findById($request->id);
        if($user == null){
            throw new ValidationException("Username atau password salah!");
        }

        if(password_verify($request->password, $user->password))
        {
            $response = new UserLoginResponse();
            $response->user = $user;
            return $response;
        }else{
            throw new ValidationException("Username atau password salah!");
        }
    }

    public function updateProfile(UserProfileUpdateRequest $request): UserProfileUpdateResponse
    {
        $this->validateUserProfilUpdateRequest($request);

        try{
            Database::beginTransaction();

            $user = $this->userRepository->findById($request->id);
            if($user == null)
            {
                throw new ValidationException("User tidak ditemukan!");
            }

            $user->username = $request->username;
            $this->userRepository->update($user);

            Database::commit();

            $response = new UserProfileUpdateResponse();
            $response->user = $user;
            return $response;

        }catch(\Exception $error){
            Database::rollback();
            throw $error;
        }
    }

    private function validateUserProfilUpdateRequest(UserProfileUpdateRequest $request)
    {
        if($request->id == null || $request->username == null ||
          trim($request->id) == '' || trim($request->username) == '')
          {
            throw new ValidationException("Username Wajib diisi!");
          }
    }

    public function updatePassword(UserPasswordUpdateRequest $request): UserPasswordUpdateResponse
    {
        $this->validationUserPasswordUpdateRequest($request);

        try{
            Database::beginTransaction();

            $user = $this->userRepository->findById($request->id);
            if($user == null)
            {
                throw new ValidationException("User tidak ditemukan!");
            }

            if(!password_verify($request->oldPassword, $user->password))
            {
                throw new ValidationException("Password lama salah!");
            }

            $user->password = password_hash($request->newPassword, PASSWORD_BCRYPT);
            $this->userRepository->update($user);

            Database::commit();

            $response = new UserPasswordUpdateResponse();
            $response->user = $user;
            return $response;
            
        }catch(\Exception $error){

            Database::rollback();
            throw $error;

        }
    }

    private function validationUserPasswordUpdateRequest(UserPasswordUpdateRequest $request)
    {
        if($request->id == null || $request->oldPassword == null || $request->newPassword == null ||
        trim($request->id) == '' || trim($request->oldPassword) == '' || trim($request->newPassword) == '')
        {
            throw new ValidationException("Id, Password lama dan Password baru tidak boleh kosong!");
        }
    }
}
