<?php

namespace App\Http\Controllers;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth ;

class SocialController extends Controller
{ 
        public function redirect($provider)
        {
            $scope = ['https://www.googleapis.com/auth/gmail.modify', 'https://mail.google.com/'];  
            return Socialite::driver($provider)->scopes($scope)->with(['access_type' => 'offline'])->redirect();
        }
        public function Callback($provider){
            $userSocial = Socialite::driver('google')->stateless()->user();
            $user = User::where(['email' => $userSocial->getEmail()])->whereNull('refreshToken')->first();
            $users = User::where(['email' => $userSocial->getEmail()])->first();
            if($user){
                User::where('email', $userSocial->getEmail())
                ->update(['refreshToken' => $userSocial->refreshToken , 'token' => $userSocial->token
                ]);
            }
            elseif($users){
                Auth::login($users);
                $loginDetails = ['user' => $userSocial->token , 'email' => $userSocial->getEmail()];
                return view('gmail.index' , compact('loginDetails'));
            }
            else{
                $user = User::create([
                    'name'          => $userSocial->getName(),
                    'email'         => $userSocial->getEmail(),
                    'image'         => $userSocial->getAvatar(),
                    'provider_id'   => $userSocial->getId(),
                    'provider'      => $provider,
                    'token'         => $userSocial->token,
                    'refreshToken' => $userSocial->refreshToken,
                 ]);
                return redirect()->route('home');
            }
        }
    
}
