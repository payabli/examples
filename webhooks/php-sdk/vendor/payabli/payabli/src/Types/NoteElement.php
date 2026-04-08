<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class NoteElement extends JsonSerializableType
{
    /**
     * @var ?bool $enabled
     */
    #[JsonProperty('enabled')]
    public ?bool $enabled;

    /**
     * @var ?string $header Header text for section
     */
    #[JsonProperty('header')]
    public ?string $header;

    /**
     * @var ?int $order
     */
    #[JsonProperty('order')]
    public ?int $order;

    /**
     * @var ?string $placeholder Placeholder text for input field
     */
    #[JsonProperty('placeholder')]
    public ?string $placeholder;

    /**
     * @var ?string $value Pre-populated value for input field
     */
    #[JsonProperty('value')]
    public ?string $value;

    /**
     * @param array{
     *   enabled?: ?bool,
     *   header?: ?string,
     *   order?: ?int,
     *   placeholder?: ?string,
     *   value?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->enabled = $values['enabled'] ?? null;
        $this->header = $values['header'] ?? null;
        $this->order = $values['order'] ?? null;
        $this->placeholder = $values['placeholder'] ?? null;
        $this->value = $values['value'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
