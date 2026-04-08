<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

class TransferQueryResponse extends JsonSerializableType
{
    /**
     * @var array<Transfer> $records
     */
    #[JsonProperty('Records'), ArrayType([Transfer::class])]
    public array $records;

    /**
     * @var TransferSummary $summary
     */
    #[JsonProperty('Summary')]
    public TransferSummary $summary;

    /**
     * @param array{
     *   records: array<Transfer>,
     *   summary: TransferSummary,
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
