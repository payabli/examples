<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class CardSection extends JsonSerializableType
{
    /**
     * @var ?CardLinkTypes $acceptance
     */
    #[JsonProperty('acceptance')]
    public ?CardLinkTypes $acceptance;

    /**
     * @var ?BasicTable $fees
     */
    #[JsonProperty('fees')]
    public ?BasicTable $fees;

    /**
     * @var ?BasicTable $price
     */
    #[JsonProperty('price')]
    public ?BasicTable $price;

    /**
     * @param array{
     *   acceptance?: ?CardLinkTypes,
     *   fees?: ?BasicTable,
     *   price?: ?BasicTable,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->acceptance = $values['acceptance'] ?? null;
        $this->fees = $values['fees'] ?? null;
        $this->price = $values['price'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
