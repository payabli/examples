<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

/**
 * Response body for queries about money out batches.
 */
class QueryBatchesOutResponse extends JsonSerializableType
{
    /**
     * @var array<QueryBatchesOutResponseRecordsItem> $records
     */
    #[JsonProperty('Records'), ArrayType([QueryBatchesOutResponseRecordsItem::class])]
    public array $records;

    /**
     * @var BatchSummary $summary
     */
    #[JsonProperty('Summary')]
    public BatchSummary $summary;

    /**
     * @param array{
     *   records: array<QueryBatchesOutResponseRecordsItem>,
     *   summary: BatchSummary,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->records = $values['records'];
        $this->summary = $values['summary'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
