<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Traits\CardSetup;

class ApplicationDataPayInServicesCard extends JsonSerializableType
{
    use CardSetup;


    /**
     * @param array{
     *   acceptAmex?: ?bool,
     *   acceptDiscover?: ?bool,
     *   acceptMastercard?: ?bool,
     *   acceptVisa?: ?bool,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->acceptAmex = $values['acceptAmex'] ?? null;
        $this->acceptDiscover = $values['acceptDiscover'] ?? null;
        $this->acceptMastercard = $values['acceptMastercard'] ?? null;
        $this->acceptVisa = $values['acceptVisa'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
