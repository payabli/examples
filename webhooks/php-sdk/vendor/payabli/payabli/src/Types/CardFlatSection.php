<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

class CardFlatSection extends JsonSerializableType
{
    /**
     * @var ?array<CardType> $tiers
     */
    #[JsonProperty('tiers'), ArrayType([CardType::class])]
    public ?array $tiers;

    /**
     * @var ?bool $visible
     */
    #[JsonProperty('visible')]
    public ?bool $visible;

    /**
     * @param array{
     *   tiers?: ?array<CardType>,
     *   visible?: ?bool,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->tiers = $values['tiers'] ?? null;
        $this->visible = $values['visible'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
