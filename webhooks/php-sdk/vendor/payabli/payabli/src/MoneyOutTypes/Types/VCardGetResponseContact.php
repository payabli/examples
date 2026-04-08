<?php

namespace Payabli\MoneyOutTypes\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * Contact information structure.
 */
class VCardGetResponseContact extends JsonSerializableType
{
    /**
     * @var ?string $contactName Name of the contact.
     */
    #[JsonProperty('ContactName')]
    public ?string $contactName;

    /**
     * @var ?string $contactEmail Email of the contact.
     */
    #[JsonProperty('ContactEmail')]
    public ?string $contactEmail;

    /**
     * @var ?string $contactTitle Title of the contact.
     */
    #[JsonProperty('ContactTitle')]
    public ?string $contactTitle;

    /**
     * @var ?string $contactPhone Phone number of the contact.
     */
    #[JsonProperty('ContactPhone')]
    public ?string $contactPhone;

    /**
     * @param array{
     *   contactName?: ?string,
     *   contactEmail?: ?string,
     *   contactTitle?: ?string,
     *   contactPhone?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->contactName = $values['contactName'] ?? null;
        $this->contactEmail = $values['contactEmail'] ?? null;
        $this->contactTitle = $values['contactTitle'] ?? null;
        $this->contactPhone = $values['contactPhone'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
