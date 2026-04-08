<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use DateTime;
use Payabli\Core\Types\Date;

class ApplicationDetailsRecordMessagesItem extends JsonSerializableType
{
    /**
     * @var ?string $content
     */
    #[JsonProperty('content')]
    public ?string $content;

    /**
     * @var ?DateTime $createdAt
     */
    #[JsonProperty('createdAt'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $createdAt;

    /**
     * @var ?int $currentApplicationStatus
     */
    #[JsonProperty('currentApplicationStatus')]
    public ?int $currentApplicationStatus;

    /**
     * @var ?int $currentApplicationSubStatus
     */
    #[JsonProperty('currentApplicationSubStatus')]
    public ?int $currentApplicationSubStatus;

    /**
     * @var ?int $id
     */
    #[JsonProperty('id')]
    public ?int $id;

    /**
     * @var ?int $messageType
     */
    #[JsonProperty('messageType')]
    public ?int $messageType;

    /**
     * @var ?int $originalApplicationStatus
     */
    #[JsonProperty('originalApplicationStatus')]
    public ?int $originalApplicationStatus;

    /**
     * @var ?int $originalApplicationSubStatus
     */
    #[JsonProperty('originalApplicationSubStatus')]
    public ?int $originalApplicationSubStatus;

    /**
     * @var ?int $roomId
     */
    #[JsonProperty('roomId')]
    public ?int $roomId;

    /**
     * @var ?int $userId
     */
    #[JsonProperty('userId')]
    public ?int $userId;

    /**
     * @var ?string $userName
     */
    #[JsonProperty('userName')]
    public ?string $userName;

    /**
     * @param array{
     *   content?: ?string,
     *   createdAt?: ?DateTime,
     *   currentApplicationStatus?: ?int,
     *   currentApplicationSubStatus?: ?int,
     *   id?: ?int,
     *   messageType?: ?int,
     *   originalApplicationStatus?: ?int,
     *   originalApplicationSubStatus?: ?int,
     *   roomId?: ?int,
     *   userId?: ?int,
     *   userName?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->content = $values['content'] ?? null;
        $this->createdAt = $values['createdAt'] ?? null;
        $this->currentApplicationStatus = $values['currentApplicationStatus'] ?? null;
        $this->currentApplicationSubStatus = $values['currentApplicationSubStatus'] ?? null;
        $this->id = $values['id'] ?? null;
        $this->messageType = $values['messageType'] ?? null;
        $this->originalApplicationStatus = $values['originalApplicationStatus'] ?? null;
        $this->originalApplicationSubStatus = $values['originalApplicationSubStatus'] ?? null;
        $this->roomId = $values['roomId'] ?? null;
        $this->userId = $values['userId'] ?? null;
        $this->userName = $values['userName'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
