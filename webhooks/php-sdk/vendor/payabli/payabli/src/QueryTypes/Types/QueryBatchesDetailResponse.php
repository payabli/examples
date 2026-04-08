<?php

namespace Payabli\QueryTypes\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

/**
 * Response body for queries about batch details.
 */
class QueryBatchesDetailResponse extends JsonSerializableType
{
    /**
     * @var array<BatchDetailResponseRecord> $records
     */
    #[JsonProperty('Records'), ArrayType([BatchDetailResponseRecord::class])]
    public array $records;

    /**
     * @var BatchDetailResponseSummary $summary
     */
    #[JsonProperty('Summary')]
    public BatchDetailResponseSummary $summary;

    /**
     * @param array{
     *   records: array<BatchDetailResponseRecord>,
     *   summary: BatchDetailResponseSummary,
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
