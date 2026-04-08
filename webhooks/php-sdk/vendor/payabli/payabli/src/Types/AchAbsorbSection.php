<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

class AchAbsorbSection extends JsonSerializableType
{
    /**
     * @var ?bool $multiTier
     */
    #[JsonProperty('multiTier')]
    public ?bool $multiTier;

    /**
     * @var ?array<AchTypesTiers> $tiers
     */
    #[JsonProperty('tiers'), ArrayType([AchTypesTiers::class])]
    public ?array $tiers;

    /**
     * @var ?bool $visible
     */
    #[JsonProperty('visible')]
    public ?bool $visible;

    /**
     * @param array{
     *   multiTier?: ?bool,
     *   tiers?: ?array<AchTypesTiers>,
     *   visible?: ?bool,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->multiTier = $values['multiTier'] ?? null;
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
