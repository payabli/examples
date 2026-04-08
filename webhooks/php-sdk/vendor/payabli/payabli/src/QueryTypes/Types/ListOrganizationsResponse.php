<?php

namespace Payabli\QueryTypes\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Types\OrganizationQueryRecord;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;
use Payabli\Types\QuerySummary;

class ListOrganizationsResponse extends JsonSerializableType
{
    /**
     * @var array<OrganizationQueryRecord> $records
     */
    #[JsonProperty('Records'), ArrayType([OrganizationQueryRecord::class])]
    public array $records;

    /**
     * @var QuerySummary $summary
     */
    #[JsonProperty('Summary')]
    public QuerySummary $summary;

    /**
     * @param array{
     *   records: array<OrganizationQueryRecord>,
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
