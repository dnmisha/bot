<?php namespace bot\src;

use \Bot;
use bot\object\Update;

$update = Bot::$update;
if ($update instanceof Update) {
    if ($update->hasMessage()) {
        $message = $update->getMessage();
        $chat_id = Bot::$chat->getId();

        Bot::$api->copyMessage($message)
            ->setChatId($chat_id)
            ->send();
    }
}