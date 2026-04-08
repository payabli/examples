<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Traits\Contacts;

class ApplicationDataOdpContactsItem extends JsonSerializableType
{
    use Contacts;


    /**
     * @param array{
     *   contactEmail?: ?string,
     *   contactName?: ?string,
     *   contactPhone?: ?string,
     *   contactTitle?: ?string,
     *   additionalData?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->contactEmail = $values['contactEmail'] ?? null;
        $this->contactName = $values['contactName'] ?? null;
        $this->contactPhone = $values['contactPhone'] ?? null;
        $this->contactTitle = $values['contactTitle'] ?? null;
        $this->additionalData = $values['additionalData'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
