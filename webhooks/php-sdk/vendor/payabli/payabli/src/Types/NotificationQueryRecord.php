<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use DateTime;
use Payabli\Core\Types\Date;

class NotificationQueryRecord extends JsonSerializableType
{
    /**
     * @var ?NotificationContent $content Notification content.
     */
    #[JsonProperty('content')]
    public ?NotificationContent $content;

    /**
     * @var ?DateTime $createdAt Timestamp of when notification was created.
     */
    #[JsonProperty('createdAt'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $createdAt;

    /**
     * @var ?value-of<Frequencynotification> $frequency
     */
    #[JsonProperty('frequency')]
    public ?string $frequency;

    /**
     * @var ?DateTime $lastUpdated Timestamp of when notification was last updated.
     */
    #[JsonProperty('lastUpdated'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $lastUpdated;

    /**
     * @var ?value-of<Methodnotification> $method
     */
    #[JsonProperty('method')]
    public ?string $method;

    /**
     * @var ?int $notificationId
     */
    #[JsonProperty('notificationId')]
    public ?int $notificationId;

    /**
     * @var ?string $ownerId
     */
    #[JsonProperty('ownerId')]
    public ?string $ownerId;

    /**
     * @var ?string $ownerName Name of entity owner of notification.
     */
    #[JsonProperty('ownerName')]
    public ?string $ownerName;

    /**
     * @var ?int $ownerType
     */
    #[JsonProperty('ownerType')]
    public ?int $ownerType;

    /**
     * @var ?string $source Custom descriptor of source of notification.
     */
    #[JsonProperty('source')]
    public ?string $source;

    /**
     * @var ?int $status
     */
    #[JsonProperty('status')]
    public ?int $status;

    /**
     * @var ?string $target
     */
    #[JsonProperty('target')]
    public ?string $target;

    /**
     * @param array{
     *   content?: ?NotificationContent,
     *   createdAt?: ?DateTime,
     *   frequency?: ?value-of<Frequencynotification>,
     *   lastUpdated?: ?DateTime,
     *   method?: ?value-of<Methodnotification>,
     *   notificationId?: ?int,
     *   ownerId?: ?string,
     *   ownerName?: ?string,
     *   ownerType?: ?int,
     *   source?: ?string,
     *   status?: ?int,
     *   target?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->content = $values['content'] ?? null;
        $this->createdAt = $values['createdAt'] ?? null;
        $this->frequency = $values['frequency'] ?? null;
        $this->lastUpdated = $values['lastUpdated'] ?? null;
        $this->method = $values['method'] ?? null;
        $this->notificationId = $values['notificationId'] ?? null;
        $this->ownerId = $values['ownerId'] ?? null;
        $this->ownerName = $values['ownerName'] ?? null;
        $this->ownerType = $values['ownerType'] ?? null;
        $this->source = $values['source'] ?? null;
        $this->status = $values['status'] ?? null;
        $this->target = $values['target'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
