<?php namespace bot\src;

use \Bot;
use bot\InputFile;
use bot\object\Chat;
use bot\object\Error;

// set chat_id in bot properties
if (Bot::$chat instanceof Chat) {
    $chat_id = Bot::$chat->getId();
    Bot::set('chat_id', $chat_id);
}

// start
Bot::text('/start', function ($chat_id) {

    $caption = Bot::t('start_message', [
        'name' => Bot::$user->getFirstName()
    ]);

    $path = '@bot/files/photo/start.jpg';
    $file = new InputFile($path);

    $res = Bot::$api->sendPhoto()
        ->setCaption($caption)
        ->setChatId($chat_id)
        ->setPhoto($file)
        ->send();

    if ($res instanceof Error) {
        // request failed
        Bot::error($res->getDescription());
    }

});