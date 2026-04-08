<?php

namespace Payabli\ChargeBacks\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use DateTime;
use Payabli\Core\Types\Date;
use Payabli\Core\Types\ArrayType;

class ChargebackMessage extends JsonSerializableType
{
    /**
     * @var int $id Message identifier.
     */
    #[JsonProperty('Id')]
    public int $id;

    /**
     * @var int $roomId Room identifier for the message.
     */
    #[JsonProperty('RoomId')]
    public int $roomId;

    /**
     * @var int $userId User identifier who sent the message.
     */
    #[JsonProperty('UserId')]
    public int $userId;

    /**
     * @var string $userName Name of the user who sent the message.
     */
    #[JsonProperty('UserName')]
    public string $userName;

    /**
     * @var string $content Content of the message.
     */
    #[JsonProperty('Content')]
    public string $content;

    /**
     * @var DateTime $createdAt Timestamp when the message was created.
     */
    #[JsonProperty('CreatedAt'), Date(Date::TYPE_DATETIME)]
    public DateTime $createdAt;

    /**
     * @var int $messageType Type of message.
     */
    #[JsonProperty('MessageType')]
    public int $messageType;

    /**
     * @var ?array<string, string> $messageProperties Additional properties of the message.
     */
    #[JsonProperty('MessageProperties'), ArrayType(['string' => 'string'])]
    public ?array $messageProperties;

    /**
     * @param array{
     *   id: int,
     *   roomId: int,
     *   userId: int,
     *   userName: string,
     *   content: string,
     *   createdAt: DateTime,
     *   messageType: int,
     *   messageProperties?: ?array<string, string>,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->id = $values['id'];
        $this->roomId = $values['roomId'];
        $this->userId = $values['userId'];
        $this->userName = $values['userName'];
        $this->content = $values['content'];
        $this->createdAt = $values['createdAt'];
        $this->messageType = $values['messageType'];
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
