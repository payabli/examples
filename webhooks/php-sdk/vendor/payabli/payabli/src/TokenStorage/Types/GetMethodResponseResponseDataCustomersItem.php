<?php

namespace Payabli\TokenStorage\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Traits\PayorDataRequest;
use Payabli\Core\Json\JsonProperty;
use DateTime;
use Payabli\Core\Types\Date;
use Payabli\Core\Types\ArrayType;
use Payabli\Types\CustomerSummaryRecord;
use Payabli\Types\MethodQueryRecords;
use Payabli\Types\SubscriptionQueryRecords;

class GetMethodResponseResponseDataCustomersItem extends JsonSerializableType
{
    use PayorDataRequest;

    /**
     * @var ?float $balance Customer's current balance
     */
    #[JsonProperty('balance')]
    public ?float $balance;

    /**
     * @var ?DateTime $created Creation timestamp
     */
    #[JsonProperty('created'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $created;

    /**
     * @var ?array<string, mixed> $customerConsent Customer consent information
     */
    #[JsonProperty('customerConsent'), ArrayType(['string' => 'mixed'])]
    public ?array $customerConsent;

    /**
     * @var ?int $customerStatus Status code for the customer
     */
    #[JsonProperty('customerStatus')]
    public ?int $customerStatus;

    /**
     * @var ?CustomerSummaryRecord $customerSummary
     */
    #[JsonProperty('customerSummary')]
    public ?CustomerSummaryRecord $customerSummary;

    /**
     * @var ?string $customerUsername Username of the customer
     */
    #[JsonProperty('customerUsername')]
    public ?string $customerUsername;

    /**
     * @var ?string $externalPaypointId
     */
    #[JsonProperty('externalPaypointID')]
    public ?string $externalPaypointId;

    /**
     * @var ?DateTime $lastUpdated Last update timestamp
     */
    #[JsonProperty('lastUpdated'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $lastUpdated;

    /**
     * @var ?bool $mfa Multi-factor authentication status
     */
    #[JsonProperty('mfa')]
    public ?bool $mfa;

    /**
     * @var ?int $mfaMode MFA mode setting
     */
    #[JsonProperty('mfaMode')]
    public ?int $mfaMode;

    /**
     * @var ?string $pageindentifier
     */
    #[JsonProperty('pageindentifier')]
    public ?string $pageindentifier;

    /**
     * @var ?int $parentOrgId Parent organization ID
     */
    #[JsonProperty('parentOrgId')]
    public ?int $parentOrgId;

    /**
     * @var ?string $parentOrgName
     */
    #[JsonProperty('parentOrgName')]
    public ?string $parentOrgName;

    /**
     * @var ?string $paypointDbaname
     */
    #[JsonProperty('paypointDbaname')]
    public ?string $paypointDbaname;

    /**
     * @var ?string $paypointEntryname The paypoint entryname the customer is associated with
     */
    #[JsonProperty('paypointEntryname')]
    public ?string $paypointEntryname;

    /**
     * @var ?string $paypointLegalname
     */
    #[JsonProperty('paypointLegalname')]
    public ?string $paypointLegalname;

    /**
     * @var ?array<string, mixed> $snData Social network data
     */
    #[JsonProperty('snData'), ArrayType(['string' => 'mixed'])]
    public ?array $snData;

    /**
     * @var ?string $snIdentifier Social network identifier
     */
    #[JsonProperty('snIdentifier')]
    public ?string $snIdentifier;

    /**
     * @var ?string $snProvider Social network provider
     */
    #[JsonProperty('snProvider')]
    public ?string $snProvider;

    /**
     * @var ?array<MethodQueryRecords> $storedMethods List of payment methods associated to the customer
     */
    #[JsonProperty('storedMethods'), ArrayType([MethodQueryRecords::class])]
    public ?array $storedMethods;

    /**
     * @var ?array<SubscriptionQueryRecords> $subscriptions List of subscriptions associated to the customer
     */
    #[JsonProperty('subscriptions'), ArrayType([SubscriptionQueryRecords::class])]
    public ?array $subscriptions;

    /**
     * @var ?int $timeZone Customer's timezone
     */
    #[JsonProperty('timeZone')]
    public ?int $timeZone;

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
     *   balance?: ?float,
     *   created?: ?DateTime,
     *   customerConsent?: ?array<string, mixed>,
     *   customerStatus?: ?int,
     *   customerSummary?: ?CustomerSummaryRecord,
     *   customerUsername?: ?string,
     *   externalPaypointId?: ?string,
     *   lastUpdated?: ?DateTime,
     *   mfa?: ?bool,
     *   mfaMode?: ?int,
     *   pageindentifier?: ?string,
     *   parentOrgId?: ?int,
     *   parentOrgName?: ?string,
     *   paypointDbaname?: ?string,
     *   paypointEntryname?: ?string,
     *   paypointLegalname?: ?string,
     *   snData?: ?array<string, mixed>,
     *   snIdentifier?: ?string,
     *   snProvider?: ?string,
     *   storedMethods?: ?array<MethodQueryRecords>,
     *   subscriptions?: ?array<SubscriptionQueryRecords>,
     *   timeZone?: ?int,
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
        $this->balance = $values['balance'] ?? null;
        $this->created = $values['created'] ?? null;
        $this->customerConsent = $values['customerConsent'] ?? null;
        $this->customerStatus = $values['customerStatus'] ?? null;
        $this->customerSummary = $values['customerSummary'] ?? null;
        $this->customerUsername = $values['customerUsername'] ?? null;
        $this->externalPaypointId = $values['externalPaypointId'] ?? null;
        $this->lastUpdated = $values['lastUpdated'] ?? null;
        $this->mfa = $values['mfa'] ?? null;
        $this->mfaMode = $values['mfaMode'] ?? null;
        $this->pageindentifier = $values['pageindentifier'] ?? null;
        $this->parentOrgId = $values['parentOrgId'] ?? null;
        $this->parentOrgName = $values['parentOrgName'] ?? null;
        $this->paypointDbaname = $values['paypointDbaname'] ?? null;
        $this->paypointEntryname = $values['paypointEntryname'] ?? null;
        $this->paypointLegalname = $values['paypointLegalname'] ?? null;
        $this->snData = $values['snData'] ?? null;
        $this->snIdentifier = $values['snIdentifier'] ?? null;
        $this->snProvider = $values['snProvider'] ?? null;
        $this->storedMethods = $values['storedMethods'] ?? null;
        $this->subscriptions = $values['subscriptions'] ?? null;
        $this->timeZone = $values['timeZone'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
