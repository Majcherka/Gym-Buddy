<?php

class MessageFactory {
    public static function create(int $senderId, int $receiverId, string $content): Message {
        $message = new Message();
        $message->senderId = $senderId;
        $message->receiverId = $receiverId;
        $message->content = $content;
        return $message;
    }
}
