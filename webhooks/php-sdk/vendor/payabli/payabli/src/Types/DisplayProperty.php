<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class DisplayProperty extends JsonSerializableType
{
    /**
     * @var ?bool $display When `true`, the field is displayed on the receipt.
     */
    #[JsonProperty('display')]
    public ?bool $display;

    /**
     * @var ?bool $fixed This field is unused.
     */
    #[JsonProperty('Fixed')]
    public ?bool $fixed;

    /**
     * @var ?string $name The field's name.
     */
    #[JsonProperty('name')]
    public ?string $name;

    /**
     * @param array{
     *   display?: ?bool,
     *   fixed?: ?bool,
     *   name?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->display = $values['display'] ?? null;
        $this->fixed = $values['fixed'] ?? null;
        $this->name = $values['name'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
