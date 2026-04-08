<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class ContactsResponse extends JsonSerializableType
{
    /**
     * @var ?string $contactEmail Contact email address.
     */
    #[JsonProperty('ContactEmail')]
    public ?string $contactEmail;

    /**
     * @var ?string $contactName Contact name.
     */
    #[JsonProperty('ContactName')]
    public ?string $contactName;

    /**
     * @var ?string $contactPhone Contact phone number.
     */
    #[JsonProperty('ContactPhone')]
    public ?string $contactPhone;

    /**
     * @var ?string $contactTitle Contact title.
     */
    #[JsonProperty('ContactTitle')]
    public ?string $contactTitle;

    /**
     * @param array{
     *   contactEmail?: ?string,
     *   contactName?: ?string,
     *   contactPhone?: ?string,
     *   contactTitle?: ?string,
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
