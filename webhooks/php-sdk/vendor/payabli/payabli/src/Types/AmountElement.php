<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

class AmountElement extends JsonSerializableType
{
    /**
     * @var ?array<PayCategory> $categories
     */
    #[JsonProperty('categories'), ArrayType([PayCategory::class])]
    public ?array $categories;

    /**
     * @var ?bool $enabled
     */
    #[JsonProperty('enabled')]
    public ?bool $enabled;

    /**
     * @var ?int $order
     */
    #[JsonProperty('order')]
    public ?int $order;

    /**
     * @param array{
     *   categories?: ?array<PayCategory>,
     *   enabled?: ?bool,
     *   order?: ?int,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->categories = $values['categories'] ?? null;
        $this->enabled = $values['enabled'] ?? null;
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
