<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function redirect($provider)
    {
        $scope = ['https://mail.google.com/'];
        return Socialite::driver($provider)->scopes($scope)->with(['access_type' => 'offline'])->redirect();
    }
    public function Callback($provider)
    {
        $userSocial = Socialite::driver('google')->stateless()->user();
        User::where('email', $userSocial->getEmail())->update(['token' => $userSocial->token]);

        $user = User::where(['email' => $userSocial->getEmail()])->whereNull('refreshToken')->first();

        $users = User::where(['email' => $userSocial->getEmail()])->first();
        if ($user) {
            User::where('email', $userSocial->getEmail())
                ->update(['refreshToken' => $userSocial->refreshToken, 'token' => $userSocial->token, 'provider' => $provider,
                ]);
        } 
        elseif ($users) {
            $loginDetails = ['user' => $userSocial->token, 'email' => $userSocial->getEmail(), 'label' => 'null'];
            $labels = getGmailList($loginDetails);

            function create($labels)
            {
                $inputs = $labels;
                foreach ($inputs as $input) {
                    foreach ($input as $input) {
                        // dd($input);
                        $test = DB::table('labels')->where('id_', $input['id'])->first();
                        if (!$test) {
                            DB::table('labels')->insert(array('id_' => $input['id'], 'name' => $input['name'], 'message_list_visibility' => $input['messageistVisibility'] ?? '', 'label_list_visibility' => $input['labelListVisibility'] ?? '', 'type' => $input['type']));}
                    }
                }
            }
            create($labels);
            // dd("i m here");
            return redirect()->route('label.index');
        } else {
            $user = User::create([
                'name' => $userSocial->getName(),
                'email' => $userSocial->getEmail(),
                'image' => $userSocial->getAvatar(),
                'provider_id' => $userSocial->getId(),
                'provider' => $provider,
                'token' => $userSocial->token,
                'refreshToken' => $userSocial->refreshToken,
            ]);
            return redirect()->route('home');
        }
    }

}
