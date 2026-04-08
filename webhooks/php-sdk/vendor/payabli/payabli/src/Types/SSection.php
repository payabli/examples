<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class SSection extends JsonSerializableType
{
    /**
     * @var ?AchSection $ach
     */
    #[JsonProperty('ach')]
    public ?AchSection $ach;

    /**
     * @var ?CardSection $card
     */
    #[JsonProperty('card')]
    public ?CardSection $card;

    /**
     * @param array{
     *   ach?: ?AchSection,
     *   card?: ?CardSection,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->ach = $values['ach'] ?? null;
        $this->card = $values['card'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
