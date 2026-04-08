<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * Details about pricing and payment services for a business.
 */
class ServicesSection extends JsonSerializableType
{
    /**
     * @var ?AchService $ach
     */
    #[JsonProperty('ach')]
    public ?AchService $ach;

    /**
     * @var ?CardService $card
     */
    #[JsonProperty('card')]
    public ?CardService $card;

    /**
     * @var ?string $subFooter
     */
    #[JsonProperty('subFooter')]
    public ?string $subFooter;

    /**
     * @var ?string $subHeader
     */
    #[JsonProperty('subHeader')]
    public ?string $subHeader;

    /**
     * @var ?bool $visible
     */
    #[JsonProperty('visible')]
    public ?bool $visible;

    /**
     * @param array{
     *   ach?: ?AchService,
     *   card?: ?CardService,
     *   subFooter?: ?string,
     *   subHeader?: ?string,
     *   visible?: ?bool,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->ach = $values['ach'] ?? null;
        $this->card = $values['card'] ?? null;
        $this->subFooter = $values['subFooter'] ?? null;
        $this->subHeader = $values['subHeader'] ?? null;
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
