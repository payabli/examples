<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class PageElement extends JsonSerializableType
{
    /**
     * @var ?string $description Page description in header
     */
    #[JsonProperty('description')]
    public ?string $description;

    /**
     * @var ?bool $enabled
     */
    #[JsonProperty('enabled')]
    public ?bool $enabled;

    /**
     * @var ?string $header Page header
     */
    #[JsonProperty('header')]
    public ?string $header;

    /**
     * @var ?int $order
     */
    #[JsonProperty('order')]
    public ?int $order;

    /**
     * @param array{
     *   description?: ?string,
     *   enabled?: ?bool,
     *   header?: ?string,
     *   order?: ?int,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->description = $values['description'] ?? null;
        $this->enabled = $values['enabled'] ?? null;
        $this->header = $values['header'] ?? null;
        $this->order = $values['order'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
