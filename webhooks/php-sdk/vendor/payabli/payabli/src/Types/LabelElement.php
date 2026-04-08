<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class LabelElement extends JsonSerializableType
{
    /**
     * @var ?bool $enabled
     */
    #[JsonProperty('enabled')]
    public ?bool $enabled;

    /**
     * @var ?string $label Label to display for section or element
     */
    #[JsonProperty('label')]
    public ?string $label;

    /**
     * @var ?int $order
     */
    #[JsonProperty('order')]
    public ?int $order;

    /**
     * @param array{
     *   enabled?: ?bool,
     *   label?: ?string,
     *   order?: ?int,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->enabled = $values['enabled'] ?? null;
        $this->label = $values['label'] ?? null;
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
