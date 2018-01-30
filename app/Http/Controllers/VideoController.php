<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\DB;

class VideoController extends Controller
{
    public function getVideoSearch(Request $request)
    {
        $client = new \Google_Client();
        $client->setDeveloperKey(env('GOOGLE_DEVELOPER_KEY'));
        $youtube = new \Google_Service_YouTube($client);

        //do some search based on qry
        $res = $youtube->search->listSearch('snippet', array('q' => $request->input('q'),
                                                             'eventType' => 'live',
                                                             'type' => 'video',
                                                             'maxResults' => 50));

        $list = '<option disabled selected>Select Stream</option>';
        foreach ($res->items as $video) {
            $list .= '<option value = "' . $video->id->videoId . '">' . $video->snippet->channelTitle . ': ' . $video->snippet->title . '</option>';
        }

        return response()->json(['status' => 'success',
                                 'response' => ['code' => 0,
                                                'messages' => $list]]);

    }

    public function getVideoStream(Request $request)
    {
        $client = new \Google_Client();
        $client->setDeveloperKey(env('GOOGLE_DEVELOPER_KEY'));
        $youtube = new \Google_Service_YouTube($client);
        $res = $youtube->videos->listVideos('snippet,liveStreamingDetails', array('id' => $request->route('id')));

        return response()->json(['status' => 'success',
                                 'response' => ['code' => 0,
                                                'liveChatId' => $res->items[0]->liveStreamingDetails->activeLiveChatId,
                                                'messages' => '']]);
    }

}