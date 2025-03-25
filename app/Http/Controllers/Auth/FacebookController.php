<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class FacebookController extends Controller
{
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $user = Socialite::driver('facebook')->user();
            $finduser = User::where('facebook_id', $user->id)->first();

            if ($finduser) {
                Auth::login($finduser);

                return redirect()->intended('home');
            } else {
                $first_name = explode(' ', $user->name);
                $last_name = array_pop($first_name);
                $first_name = implode(' ', $first_name);
                $username = str_replace(' ', '', $user->name);

                $newUser = User::firstOrNew(
                    [
                        'email' => $user->email,
                    ],
                    [
                        'uuid' => \App\Services\SlugService::generateSlug(),
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'username' => $username,
                        'email' => $user->email,
                        'email_verified_at' => now(),
                        'password' => encrypt('1!2@3#4$5%6^7&8*9(0)'), // TODO: Change this to a random password
                        'thumbnail' => $user->getAvatar(),
                        'profile' => $user->getAvatar(),
                    ]
                );
                $newUser->facebook_id = $user->id;
                $newUser->save();

                Auth::login($newUser);

                return redirect()->intended('home');
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
