<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

class LinkRow extends JsonSerializableType
{
    /**
     * @var ?array<LinkData> $columns
     */
    #[JsonProperty('columns'), ArrayType([LinkData::class])]
    public ?array $columns;

    /**
     * @param array{
     *   columns?: ?array<LinkData>,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->columns = $values['columns'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
