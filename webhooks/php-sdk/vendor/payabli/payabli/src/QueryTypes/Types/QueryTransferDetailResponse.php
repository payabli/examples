<?php

namespace Payabli\QueryTypes\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

class QueryTransferDetailResponse extends JsonSerializableType
{
    /**
     * @var array<TransferDetailRecord> $records List of transfer detail records
     */
    #[JsonProperty('Records'), ArrayType([TransferDetailRecord::class])]
    public array $records;

    /**
     * @var QueryTransferSummary $summary Summary of the transfer details query
     */
    #[JsonProperty('Summary')]
    public QueryTransferSummary $summary;

    /**
     * @param array{
     *   records: array<TransferDetailRecord>,
     *   summary: QueryTransferSummary,
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
