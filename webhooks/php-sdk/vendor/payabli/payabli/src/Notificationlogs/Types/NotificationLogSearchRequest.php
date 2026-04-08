<?php

namespace Payabli\Notificationlogs\Types;

use Payabli\Core\Json\JsonSerializableType;
use DateTime;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\Date;

class NotificationLogSearchRequest extends JsonSerializableType
{
    /**
     * @var DateTime $startDate The start date for the search.
     */
    #[JsonProperty('startDate'), Date(Date::TYPE_DATETIME)]
    public DateTime $startDate;

    /**
     * @var DateTime $endDate The end date for the search.
     */
    #[JsonProperty('endDate'), Date(Date::TYPE_DATETIME)]
    public DateTime $endDate;

    /**
     * @var ?string $notificationEvent The type of notification event to filter by.
     */
    #[JsonProperty('notificationEvent')]
    public ?string $notificationEvent;

    /**
     * @var ?bool $succeeded Indicates whether the notification was successful.
     */
    #[JsonProperty('succeeded')]
    public ?bool $succeeded;

    /**
     * @var ?int $orgId The ID of the organization to filter by.
     */
    #[JsonProperty('orgId')]
    public ?int $orgId;

    /**
     * @var ?int $paypointId The ID of the paypoint to filter by.
     */
    #[JsonProperty('paypointId')]
    public ?int $paypointId;

    /**
     * @param array{
     *   startDate: DateTime,
     *   endDate: DateTime,
     *   notificationEvent?: ?string,
     *   succeeded?: ?bool,
     *   orgId?: ?int,
     *   paypointId?: ?int,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->startDate = $values['startDate'];
        $this->endDate = $values['endDate'];
        $this->notificationEvent = $values['notificationEvent'] ?? null;
        $this->succeeded = $values['succeeded'] ?? null;
        $this->orgId = $values['orgId'] ?? null;
        $this->paypointId = $values['paypointId'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
