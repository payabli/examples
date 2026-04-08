<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class CardLinkTypes extends JsonSerializableType
{
    /**
     * @var ?LinkData $amex
     */
    #[JsonProperty('amex')]
    public ?LinkData $amex;

    /**
     * @var ?LinkData $discover
     */
    #[JsonProperty('discover')]
    public ?LinkData $discover;

    /**
     * @var ?LinkData $mastercard
     */
    #[JsonProperty('mastercard')]
    public ?LinkData $mastercard;

    /**
     * @var ?LinkData $visa
     */
    #[JsonProperty('visa')]
    public ?LinkData $visa;

    /**
     * @param array{
     *   amex?: ?LinkData,
     *   discover?: ?LinkData,
     *   mastercard?: ?LinkData,
     *   visa?: ?LinkData,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->amex = $values['amex'] ?? null;
        $this->discover = $values['discover'] ?? null;
        $this->mastercard = $values['mastercard'] ?? null;
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
