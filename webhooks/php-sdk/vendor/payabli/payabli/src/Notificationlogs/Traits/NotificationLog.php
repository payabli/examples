<?php

namespace Payabli\Notificationlogs\Traits;

use DateTime;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\Date;

/**
 * @property string $id
 * @property ?int $orgId
 * @property ?int $paypointId
 * @property ?string $notificationEvent
 * @property ?string $target
 * @property ?string $responseStatus
 * @property bool $success
 * @property ?string $jobData
 * @property DateTime $createdDate
 * @property ?DateTime $successDate
 * @property ?DateTime $lastFailedDate
 * @property bool $isInProgress
 */
trait NotificationLog
{
    /**
     * @var string $id The unique identifier for the notification.
     */
    #[JsonProperty('id')]
    public string $id;

    /**
     * @var ?int $orgId The ID of the organization that the notification belongs to.
     */
    #[JsonProperty('orgId')]
    public ?int $orgId;

    /**
     * @var ?int $paypointId The ID of the paypoint that the notification is related to.
     */
    #[JsonProperty('paypointId')]
    public ?int $paypointId;

    /**
     * @var ?string $notificationEvent The event that triggered the notification.
     */
    #[JsonProperty('notificationEvent')]
    public ?string $notificationEvent;

    /**
     * @var ?string $target The target URL for the notification.
     */
    #[JsonProperty('target')]
    public ?string $target;

    /**
     * @var ?string $responseStatus The HTTP response status of the notification.
     */
    #[JsonProperty('responseStatus')]
    public ?string $responseStatus;

    /**
     * @var bool $success Indicates whether the notification was successful.
     */
    #[JsonProperty('success')]
    public bool $success;

    /**
     * @var ?string $jobData Contains the body of the notification.
     */
    #[JsonProperty('jobData')]
    public ?string $jobData;

    /**
     * @var DateTime $createdDate The date and time when the notification was created.
     */
    #[JsonProperty('createdDate'), Date(Date::TYPE_DATETIME)]
    public DateTime $createdDate;

    /**
     * @var ?DateTime $successDate The date and time when the notification was successfully delivered.
     */
    #[JsonProperty('successDate'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $successDate;

    /**
     * @var ?DateTime $lastFailedDate The date and time when the notification last failed.
     */
    #[JsonProperty('lastFailedDate'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $lastFailedDate;

    /**
     * @var bool $isInProgress Indicates whether the notification is currently in progress.
     */
    #[JsonProperty('isInProgress')]
    public bool $isInProgress;
}
