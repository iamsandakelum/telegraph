<?php

namespace DefStudio\Telegraph\DTO;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, string|int|null|array>
 */
class SharedChat implements Arrayable
{
    private int $requestId;
    private int $chatId;
    private ?string $title = null;
    private ?string $username = null;
    /** @var Photo[]|null */
    private ?array $photo = null;

    private function __construct()
    {
    }

    /**
     * @param array{
     *     request_id: int,
     *     chat_id: int,
     *     title?: string,
     *     username?: string,
     *     photo?: array<array<string, mixed>>
     * } $data
     */
    public static function fromArray(array $data): SharedChat
    {
        $sharedChat = new self();

        $sharedChat->requestId = $data['request_id'];
        $sharedChat->chatId = $data['chat_id'];
        $sharedChat->title = $data['title'] ?? null;
        $sharedChat->username = $data['username'] ?? null;

        if (isset($data['photo'])) {
            $sharedChat->photo = array_map(
                fn(array $photoData) => Photo::fromArray($photoData),
                $data['photo']
            );
        }

        return $sharedChat;
    }

    public function requestId(): int
    {
        return $this->requestId;
    }

    public function chatId(): int
    {
        return $this->chatId;
    }

    public function title(): ?string
    {
        return $this->title;
    }

    public function username(): ?string
    {
        return $this->username;
    }

    /**
     * @return PhotoSize[]|null
     */
    public function photo(): ?array
    {
        return $this->photo;
    }

    public function toArray(): array
    {
        return array_filter([
            'request_id' => $this->requestId,
            'chat_id' => $this->chatId,
            'title' => $this->title,
            'username' => $this->username,
            'photo' => $this->photo ? array_map(fn(Photo $photo) => $photo->toArray(), $this->photo) : null,
        ], fn($value) => $value !== null);
    }
}
