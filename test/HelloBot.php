<?php namespace bot\src;

use \Bot;
use bot\object\User;
use bot\object\Update;

$update = Bot::$update;
if ($update instanceof Update) {
    if (
        $update->hasMessage() &&
        Bot::$user instanceof User
    ) {
        $chat_id = Bot::$chat->getId();
        $text = 'Hello ' . Bot::$user->getFirstName();

        Bot::$api->sendMessage()
            ->setChatId($chat_id)
            ->setText($text)
            ->send();
    }
}