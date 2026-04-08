<?php

namespace Payabli\Traits;

use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;
use Payabli\Core\Types\Union;

/**
 * Customer information. May be required, depending on the paypoint's settings. Required for subscriptions.
 *
 * @property ?array<string, ?array<string, mixed>> $additionalData
 * @property ?string $billingAddress1
 * @property ?string $billingAddress2
 * @property ?string $billingCity
 * @property ?string $billingCountry
 * @property ?string $billingEmail
 * @property ?string $billingPhone
 * @property ?string $billingState
 * @property ?string $billingZip
 * @property ?string $company
 * @property ?int $customerId
 * @property ?string $customerNumber
 * @property ?string $firstName
 * @property ?array<?string> $identifierFields
 * @property ?string $lastName
 * @property ?string $shippingAddress1
 * @property ?string $shippingAddress2
 * @property ?string $shippingCity
 * @property ?string $shippingCountry
 * @property ?string $shippingState
 * @property ?string $shippingZip
 */
trait PayorDataRequest
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
}
