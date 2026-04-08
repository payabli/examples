<?php

namespace Payabli\Traits;

use Payabli\Core\Json\JsonProperty;

/**
 * @property ?string $contactEmail
 * @property ?string $contactName
 * @property ?string $contactPhone
 * @property ?string $contactTitle
 * @property ?string $additionalData
 */
trait Contacts
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
}
