<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class CardTypePass extends JsonSerializableType
{
    /**
     * @var ?TierItemPass $amex
     */
    #[JsonProperty('amex')]
    public ?TierItemPass $amex;

    /**
     * @var ?TierItemPass $discover
     */
    #[JsonProperty('discover')]
    public ?TierItemPass $discover;

    /**
     * @var ?TierItemPass $masterCard
     */
    #[JsonProperty('masterCard')]
    public ?TierItemPass $masterCard;

    /**
     * @var ?TierItemPass $visa
     */
    #[JsonProperty('visa')]
    public ?TierItemPass $visa;

    /**
     * @param array{
     *   amex?: ?TierItemPass,
     *   discover?: ?TierItemPass,
     *   masterCard?: ?TierItemPass,
     *   visa?: ?TierItemPass,
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
