<?php

namespace Payabli\QueryTypes\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Types\TransactionQueryRecords;
use Payabli\Core\Types\ArrayType;

class QueryTransferResponse extends JsonSerializableType
{
    /**
     * @var QueryTransferSummary $summary Summary information about the transfers.
     */
    #[JsonProperty('Summary')]
    public QueryTransferSummary $summary;

    /**
     * @var array<TransactionQueryRecords> $records List of transfer transaction records.
     */
    #[JsonProperty('Records'), ArrayType([TransactionQueryRecords::class])]
    public array $records;

    /**
     * @param array{
     *   summary: QueryTransferSummary,
     *   records: array<TransactionQueryRecords>,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->summary = $values['summary'];
        $this->records = $values['records'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
