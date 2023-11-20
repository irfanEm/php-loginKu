<?php

namespace PROGAMERANYARAN\PHP\LOGIN\Service;

use PROGAMERANYARAN\PHP\LOGIN\Domain\Session;
use PROGAMERANYARAN\PHP\LOGIN\Domain\User;
use PROGAMERANYARAN\PHP\LOGIN\Repository\SessionRepository;
use PROGAMERANYARAN\PHP\LOGIN\Repository\UserRepository;

class SessionService
{
    public static string $COOKIE_NAME = "X-PRGANYARAN-SESSION";
    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;

    public function __construct(SessionRepository $sessionRepository, UserRepository $userRepository)
    {
        $this->sessionRepository = $sessionRepository;
        $this->userRepository = $userRepository;
    }

    public function create(string $userId): Session
    {
        $session = new Session();
        $session->id = uniqid();
        $session->userId = $userId;

        $this->sessionRepository->save($session);

        setcookie(self::$COOKIE_NAME, $session->id, time() + (3600 * 24), "/");

        return $session;
    }

    public function destroy()
    {
        $sessionId = $_COOKIE[self::$COOKIE_NAME] ?? '';
        $this->sessionRepository->findById($sessionId);
        setcookie(self::$COOKIE_NAME, '', 1, "/");
    }

    public function current(): ?User
    {
        $sessionId = $_COOKIE[self::$COOKIE_NAME] ?? '';
        $session = $this->sessionRepository->findById($sessionId);
        if($session == null){
            return null;
        }

        return $this->userRepository->findById($session->userId);
    }
}
