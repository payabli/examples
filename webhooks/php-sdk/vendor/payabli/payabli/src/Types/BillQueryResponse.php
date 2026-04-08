<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

class BillQueryResponse extends JsonSerializableType
{
    /**
     * @var ?BillQueryResponseSummary $summary Summary statistics for the bill query response.
     */
    #[JsonProperty('Summary')]
    public ?BillQueryResponseSummary $summary;

    /**
     * @var ?array<BillQueryRecord2> $records Array of bill records returned by the query.
     */
    #[JsonProperty('Records'), ArrayType([BillQueryRecord2::class])]
    public ?array $records;

    /**
     * @param array{
     *   summary?: ?BillQueryResponseSummary,
     *   records?: ?array<BillQueryRecord2>,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->summary = $values['summary'] ?? null;
        $this->records = $values['records'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
