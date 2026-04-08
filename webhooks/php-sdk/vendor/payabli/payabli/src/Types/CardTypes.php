<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class CardTypes extends JsonSerializableType
{
    /**
     * @var ?BasicTemplateElement $amex
     */
    #[JsonProperty('amex')]
    public ?BasicTemplateElement $amex;

    /**
     * @var ?BasicTemplateElement $discover
     */
    #[JsonProperty('discover')]
    public ?BasicTemplateElement $discover;

    /**
     * @var ?BasicTemplateElement $masterCard
     */
    #[JsonProperty('masterCard')]
    public ?BasicTemplateElement $masterCard;

    /**
     * @var ?BasicTemplateElement $visa
     */
    #[JsonProperty('visa')]
    public ?BasicTemplateElement $visa;

    /**
     * @param array{
     *   amex?: ?BasicTemplateElement,
     *   discover?: ?BasicTemplateElement,
     *   masterCard?: ?BasicTemplateElement,
     *   visa?: ?BasicTemplateElement,
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
