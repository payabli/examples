<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * Controls which services will be enabled for the merchant.
 */
class Services extends JsonSerializableType
{
    /**
     * @var ?AchSetup $ach
     */
    #[JsonProperty('ach')]
    public ?AchSetup $ach;

    /**
     * @var ?CardSetup $card
     */
    #[JsonProperty('card')]
    public ?CardSetup $card;

    /**
     * @var ?OdpSetup $odp
     */
    #[JsonProperty('odp')]
    public ?OdpSetup $odp;

    /**
     * @param array{
     *   ach?: ?AchSetup,
     *   card?: ?CardSetup,
     *   odp?: ?OdpSetup,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->ach = $values['ach'] ?? null;
        $this->card = $values['card'] ?? null;
        $this->odp = $values['odp'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
