<?php

class Invitation
{
    public int $id;
    public int $senderId;
    public ?string $senderName = null;
    public ?int $senderAge = null;

    public int $receiverId;
    public ?string $receiverName = null;
    public ?int $receiverAge = null;

    public string $message;
    public string $gym;
    public string $status;
    public ?string $createdAt = null;

    /** @var string[] */
    public array $categories = [];

    /** @var string[] */
    public array $days = [];

    public static function fromArray(array $row): Invitation
    {
        $inv = new self();
        $inv->id = $row['invitation_id'];
        $inv->senderId = $row['sender_id'] ?? 0;
        $inv->senderName = $row['sender_name'] ?? null;
        $inv->senderAge = $row['sender_age'] ?? null;

        $inv->receiverId = $row['receiver_id'] ?? 0;
        $inv->receiverName = $row['receiver_name'] ?? null;
        $inv->receiverAge = $row['receiver_age'] ?? null;

        $inv->message = $row['message'];
        $inv->gym = $row['gym'];
        $inv->status = $row['status'] ?? 'pending';
        $inv->createdAt = $row['created_at'] ?? null;

        return $inv;
    }

    public function toArray(): array
    {
        return [
            'invitation_id' => $this->id,
            'sender_id' => $this->senderId,
            'sender_name' => $this->senderName,
            'sender_age' => $this->senderAge,
            'receiver_id' => $this->receiverId,
            'receiver_name' => $this->receiverName,
            'receiver_age' => $this->receiverAge,
            'message' => $this->message,
            'gym' => $this->gym,
            'status' => $this->status,
            'created_at' => $this->createdAt,
            'categories' => $this->categories,
            'days' => $this->days
        ];
    }
}