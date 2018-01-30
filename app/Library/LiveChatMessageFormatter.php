<?php

namespace App\Library;


use Illuminate\Support\Collection;

class LiveChatMessageFormatter
{

    public static function UserChatList($data)
    {
        $list = '';
        foreach ($data as $message) {

            $list .= $message->author . ': ' . $message->text . "<br>";
        }

        return $list;

    }

    public static function UserNameOnlyList(Collection $data)
    {
        $list = '';
        foreach ($data as $message) {

            $list .= '<li role="presentation"><a href="#userMessages">' . $message->author . '</a></li>';
        }

        return $list;

    }

    public static function UserChatOnlyList($data)
    {
        $list = '';
        foreach ($data as $message) {

            //format time before using this
            //$list .= $message->published_at .': ' . $message->text . "<br>";
            $list .= $message->text . "<br>";
        }

        return $list;

    }

}