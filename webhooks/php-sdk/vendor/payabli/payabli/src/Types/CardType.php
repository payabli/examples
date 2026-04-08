<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class CardType extends JsonSerializableType
{
    /**
     * @var ?TierItem $amex
     */
    #[JsonProperty('amex')]
    public ?TierItem $amex;

    /**
     * @var ?TierItem $discover
     */
    #[JsonProperty('discover')]
    public ?TierItem $discover;

    /**
     * @var ?TierItem $masterCard
     */
    #[JsonProperty('masterCard')]
    public ?TierItem $masterCard;

    /**
     * @var ?TierItem $visa
     */
    #[JsonProperty('visa')]
    public ?TierItem $visa;

    /**
     * @param array{
     *   amex?: ?TierItem,
     *   discover?: ?TierItem,
     *   masterCard?: ?TierItem,
     *   visa?: ?TierItem,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->amex = $values['amex'] ?? null;
        $this->discover = $values['discover'] ?? null;
        $this->masterCard = $values['masterCard'] ?? null;
        $this->visa = $values['visa'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
