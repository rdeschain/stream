<?php


namespace App\Library;

use App\Token;

class LiveChatMessage
{

    static public function getLiveChatClient()
    {
        static $client = null;

        if ($client === null) {

            $client = new \Google_Client();
            $client->setDeveloperKey(env('GOOGLE_DEVELOPER_KEY'));
            $youtube = new \Google_Service_YouTube($client);
        }

        return $client;
    }

    static public function getAuthLiveChatClient()
    {
        static $authClient = null;

        if ($authClient === null) {

            //this is needed to post to live chat. do not need to just fetch
            $authClient = new \Google_Client();
            $authClient->setAuthConfig(env('GOOGLE_CREDS'));
            $authClient->setRedirectUri(env('ENV_HOST'));
            $authClient->setAccessToken(['access_token' => Token::where('user_id', session('userid'))->value('access_token')]);
        }

        return $authClient;

    }

}