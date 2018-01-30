<?php

namespace App\Http\Controllers;


use App\Library\LiveChatMessage;
use App\Library\LiveChatMessageFormatter;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\DB;

class LiveChatMessageController extends Controller
{

    public function retrieveMessage(Request $request)
    {

        $messageList = DB::table('live_chat_messages')->where('livemessage_id',
                                                                $request->route('id'))->orderBy('id', 'asc');

        //build qry
        switch ($request->input('type')) {

            case "names":
                $messageList->select('author')->distinct();
                $list = LiveChatMessageFormatter::UserNameOnlyList($messageList->get());
                break;

            case "name":
                $messageList->where('author', $request->input('author'))->select('text', 'published_at');
                $list = LiveChatMessageFormatter::UserChatOnlyList($messageList->get());
                break;

            default:
                $list = LiveChatMessageFormatter::UserChatList($messageList->get());
        }

        return response()->json(['status' => 'success',
                                 'response' => ['code' => 0,
                                                'messages' => $list]]);

    }

    public function postMessage(Request $request)
    {
        $client = LiveChatMessage::getAuthLiveChatClient();
        $youtube = new \Google_Service_YouTube($client);
        $liveChatMessage = new \Google_Service_YouTube_LiveChatMessage();
        $liveChatMessageSnippet = new \Google_Service_YouTube_LiveChatMessageSnippet();
        $liveChatTextMessageDetails = new \Google_Service_YouTube_LiveChatTextMessageDetails();

        try {

            $liveChatTextMessageDetails->setMessageText($request->input('message'));
            $liveChatMessageSnippet->setType('textMessageEvent');
            $liveChatMessageSnippet->setLiveChatId($request->route('id'));
            $liveChatMessageSnippet->setTextMessageDetails($liveChatTextMessageDetails);
            $liveChatMessage->setSnippet($liveChatMessageSnippet);

            $youtube->liveChatMessages->insert('snippet', $liveChatMessage);

            return response()->json(['status' => 'success',
                                     'response' => ['code' => 0,
                                                    'messages' => $request->input('message')]]);
        } catch(\Exception $e){

            return response()->json(['status' => 'error',
                                     'response' => ['code' => 0,
                                                    'messages' => $e->getMessage()]]);
        }
    }

    public function getMessage(Request $request)
    {

        $client = LiveChatMessage::getLiveChatClient();

        $youtube = new \Google_Service_YouTube($client);
        $messageList = $youtube->liveChatMessages->listLiveChatMessages($request->route('id'), 'snippet,authorDetails');

        /**
         * API does not have a way to NOT get all messages all the time. Optimize later and avoid re-saving duplicates with try catch
         * API messages are ordered oldest to newest
         */

        $list = '';
        foreach ($messageList->items as $message) {

            $list .= $message->authorDetails->displayName . ': ' . $message->snippet->displayMessage . "<br>";
            try {

                $liveChat = new \App\LiveChatMessage();
                $liveChat->message_id = $message->id;
                $liveChat->author = $message->authorDetails->displayName;
                $liveChat->text = $message->snippet->displayMessage;
                $liveChat->author_channel = $message->snippet->authorChannelId;
                $liveChat->published_at = $message->snippet->publishedAt;
                $liveChat->livemessage_id = $request->route('id');
                //$liveChat->broadcast_id = $request->route('broadcast_id');
                $liveChat->save();

            } catch (\Exception $e) {

            }
        }

        return response()->json(['status' => 'success',
                                 'response' => ['code' => 0,
                                                'messages' => $list,
                                                'liveChatId' => $request->route('id')]]);
    }

}