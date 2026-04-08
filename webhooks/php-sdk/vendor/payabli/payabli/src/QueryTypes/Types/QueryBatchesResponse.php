<?php

namespace Payabli\QueryTypes\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;
use Payabli\Types\BatchSummary;

/**
 * Response body for queries about batches.
 */
class QueryBatchesResponse extends JsonSerializableType
{
    /**
     * @var array<QueryBatchesResponseRecordsItem> $records
     */
    #[JsonProperty('Records'), ArrayType([QueryBatchesResponseRecordsItem::class])]
    public array $records;

    /**
     * @var BatchSummary $summary
     */
    #[JsonProperty('Summary')]
    public BatchSummary $summary;

    /**
     * @param array{
     *   records: array<QueryBatchesResponseRecordsItem>,
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
