<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

class TemplateQueryResponse extends JsonSerializableType
{
    /**
     * @var ?array<TemplateQueryRecord> $records
     */
    #[JsonProperty('records'), ArrayType([TemplateQueryRecord::class])]
    public ?array $records;

    /**
     * @var ?QuerySummary $summary
     */
    #[JsonProperty('summary')]
    public ?QuerySummary $summary;

    /**
     * @param array{
     *   records?: ?array<TemplateQueryRecord>,
     *   summary?: ?QuerySummary,
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
