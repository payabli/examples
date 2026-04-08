<?php

namespace Payabli\Notificationlogs\Requests;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Notificationlogs\Types\NotificationLogSearchRequest;

class SearchNotificationLogsRequest extends JsonSerializableType
{
    /**
     * @var ?int $pageSize
     */
    public ?int $pageSize;

    /**
     * @var ?int $skip The number of records to skip before starting to collect the result set.
     */
    public ?int $skip;

    /**
     * @var NotificationLogSearchRequest $body
     */
    public NotificationLogSearchRequest $body;

    /**
     * @param array{
     *   body: NotificationLogSearchRequest,
     *   pageSize?: ?int,
     *   skip?: ?int,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->pageSize = $values['pageSize'] ?? null;
        $this->skip = $values['skip'] ?? null;
        $this->body = $values['body'];
    }
}
