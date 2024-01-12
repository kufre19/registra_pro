<?php

namespace App\Http\Controllers\CRM\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\Auth\User\UserController as BaseUserController;
use App\Http\Requests\Core\Auth\User\UserRequest;
use App\Mail\UserCreateMail;
use App\Models\Core\Status;
use App\Models\CRM\User\User;
use App\Models\CRM\User\UserSocialProfileLink;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AppUserController extends BaseUserController
{
    public function index()
    {
        $existing_users = request()->has('existing') ? explode(',', request()->existing) : [];

        return $this->service
            ->filters($this->filter)
            ->whereHas('roles', function ($query) {
                $query->where('name', '!=', 'Client');
            })
            ->when(!count($existing_users), function (Builder $builder) {
                $builder->whereHas('status', function (Builder $builder) {
                    $builder->where('name', '!=', 'status_invited');
                });
            }, function (Builder $builder) use ($existing_users) {
                $builder->with('profilePicture')
                    ->whereNotIn('id', array_merge($existing_users, [auth()->id()]));
            })
            ->latest('id')
            ->get(['id', 'first_name', 'last_name', 'email']);
    }

    public function crmAuthUsers()
    {
        return User::whereHas('roles', function ($query) {
            $query->where('name', '!=', 'Client');
        }
        )->whereNotNull('first_name')
            ->whereNotNull('last_name')
            ->get();
    }

    public function crmLoggedAgentUser()
    {
        return User::query()->where('id', auth()->id())
        ->whereHas('roles', function ($query) {
            $query->where('name','Agent');

        })->whereNotNull('first_name')
            ->whereNotNull('last_name')
            ->first();
    }

    public function userSocialLink()
    {
        $result = UserSocialProfileLink::where('user_id', auth()->id())
            ->first();
        if ($result) {
            $result->toArray();
            $result = collect($result)->except(['id', 'user_id', 'created_at', 'updated_at']);
            $socialLink = [];
            $index = 1;
            foreach ($result as $key => $value) {
                array_push($socialLink, [
                    'id' => $index++,
                    'name' => Str::ucfirst($key),
                    'icon' => Str::slug($key, '-'),
                    'link' => $value,
                    'edit' => false
                ]);
            }
            return $socialLink;
        }

    }

    public function userSocialLinkUpdate(Request $request, $userId)
    {
        $social = UserSocialProfileLink::where('user_id', $userId)->count();
        if ($social > 0) {
            UserSocialProfileLink::where('user_id', $userId)
                ->update(array_change_key_case($request->all(), CASE_LOWER));
        } else {
            UserSocialProfileLink::where('user_id', $userId)
                ->updateOrCreate(array_change_key_case($request->all(), CASE_LOWER));
        }


        return updated_responses('social_links');

    }

    public function createUser(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            //"gender" => "required", //user->profile
            'roles' => ['nullable', 'array'],
        ]);

        $status = Status::findByNameAndType('status_active', 'user')->id;

        DB::transaction(function () use ($request, $status) {
            $password = Str::random(8);

            $newUser = $this->service
                ->save(array_merge(
                    $request->only('first_name', 'last_name', 'email'),
                    [
                        'password' => Hash::make($password),
                        'status_id'=> $status,
                        'created_by' => auth()->id()
                    ]
                ));

            //create profile
//            if ($request->gender){
//                $newUser->profile()->create(['gender'=> $request->gender]);
//            }

            //assign role
            $newUser->roles()->attach([$request->role]);

            $newUser->tempPass= $password;

            $this->createUserMail($newUser);
        });

        return created_responses('user');
    }

    public function createUserMail($user)
    {
        Mail::to($user)
//            ->locale(session()->get('locale') ?: settings('language') ?: "en")
            ->locale(session()->get('locale') ?: "en")
            ->send((new UserCreateMail($user))->onQueue('high')->delay(5));

        return $this;
    }
}
