<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class ApplicationDataPayInServices extends JsonSerializableType
{
    /**
     * @var ApplicationDataPayInServicesAch $ach
     */
    #[JsonProperty('ach')]
    public ApplicationDataPayInServicesAch $ach;

    /**
     * @var ApplicationDataPayInServicesCard $card
     */
    #[JsonProperty('card')]
    public ApplicationDataPayInServicesCard $card;

    /**
     * @var ?OdpSetup $odp
     */
    #[JsonProperty('odp')]
    public ?OdpSetup $odp;

    /**
     * @param array{
     *   ach: ApplicationDataPayInServicesAch,
     *   card: ApplicationDataPayInServicesCard,
     *   odp?: ?OdpSetup,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->ach = $values['ach'];
        $this->card = $values['card'];
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
