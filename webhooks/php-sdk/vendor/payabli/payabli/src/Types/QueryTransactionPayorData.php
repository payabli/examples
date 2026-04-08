<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

class QueryTransactionPayorData extends JsonSerializableType
{
    /**
     * @var ?array<mixed> $identifiers Array of field names to be used as identifiers.
     */
    #[JsonProperty('Identifiers'), ArrayType(['mixed'])]
    public ?array $identifiers;

    /**
     * @var ?string $firstName Customer/Payor first name.
     */
    #[JsonProperty('FirstName')]
    public ?string $firstName;

    /**
     * @var ?string $lastName Customer/Payor last name.
     */
    #[JsonProperty('LastName')]
    public ?string $lastName;

    /**
     * @var ?string $companyName Customer's company name.
     */
    #[JsonProperty('CompanyName')]
    public ?string $companyName;

    /**
     * @var ?string $billingAddress1 Customer's billing address.
     */
    #[JsonProperty('BillingAddress1')]
    public ?string $billingAddress1;

    /**
     * @var ?string $billingAddress2 Additional line for Customer's billing address.
     */
    #[JsonProperty('BillingAddress2')]
    public ?string $billingAddress2;

    /**
     * @var ?string $billingCity Customer's billing city.
     */
    #[JsonProperty('BillingCity')]
    public ?string $billingCity;

    /**
     * @var ?string $billingState Customer's billing state. Must be 2-letter state code for address in US.
     */
    #[JsonProperty('BillingState')]
    public ?string $billingState;

    /**
     * @var ?string $billingZip Customer's billing ZIP code.
     */
    #[JsonProperty('BillingZip')]
    public ?string $billingZip;

    /**
     * @var ?string $billingCountry Customer's billing country.
     */
    #[JsonProperty('BillingCountry')]
    public ?string $billingCountry;

    /**
     * @var ?string $billingPhone Customer's phone number.
     */
    #[JsonProperty('BillingPhone')]
    public ?string $billingPhone;

    /**
     * @var ?string $billingEmail Customer's email address.
     */
    #[JsonProperty('BillingEmail')]
    public ?string $billingEmail;

    /**
     * @var ?string $customerNumber
     */
    #[JsonProperty('CustomerNumber')]
    public ?string $customerNumber;

    /**
     * @var ?string $shippingAddress1
     */
    #[JsonProperty('ShippingAddress1')]
    public ?string $shippingAddress1;

    /**
     * @var ?string $shippingAddress2
     */
    #[JsonProperty('ShippingAddress2')]
    public ?string $shippingAddress2;

    /**
     * @var ?string $shippingCity
     */
    #[JsonProperty('ShippingCity')]
    public ?string $shippingCity;

    /**
     * @var ?string $shippingState
     */
    #[JsonProperty('ShippingState')]
    public ?string $shippingState;

    /**
     * @var ?string $shippingZip
     */
    #[JsonProperty('ShippingZip')]
    public ?string $shippingZip;

    /**
     * @var ?string $shippingCountry
     */
    #[JsonProperty('ShippingCountry')]
    public ?string $shippingCountry;

    /**
     * @var ?int $customerId
     */
    #[JsonProperty('customerId')]
    public ?int $customerId;

    /**
     * @var ?int $customerStatus
     */
    #[JsonProperty('customerStatus')]
    public ?int $customerStatus;

    /**
     * @var ?string $additionalData
     */
    #[JsonProperty('AdditionalData')]
    public ?string $additionalData;

    /**
     * @param array{
     *   identifiers?: ?array<mixed>,
     *   firstName?: ?string,
     *   lastName?: ?string,
     *   companyName?: ?string,
     *   billingAddress1?: ?string,
     *   billingAddress2?: ?string,
     *   billingCity?: ?string,
     *   billingState?: ?string,
     *   billingZip?: ?string,
     *   billingCountry?: ?string,
     *   billingPhone?: ?string,
     *   billingEmail?: ?string,
     *   customerNumber?: ?string,
     *   shippingAddress1?: ?string,
     *   shippingAddress2?: ?string,
     *   shippingCity?: ?string,
     *   shippingState?: ?string,
     *   shippingZip?: ?string,
     *   shippingCountry?: ?string,
     *   customerId?: ?int,
     *   customerStatus?: ?int,
     *   additionalData?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->identifiers = $values['identifiers'] ?? null;
        $this->firstName = $values['firstName'] ?? null;
        $this->lastName = $values['lastName'] ?? null;
        $this->companyName = $values['companyName'] ?? null;
        $this->billingAddress1 = $values['billingAddress1'] ?? null;
        $this->billingAddress2 = $values['billingAddress2'] ?? null;
        $this->billingCity = $values['billingCity'] ?? null;
        $this->billingState = $values['billingState'] ?? null;
        $this->billingZip = $values['billingZip'] ?? null;
        $this->billingCountry = $values['billingCountry'] ?? null;
        $this->billingPhone = $values['billingPhone'] ?? null;
        $this->billingEmail = $values['billingEmail'] ?? null;
        $this->customerNumber = $values['customerNumber'] ?? null;
        $this->shippingAddress1 = $values['shippingAddress1'] ?? null;
        $this->shippingAddress2 = $values['shippingAddress2'] ?? null;
        $this->shippingCity = $values['shippingCity'] ?? null;
        $this->shippingState = $values['shippingState'] ?? null;
        $this->shippingZip = $values['shippingZip'] ?? null;
        $this->shippingCountry = $values['shippingCountry'] ?? null;
        $this->customerId = $values['customerId'] ?? null;
        $this->customerStatus = $values['customerStatus'] ?? null;
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
