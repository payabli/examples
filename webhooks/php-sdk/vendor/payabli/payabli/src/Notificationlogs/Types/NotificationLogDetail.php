<?php

namespace Payabli\Notificationlogs\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Notificationlogs\Traits\NotificationLog;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;
use DateTime;

class NotificationLogDetail extends JsonSerializableType
{
    use NotificationLog;

    /**
     * @var ?array<StringStringKeyValuePair> $webHeaders
     */
    #[JsonProperty('webHeaders'), ArrayType([StringStringKeyValuePair::class])]
    public ?array $webHeaders;

    /**
     * @var ?array<KeyValueArray> $responseHeaders
     */
    #[JsonProperty('responseHeaders'), ArrayType([KeyValueArray::class])]
    public ?array $responseHeaders;

    /**
     * @var ?string $responseContent
     */
    #[JsonProperty('responseContent')]
    public ?string $responseContent;

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
     *   webHeaders?: ?array<StringStringKeyValuePair>,
     *   responseHeaders?: ?array<KeyValueArray>,
     *   responseContent?: ?string,
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
        $this->webHeaders = $values['webHeaders'] ?? null;
        $this->responseHeaders = $values['responseHeaders'] ?? null;
        $this->responseContent = $values['responseContent'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
