<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class PaymentCategories extends JsonSerializableType
{
    /**
     * @var float $amount Price/cost per unit of item or category.
     */
    #[JsonProperty('amount')]
    public float $amount;

    /**
     * @var ?string $description Description of item or category
     */
    #[JsonProperty('description')]
    public ?string $description;

    /**
     * @var string $label Name of item or category.
     */
    #[JsonProperty('label')]
    public string $label;

    /**
     * @var ?int $qty Quantity of item or category
     */
    #[JsonProperty('qty')]
    public ?int $qty;

    /**
     * @param array{
     *   amount: float,
     *   label: string,
     *   description?: ?string,
     *   qty?: ?int,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->amount = $values['amount'];
        $this->description = $values['description'] ?? null;
        $this->label = $values['label'];
        $this->qty = $values['qty'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
