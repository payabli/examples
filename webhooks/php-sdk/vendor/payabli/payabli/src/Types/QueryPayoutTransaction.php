<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

class QueryPayoutTransaction extends JsonSerializableType
{
    /**
     * @var ?array<QueryPayoutTransactionRecordsItem> $records
     */
    #[JsonProperty('Records'), ArrayType([QueryPayoutTransactionRecordsItem::class])]
    public ?array $records;

    /**
     * @var ?QueryPayoutTransactionSummary $summary
     */
    #[JsonProperty('Summary')]
    public ?QueryPayoutTransactionSummary $summary;

    /**
     * @param array{
     *   records?: ?array<QueryPayoutTransactionRecordsItem>,
     *   summary?: ?QueryPayoutTransactionSummary,
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
