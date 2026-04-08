<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class CList extends JsonSerializableType
{
    /**
     * @var ?LinkData $contactEmail
     */
    #[JsonProperty('contactEmail')]
    public ?LinkData $contactEmail;

    /**
     * @var ?LinkData $contactName
     */
    #[JsonProperty('contactName')]
    public ?LinkData $contactName;

    /**
     * @var ?LinkData $contactPhone
     */
    #[JsonProperty('contactPhone')]
    public ?LinkData $contactPhone;

    /**
     * @var ?LinkData $contactTitle
     */
    #[JsonProperty('contactTitle')]
    public ?LinkData $contactTitle;

    /**
     * @param array{
     *   contactEmail?: ?LinkData,
     *   contactName?: ?LinkData,
     *   contactPhone?: ?LinkData,
     *   contactTitle?: ?LinkData,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->contactEmail = $values['contactEmail'] ?? null;
        $this->contactName = $values['contactName'] ?? null;
        $this->contactPhone = $values['contactPhone'] ?? null;
        $this->contactTitle = $values['contactTitle'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
