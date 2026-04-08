<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * Subscription query response body.
 */
class QuerySubscriptionResponse extends JsonSerializableType
{
    /**
     * @var ?SubscriptionQueryRecords $records
     */
    #[JsonProperty('Records')]
    public ?SubscriptionQueryRecords $records;

    /**
     * @var ?QuerySummary $summary
     */
    #[JsonProperty('Summary')]
    public ?QuerySummary $summary;

    /**
     * @param array{
     *   records?: ?SubscriptionQueryRecords,
     *   summary?: ?QuerySummary,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->records = $values['records'] ?? null;
        $this->summary = $values['summary'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
