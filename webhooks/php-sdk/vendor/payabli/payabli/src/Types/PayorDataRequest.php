<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;
use Payabli\Core\Types\Union;

/**
 * Customer information. May be required, depending on the paypoint's settings. Required for subscriptions.
 */
class PayorDataRequest extends JsonSerializableType
{
    /**
     * @var ?array<string, ?array<string, mixed>> $additionalData
     */
    #[JsonProperty('additionalData'), ArrayType(['string' => new Union(['string' => 'mixed'], 'null')])]
    public ?array $additionalData;

    /**
     * @var ?string $billingAddress1
     */
    #[JsonProperty('billingAddress1')]
    public ?string $billingAddress1;

    /**
     * @var ?string $billingAddress2
     */
    #[JsonProperty('billingAddress2')]
    public ?string $billingAddress2;

    /**
     * @var ?string $billingCity
     */
    #[JsonProperty('billingCity')]
    public ?string $billingCity;

    /**
     * @var ?string $billingCountry
     */
    #[JsonProperty('billingCountry')]
    public ?string $billingCountry;

    /**
     * @var ?string $billingEmail
     */
    #[JsonProperty('billingEmail')]
    public ?string $billingEmail;

    /**
     * @var ?string $billingPhone
     */
    #[JsonProperty('billingPhone')]
    public ?string $billingPhone;

    /**
     * @var ?string $billingState
     */
    #[JsonProperty('billingState')]
    public ?string $billingState;

    /**
     * @var ?string $billingZip Customer's billing ZIP code. For Pay In functions, this field supports 5-digit and 9-digit ZIP codes and alphanumeric Canadian postal codes. For example: "37615-1234" or "37615".
     */
    #[JsonProperty('billingZip')]
    public ?string $billingZip;

    /**
     * @var ?string $company Customer's company name.
     */
    #[JsonProperty('company')]
    public ?string $company;

    /**
     * @var ?int $customerId
     */
    #[JsonProperty('customerId')]
    public ?int $customerId;

    /**
     * @var ?string $customerNumber
     */
    #[JsonProperty('customerNumber')]
    public ?string $customerNumber;

    /**
     * @var ?string $firstName Customer/Payor first name.
     */
    #[JsonProperty('firstName')]
    public ?string $firstName;

    /**
     * @var ?array<?string> $identifierFields
     */
    #[JsonProperty('identifierFields'), ArrayType([new Union('string', 'null')])]
    public ?array $identifierFields;

    /**
     * @var ?string $lastName Customer/Payor last name.
     */
    #[JsonProperty('lastName')]
    public ?string $lastName;

    /**
     * @var ?string $shippingAddress1
     */
    #[JsonProperty('shippingAddress1')]
    public ?string $shippingAddress1;

    /**
     * @var ?string $shippingAddress2
     */
    #[JsonProperty('shippingAddress2')]
    public ?string $shippingAddress2;

    /**
     * @var ?string $shippingCity
     */
    #[JsonProperty('shippingCity')]
    public ?string $shippingCity;

    /**
     * @var ?string $shippingCountry
     */
    #[JsonProperty('shippingCountry')]
    public ?string $shippingCountry;

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
     *   company?: ?string,
     *   customerId?: ?int,
     *   customerNumber?: ?string,
     *   firstName?: ?string,
     *   identifierFields?: ?array<?string>,
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
        $this->company = $values['company'] ?? null;
        $this->customerId = $values['customerId'] ?? null;
        $this->customerNumber = $values['customerNumber'] ?? null;
        $this->firstName = $values['firstName'] ?? null;
        $this->identifierFields = $values['identifierFields'] ?? null;
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
