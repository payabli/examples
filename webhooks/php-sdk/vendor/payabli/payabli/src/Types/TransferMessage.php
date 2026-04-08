<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class TransferMessage extends JsonSerializableType
{
    /**
     * @var ?int $id
     */
    #[JsonProperty('Id')]
    public ?int $id;

    /**
     * @var ?int $roomId
     */
    #[JsonProperty('RoomId')]
    public ?int $roomId;

    /**
     * @var ?int $userId
     */
    #[JsonProperty('UserId')]
    public ?int $userId;

    /**
     * @var ?string $userName
     */
    #[JsonProperty('UserName')]
    public ?string $userName;

    /**
     * @var ?string $content
     */
    #[JsonProperty('Content')]
    public ?string $content;

    /**
     * @var ?string $createdAt
     */
    #[JsonProperty('CreatedAt')]
    public ?string $createdAt;

    /**
     * @var ?int $messageType
     */
    #[JsonProperty('MessageType')]
    public ?int $messageType;

    /**
     * @var ?TransferMessageProperties $messageProperties
     */
    #[JsonProperty('MessageProperties')]
    public ?TransferMessageProperties $messageProperties;

    /**
     * @param array{
     *   id?: ?int,
     *   roomId?: ?int,
     *   userId?: ?int,
     *   userName?: ?string,
     *   content?: ?string,
     *   createdAt?: ?string,
     *   messageType?: ?int,
     *   messageProperties?: ?TransferMessageProperties,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->id = $values['id'] ?? null;
        $this->roomId = $values['roomId'] ?? null;
        $this->userId = $values['userId'] ?? null;
        $this->userName = $values['userName'] ?? null;
        $this->content = $values['content'] ?? null;
        $this->createdAt = $values['createdAt'] ?? null;
        $this->messageType = $values['messageType'] ?? null;
        $this->messageProperties = $values['messageProperties'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
