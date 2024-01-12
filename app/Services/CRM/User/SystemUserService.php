<?php

namespace App\Services\CRM\User;

use App\Exceptions\GeneralException;
use App\Models\Core\Auth\User;
use App\Services\Core\Auth\UserService;

class SystemUserService extends UserService
{

    public function delete(User $user)
    {
        if (($this->model->isAppAdmin() && $this->checkNoMoreAdmin()) && !$user->isInvited())
            throw new GeneralException(trans('default.action_not_allowed'));

        if ($user->id == auth()->id())
            throw new GeneralException(trans('default.cant_delete_own_account'));

        return $user->forceDelete();
    }

    public function checkNoMoreAdmin() : bool
    {
        return User::allAdmin() <= 1;
    }
}

