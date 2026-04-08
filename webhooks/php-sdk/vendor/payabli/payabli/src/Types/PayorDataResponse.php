<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;
use Payabli\Core\Types\Union;

/**
 * Customer information.
 */
class PayorDataResponse extends JsonSerializableType
{
    /**
     * @var ?array<string, ?array<string, mixed>> $additionalData
     */
    #[JsonProperty('AdditionalData'), ArrayType(['string' => new Union(['string' => 'mixed'], 'null')])]
    public ?array $additionalData;

    /**
     * @var ?string $billingAddress1
     */
    #[JsonProperty('BillingAddress1')]
    public ?string $billingAddress1;

    /**
     * @var ?string $billingAddress2
     */
    #[JsonProperty('BillingAddress2')]
    public ?string $billingAddress2;

    /**
     * @var ?string $billingCity
     */
    #[JsonProperty('BillingCity')]
    public ?string $billingCity;

    /**
     * @var ?string $billingCountry
     */
    #[JsonProperty('BillingCountry')]
    public ?string $billingCountry;

    /**
     * @var ?string $billingEmail
     */
    #[JsonProperty('BillingEmail')]
    public ?string $billingEmail;

    /**
     * @var ?string $billingPhone
     */
    #[JsonProperty('BillingPhone')]
    public ?string $billingPhone;

    /**
     * @var ?string $billingState
     */
    #[JsonProperty('BillingState')]
    public ?string $billingState;

    /**
     * @var ?string $billingZip Customer's billing ZIP code. For Pay In functions, this field supports 5-digit and 9-digit ZIP codes and alphanumeric Canadian postal codes. For example: "37615-1234" or "37615".
     */
    #[JsonProperty('BillingZip')]
    public ?string $billingZip;

    /**
     * @var ?string $companyName Customer's company name.
     */
    #[JsonProperty('CompanyName')]
    public ?string $companyName;

    /**
     * @var ?int $customerId
     */
    #[JsonProperty('customerId')]
    public ?int $customerId;

    /**
     * @var ?string $customerNumber
     */
    #[JsonProperty('CustomerNumber')]
    public ?string $customerNumber;

    /**
     * @var ?int $customerStatus Customer status. This is used to determine if the customer is active or inactive.
     */
    #[JsonProperty('customerStatus')]
    public ?int $customerStatus;

    /**
     * @var ?string $firstName Customer/Payor first name.
     */
    #[JsonProperty('FirstName')]
    public ?string $firstName;

    /**
     * @var ?array<?string> $identifiers
     */
    #[JsonProperty('Identifiers'), ArrayType([new Union('string', 'null')])]
    public ?array $identifiers;

    /**
     * @var ?string $lastName Customer/Payor last name.
     */
    #[JsonProperty('LastName')]
    public ?string $lastName;

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
     * @var ?string $shippingCountry
     */
    #[JsonProperty('ShippingCountry')]
    public ?string $shippingCountry;

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
     * @param array{
     *   additionalData?: ?array<string, ?array<string, mixed>>,
     *   billingAddress1?: ?string,
     *   billingAddress2?: ?string,
     *   billingCity?: ?string,
     *   billingCountry?: ?string,
     *   billingEmail?: ?string,
     *   billingPhone?: ?string,
     *   billingState?: ?string,
     *   billingZip?: ?string,
     *   companyName?: ?string,
     *   customerId?: ?int,
     *   customerNumber?: ?string,
     *   customerStatus?: ?int,
     *   firstName?: ?string,
     *   identifiers?: ?array<?string>,
     *   lastName?: ?string,
     *   shippingAddress1?: ?string,
     *   shippingAddress2?: ?string,
     *   shippingCity?: ?string,
     *   shippingCountry?: ?string,
     *   shippingState?: ?string,
     *   shippingZip?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->additionalData = $values['additionalData'] ?? null;
        $this->billingAddress1 = $values['billingAddress1'] ?? null;
        $this->billingAddress2 = $values['billingAddress2'] ?? null;
        $this->billingCity = $values['billingCity'] ?? null;
        $this->billingCountry = $values['billingCountry'] ?? null;
        $this->billingEmail = $values['billingEmail'] ?? null;
        $this->billingPhone = $values['billingPhone'] ?? null;
        $this->billingState = $values['billingState'] ?? null;
        $this->billingZip = $values['billingZip'] ?? null;
        $this->companyName = $values['companyName'] ?? null;
        $this->customerId = $values['customerId'] ?? null;
        $this->customerNumber = $values['customerNumber'] ?? null;
        $this->customerStatus = $values['customerStatus'] ?? null;
        $this->firstName = $values['firstName'] ?? null;
        $this->identifiers = $values['identifiers'] ?? null;
        $this->lastName = $values['lastName'] ?? null;
        $this->shippingAddress1 = $values['shippingAddress1'] ?? null;
        $this->shippingAddress2 = $values['shippingAddress2'] ?? null;
        $this->shippingCity = $values['shippingCity'] ?? null;
        $this->shippingCountry = $values['shippingCountry'] ?? null;
        $this->shippingState = $values['shippingState'] ?? null;
        $this->shippingZip = $values['shippingZip'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
