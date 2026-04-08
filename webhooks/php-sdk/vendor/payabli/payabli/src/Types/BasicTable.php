<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

class BasicTable extends JsonSerializableType
{
    /**
     * @var ?array<LinkRow> $body
     */
    #[JsonProperty('body'), ArrayType([LinkRow::class])]
    public ?array $body;

    /**
     * @var ?LinkRow $header
     */
    #[JsonProperty('header')]
    public ?LinkRow $header;

    /**
     * @param array{
     *   body?: ?array<LinkRow>,
     *   header?: ?LinkRow,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->body = $values['body'] ?? null;
        $this->header = $values['header'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
