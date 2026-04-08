<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

/**
 * Describes the response for settlement queries.
 */
class QueryResponseSettlements extends JsonSerializableType
{
    /**
     * @var ?array<QueryResponseSettlementsRecordsItem> $records
     */
    #[JsonProperty('Records'), ArrayType([QueryResponseSettlementsRecordsItem::class])]
    public ?array $records;

    /**
     * @var ?QueryResponseSettlementsSummary $summary
     */
    #[JsonProperty('Summary')]
    public ?QueryResponseSettlementsSummary $summary;

    /**
     * @param array{
     *   records?: ?array<QueryResponseSettlementsRecordsItem>,
     *   summary?: ?QueryResponseSettlementsSummary,
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
