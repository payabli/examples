<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Traits\AchSetup;

class ApplicationDataPayInServicesAch extends JsonSerializableType
{
    use AchSetup;


    /**
     * @param array{
     *   acceptCcd?: ?bool,
     *   acceptPpd?: ?bool,
     *   acceptWeb?: ?bool,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->acceptCcd = $values['acceptCcd'] ?? null;
        $this->acceptPpd = $values['acceptPpd'] ?? null;
        $this->acceptWeb = $values['acceptWeb'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
