<?php

namespace PROGAMERANYARAN\PHP\LOGIN\Model;

class UserPasswordUpdateRequest
{
    public ?string $id = null;
    public ?string $oldPassword = null;
    public ?string $newPassword = null;
}
