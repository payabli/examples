<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

/**
 * Response payload for queries related to vendors.
 */
class QueryResponseVendors extends JsonSerializableType
{
    /**
     * @var ?array<VendorQueryRecord> $records
     */
    #[JsonProperty('Records'), ArrayType([VendorQueryRecord::class])]
    public ?array $records;

    /**
     * @var ?QuerySummary $summary
     */
    #[JsonProperty('Summary')]
    public ?QuerySummary $summary;

    /**
     * @param array{
     *   records?: ?array<VendorQueryRecord>,
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
