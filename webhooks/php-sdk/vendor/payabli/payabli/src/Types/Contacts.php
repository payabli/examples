<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class Contacts extends JsonSerializableType
{
    /**
     * @var ?string $contactEmail Contact email address.
     */
    #[JsonProperty('contactEmail')]
    public ?string $contactEmail;

    /**
     * @var ?string $contactName Contact name.
     */
    #[JsonProperty('contactName')]
    public ?string $contactName;

    /**
     * @var ?string $contactPhone Contact phone number.
     */
    #[JsonProperty('contactPhone')]
    public ?string $contactPhone;

    /**
     * @var ?string $contactTitle Contact title.
     */
    #[JsonProperty('contactTitle')]
    public ?string $contactTitle;

    /**
     * @var ?string $additionalData
     */
    #[JsonProperty('additionalData')]
    public ?string $additionalData;

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
