<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class PayCategory extends JsonSerializableType
{
    /**
     * @var ?string $description
     */
    #[JsonProperty('description')]
    public ?string $description;

    /**
     * @var ?string $label
     */
    #[JsonProperty('label')]
    public ?string $label;

    /**
     * @var ?string $name
     */
    #[JsonProperty('name')]
    public ?string $name;

    /**
     * @var ?bool $optionalPay
     */
    #[JsonProperty('optionalPay')]
    public ?bool $optionalPay;

    /**
     * @var ?int $order
     */
    #[JsonProperty('order')]
    public ?int $order;

    /**
     * @var ?int $quantity
     */
    #[JsonProperty('quantity')]
    public ?int $quantity;

    /**
     * @var ?bool $showDescription
     */
    #[JsonProperty('showDescription')]
    public ?bool $showDescription;

    /**
     * @var ?string $type
     */
    #[JsonProperty('type')]
    public ?string $type;

    /**
     * @var ?string $value
     */
    #[JsonProperty('value')]
    public ?string $value;

    /**
     * @param array{
     *   description?: ?string,
     *   label?: ?string,
     *   name?: ?string,
     *   optionalPay?: ?bool,
     *   order?: ?int,
     *   quantity?: ?int,
     *   showDescription?: ?bool,
     *   type?: ?string,
     *   value?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->description = $values['description'] ?? null;
        $this->label = $values['label'] ?? null;
        $this->name = $values['name'] ?? null;
        $this->optionalPay = $values['optionalPay'] ?? null;
        $this->order = $values['order'] ?? null;
        $this->quantity = $values['quantity'] ?? null;
        $this->showDescription = $values['showDescription'] ?? null;
        $this->type = $values['type'] ?? null;
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
