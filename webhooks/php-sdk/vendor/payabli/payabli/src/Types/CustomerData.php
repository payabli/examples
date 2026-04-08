<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;
use Payabli\Core\Types\Union;
use DateTime;
use Payabli\Core\Types\Date;

/**
 * Data about a single customer.
 */
class CustomerData extends JsonSerializableType
{
    /**
     * @var ?string $customerNumber
     */
    #[JsonProperty('customerNumber')]
    public ?string $customerNumber;

    /**
     * @var ?string $customerUsername Customer username for customer portal
     */
    #[JsonProperty('customerUsername')]
    public ?string $customerUsername;

    /**
     * @var ?string $customerPsw Customer password for customer portal
     */
    #[JsonProperty('customerPsw')]
    public ?string $customerPsw;

    /**
     * @var ?int $customerStatus
     */
    #[JsonProperty('customerStatus')]
    public ?int $customerStatus;

    /**
     * @var ?string $company Company name
     */
    #[JsonProperty('company')]
    public ?string $company;

    /**
     * @var ?string $firstname Customer first name
     */
    #[JsonProperty('firstname')]
    public ?string $firstname;

    /**
     * @var ?string $lastname Customer last name
     */
    #[JsonProperty('lastname')]
    public ?string $lastname;

    /**
     * @var ?string $phone Customer phone number
     */
    #[JsonProperty('phone')]
    public ?string $phone;

    /**
     * @var ?string $email Customer email address.
     */
    #[JsonProperty('email')]
    public ?string $email;

    /**
     * @var ?string $address Customer address
     */
    #[JsonProperty('address')]
    public ?string $address;

    /**
     * @var ?string $address1 Additional customer address
     */
    #[JsonProperty('address1')]
    public ?string $address1;

    /**
     * @var ?string $city Customer city
     */
    #[JsonProperty('city')]
    public ?string $city;

    /**
     * @var ?string $state Customer State
     */
    #[JsonProperty('state')]
    public ?string $state;

    /**
     * @var ?string $zip Customer postal code
     */
    #[JsonProperty('zip')]
    public ?string $zip;

    /**
     * @var ?string $country Customer country in ISO-3166-1 alpha 2 format. See https://en.wikipedia.org/wiki/ISO_3166-1 for reference.
     */
    #[JsonProperty('country')]
    public ?string $country;

    /**
     * @var ?string $shippingAddress
     */
    #[JsonProperty('shippingAddress')]
    public ?string $shippingAddress;

    /**
     * @var ?string $shippingAddress1
     */
    #[JsonProperty('shippingAddress1')]
    public ?string $shippingAddress1;

    /**
     * @var ?string $shippingCity
     */
    #[JsonProperty('shippingCity')]
    public ?string $shippingCity;

    /**
     * @var ?string $shippingState
     */
    #[JsonProperty('shippingState')]
    public ?string $shippingState;

    /**
     * @var ?string $shippingZip
     */
    #[JsonProperty('shippingZip')]
    public ?string $shippingZip;

    /**
     * @var ?string $shippingCountry
     */
    #[JsonProperty('shippingCountry')]
    public ?string $shippingCountry;

    /**
     * @var ?float $balance Customer balance.
     */
    #[JsonProperty('balance')]
    public ?float $balance;

    /**
     * @var ?int $timeZone
     */
    #[JsonProperty('timeZone')]
    public ?int $timeZone;

    /**
     * @var ?array<string, ?string> $additionalFields Additional Custom fields in format "key":"value".
     */
    #[JsonProperty('additionalFields'), ArrayType(['string' => new Union('string', 'null')])]
    public ?array $additionalFields;

    /**
     * @var ?array<?string> $identifierFields
     */
    #[JsonProperty('identifierFields'), ArrayType([new Union('string', 'null')])]
    public ?array $identifierFields;

    /**
     * @var ?DateTime $createdAt
     */
    #[JsonProperty('createdAt'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $createdAt;

    /**
     * @param array{
     *   customerNumber?: ?string,
     *   customerUsername?: ?string,
     *   customerPsw?: ?string,
     *   customerStatus?: ?int,
     *   company?: ?string,
     *   firstname?: ?string,
     *   lastname?: ?string,
     *   phone?: ?string,
     *   email?: ?string,
     *   address?: ?string,
     *   address1?: ?string,
     *   city?: ?string,
     *   state?: ?string,
     *   zip?: ?string,
     *   country?: ?string,
     *   shippingAddress?: ?string,
     *   shippingAddress1?: ?string,
     *   shippingCity?: ?string,
     *   shippingState?: ?string,
     *   shippingZip?: ?string,
     *   shippingCountry?: ?string,
     *   balance?: ?float,
     *   timeZone?: ?int,
     *   additionalFields?: ?array<string, ?string>,
     *   identifierFields?: ?array<?string>,
     *   createdAt?: ?DateTime,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->customerNumber = $values['customerNumber'] ?? null;
        $this->customerUsername = $values['customerUsername'] ?? null;
        $this->customerPsw = $values['customerPsw'] ?? null;
        $this->customerStatus = $values['customerStatus'] ?? null;
        $this->company = $values['company'] ?? null;
        $this->firstname = $values['firstname'] ?? null;
        $this->lastname = $values['lastname'] ?? null;
        $this->phone = $values['phone'] ?? null;
        $this->email = $values['email'] ?? null;
        $this->address = $values['address'] ?? null;
        $this->address1 = $values['address1'] ?? null;
        $this->city = $values['city'] ?? null;
        $this->state = $values['state'] ?? null;
        $this->zip = $values['zip'] ?? null;
        $this->country = $values['country'] ?? null;
        $this->shippingAddress = $values['shippingAddress'] ?? null;
        $this->shippingAddress1 = $values['shippingAddress1'] ?? null;
        $this->shippingCity = $values['shippingCity'] ?? null;
        $this->shippingState = $values['shippingState'] ?? null;
        $this->shippingZip = $values['shippingZip'] ?? null;
        $this->shippingCountry = $values['shippingCountry'] ?? null;
        $this->balance = $values['balance'] ?? null;
        $this->timeZone = $values['timeZone'] ?? null;
        $this->additionalFields = $values['additionalFields'] ?? null;
        $this->identifierFields = $values['identifierFields'] ?? null;
        $this->createdAt = $values['createdAt'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
