<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

class VCardQueryResponse extends JsonSerializableType
{
    /**
     * @var ?VCardSummary $summary
     */
    #[JsonProperty('Summary')]
    public ?VCardSummary $summary;

    /**
     * @var ?array<VCardRecord> $records
     */
    #[JsonProperty('Records'), ArrayType([VCardRecord::class])]
    public ?array $records;

    /**
     * @param array{
     *   summary?: ?VCardSummary,
     *   records?: ?array<VCardRecord>,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->summary = $values['summary'] ?? null;
        $this->records = $values['records'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
