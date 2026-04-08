<?php

namespace Payabli\Notificationlogs\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use DateTime;
use Payabli\Core\Types\Date;

class NotificationLog extends JsonSerializableType
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

    /**
     * @param array{
     *   id: string,
     *   success: bool,
     *   createdDate: DateTime,
     *   isInProgress: bool,
     *   orgId?: ?int,
     *   paypointId?: ?int,
     *   notificationEvent?: ?string,
     *   target?: ?string,
     *   responseStatus?: ?string,
     *   jobData?: ?string,
     *   successDate?: ?DateTime,
     *   lastFailedDate?: ?DateTime,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->id = $values['id'];
        $this->orgId = $values['orgId'] ?? null;
        $this->paypointId = $values['paypointId'] ?? null;
        $this->notificationEvent = $values['notificationEvent'] ?? null;
        $this->target = $values['target'] ?? null;
        $this->responseStatus = $values['responseStatus'] ?? null;
        $this->success = $values['success'];
        $this->jobData = $values['jobData'] ?? null;
        $this->createdDate = $values['createdDate'];
        $this->successDate = $values['successDate'] ?? null;
        $this->lastFailedDate = $values['lastFailedDate'] ?? null;
        $this->isInProgress = $values['isInProgress'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
