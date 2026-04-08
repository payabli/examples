<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

/**
 * Response for line item queries
 */
class QueryResponseItems extends JsonSerializableType
{
    /**
     * @var ?array<QueryResponseItemsRecordsItem> $records
     */
    #[JsonProperty('Records'), ArrayType([QueryResponseItemsRecordsItem::class])]
    public ?array $records;

    /**
     * @var ?QuerySummary $summary
     */
    #[JsonProperty('Summary')]
    public ?QuerySummary $summary;

    /**
     * @param array{
     *   records?: ?array<QueryResponseItemsRecordsItem>,
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
