<?php

namespace Payabli\Invoice\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;
use Payabli\Types\QuerySummary;

class QueryInvoiceResponse extends JsonSerializableType
{
    /**
     * @var array<QueryInvoiceResponseRecordsItem> $records
     */
    #[JsonProperty('Records'), ArrayType([QueryInvoiceResponseRecordsItem::class])]
    public array $records;

    /**
     * @var QuerySummary $summary
     */
    #[JsonProperty('Summary')]
    public QuerySummary $summary;

    /**
     * @param array{
     *   records: array<QueryInvoiceResponseRecordsItem>,
     *   summary: QuerySummary,
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
