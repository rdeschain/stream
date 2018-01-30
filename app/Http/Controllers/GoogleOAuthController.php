<?php

namespace App\Http\Controllers;


use App\Broadcast;
use App\LiveChat;
use App\Stream;
use App\Token;
use App\User;
use Illuminate\Http\Request;

class GoogleOAuthController extends Controller
{

    public function callback(Request $request)
    {

        $client = new \Google_Client();
        $client->setAuthConfig(env('GOOGLE_CREDS'));
        $client->setRedirectUri('https://' . $request->getHttpHost());
        $accessToken = $client->fetchAccessTokenWithAuthCode($request->input('code'));
        $oauth = (new \Google_Service_Oauth2($client))->userinfo->get();

        /**
         * save basic profile data of user and log user in
         */

        $user = User::where('id', $oauth->getId())->first();

        if ($user === null) {

            $user = new User();
            $user->first = $oauth->getGivenName();
            $user->last = $oauth->getFamilyName();
            $user->id = $oauth->getId();
            $user->email = $oauth->getEmail();
            $user->save();
        }

        /**
         * save user id in session
         */
        $request->session()->forget('userid');
        $request->session()->put('userid', $user->id);

        /**
         * save or update existing token
         */

        $token = Token::where('user_id', $oauth->getId())->first();

        if ($token === null) {

            if (!isset($accessToken['refresh_token'])) {
                $client->revokeToken();
                return response()->json(['status' => 'error',
                                         'response' => ['code' => 1,
                                                        'message' => 'There was an issue with authentication. Please try again.']]);
            }

            $token = new Token();
            $token->user_id = $oauth->getId();
            $token->refresh_token = $accessToken['refresh_token'];

        } else {

            $refreshToken = $token->refresh_token;

            //make sure to save a refresh token is available. could happen if user revoked access
            if (isset($accessToken['refresh_token'])) {
                $token->refresh_token = $refreshToken = $accessToken['refresh_token'];
            }

            $client->refreshToken($refreshToken);
            $accessToken = $client->getAccessToken();
        }

        $token->access_token = $accessToken['access_token'];
        $token->token_type = $accessToken['token_type'];
        $token->expires_in = $accessToken['expires_in'];
        $token->id_token = $accessToken['id_token'];
        $token->created = $accessToken['created'];
        $token->save();

        return response()->json(['status' => 'success',
                                 'response' => ['code' => 0,
                                                'messages' => '']]);
    }

}