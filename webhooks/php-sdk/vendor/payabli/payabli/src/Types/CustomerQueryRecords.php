<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use DateTime;
use Payabli\Core\Types\Date;
use Payabli\Core\Types\ArrayType;
use Payabli\Core\Types\Union;

class CustomerQueryRecords extends JsonSerializableType
{
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
     * @var ?string $customerUsername Username for customer.
     */
    #[JsonProperty('customerUsername')]
    public ?string $customerUsername;

    /**
     * @var ?int $customerStatus
     */
    #[JsonProperty('customerStatus')]
    public ?int $customerStatus;

    /**
     * @var ?string $company Company name.
     */
    #[JsonProperty('Company')]
    public ?string $company;

    /**
     * @var ?string $firstname Customer first name.
     */
    #[JsonProperty('Firstname')]
    public ?string $firstname;

    /**
     * @var ?string $lastname Customer last name.
     */
    #[JsonProperty('Lastname')]
    public ?string $lastname;

    /**
     * @var ?string $phone Customer phone number.
     */
    #[JsonProperty('Phone')]
    public ?string $phone;

    /**
     * @var ?string $email Customer email address.
     */
    #[JsonProperty('Email')]
    public ?string $email;

    /**
     * @var ?string $address Customer address.
     */
    #[JsonProperty('Address')]
    public ?string $address;

    /**
     * @var ?string $address1 Additional line for customer address.
     */
    #[JsonProperty('Address1')]
    public ?string $address1;

    /**
     * @var ?string $city Customer city.
     */
    #[JsonProperty('City')]
    public ?string $city;

    /**
     * @var ?string $state Customer state.
     */
    #[JsonProperty('State')]
    public ?string $state;

    /**
     * @var ?string $zip Customer postal code.
     */
    #[JsonProperty('Zip')]
    public ?string $zip;

    /**
     * @var ?string $country Customer country.
     */
    #[JsonProperty('Country')]
    public ?string $country;

    /**
     * @var ?string $shippingAddress
     */
    #[JsonProperty('ShippingAddress')]
    public ?string $shippingAddress;

    /**
     * @var ?string $shippingAddress1
     */
    #[JsonProperty('ShippingAddress1')]
    public ?string $shippingAddress1;

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
     * @var ?float $balance Customer balance.
     */
    #[JsonProperty('Balance')]
    public ?float $balance;

    /**
     * @var ?int $timeZone
     */
    #[JsonProperty('TimeZone')]
    public ?int $timeZone;

    /**
     * @var ?bool $mfa
     */
    #[JsonProperty('MFA')]
    public ?bool $mfa;

    /**
     * @var ?int $mfaMode
     */
    #[JsonProperty('MFAMode')]
    public ?int $mfaMode;

    /**
     * Social network linked to customer. Possible values:
     *
     * - `facebook`
     *
     * - `google`
     *
     * - `twitter`
     *
     * - `microsoft`
     *
     * @var ?string $snProvider
     */
    #[JsonProperty('snProvider')]
    public ?string $snProvider;

    /**
     * @var ?string $snIdentifier Identifier or token for customer in linked social network.
     */
    #[JsonProperty('snIdentifier')]
    public ?string $snIdentifier;

    /**
     * @var ?string $snData Additional data provided by the social network related to the customer.
     */
    #[JsonProperty('snData')]
    public ?string $snData;

    /**
     * @var ?DateTime $lastUpdated Date and time of last update.
     */
    #[JsonProperty('LastUpdated'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $lastUpdated;

    /**
     * @var ?DateTime $created Date and time created.
     */
    #[JsonProperty('Created'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $created;

    /**
     * @var ?array<string, ?string> $additionalFields List of additional custom fields in format key:value.
     */
    #[JsonProperty('AdditionalFields'), ArrayType(['string' => new Union('string', 'null')])]
    public ?array $additionalFields;

    /**
     * @var ?array<?string> $identifierFields
     */
    #[JsonProperty('IdentifierFields'), ArrayType([new Union('string', 'null')])]
    public ?array $identifierFields;

    /**
     * @var ?array<SubscriptionQueryRecords> $subscriptions List of subscriptions associated to the customer.
     */
    #[JsonProperty('Subscriptions'), ArrayType([SubscriptionQueryRecords::class])]
    public ?array $subscriptions;

    /**
     * @var ?array<MethodQueryRecords> $storedMethods List of payment methods associated to the customer.
     */
    #[JsonProperty('StoredMethods'), ArrayType([MethodQueryRecords::class])]
    public ?array $storedMethods;

    /**
     * @var ?CustomerSummaryRecord $customerSummary
     */
    #[JsonProperty('customerSummary')]
    public ?CustomerSummaryRecord $customerSummary;

    /**
     * @var ?string $paypointLegalname Paypoint legal name.
     */
    #[JsonProperty('PaypointLegalname')]
    public ?string $paypointLegalname;

    /**
     * @var ?string $paypointDbaname Paypoint DBA name.
     */
    #[JsonProperty('PaypointDbaname')]
    public ?string $paypointDbaname;

    /**
     * @var ?string $parentOrgName
     */
    #[JsonProperty('ParentOrgName')]
    public ?string $parentOrgName;

    /**
     * @var ?int $parentOrgId
     */
    #[JsonProperty('ParentOrgId')]
    public ?int $parentOrgId;

    /**
     * @var ?string $paypointEntryname
     */
    #[JsonProperty('PaypointEntryname')]
    public ?string $paypointEntryname;

    /**
     * @var ?string $pageidentifier
     */
    #[JsonProperty('pageidentifier')]
    public ?string $pageidentifier;

    /**
     * @var ?string $externalPaypointId
     */
    #[JsonProperty('externalPaypointID')]
    public ?string $externalPaypointId;

    /**
     * @var ?CustomerQueryRecordsCustomerConsent $customerConsent
     */
    #[JsonProperty('customerConsent')]
    public ?CustomerQueryRecordsCustomerConsent $customerConsent;

    /**
     * @param array{
     *   customerId?: ?int,
     *   customerNumber?: ?string,
     *   customerUsername?: ?string,
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
     *   mfa?: ?bool,
     *   mfaMode?: ?int,
     *   snProvider?: ?string,
     *   snIdentifier?: ?string,
     *   snData?: ?string,
     *   lastUpdated?: ?DateTime,
     *   created?: ?DateTime,
     *   additionalFields?: ?array<string, ?string>,
     *   identifierFields?: ?array<?string>,
     *   subscriptions?: ?array<SubscriptionQueryRecords>,
     *   storedMethods?: ?array<MethodQueryRecords>,
     *   customerSummary?: ?CustomerSummaryRecord,
     *   paypointLegalname?: ?string,
     *   paypointDbaname?: ?string,
     *   parentOrgName?: ?string,
     *   parentOrgId?: ?int,
     *   paypointEntryname?: ?string,
     *   pageidentifier?: ?string,
     *   externalPaypointId?: ?string,
     *   customerConsent?: ?CustomerQueryRecordsCustomerConsent,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->customerId = $values['customerId'] ?? null;
        $this->customerNumber = $values['customerNumber'] ?? null;
        $this->customerUsername = $values['customerUsername'] ?? null;
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
        $this->mfa = $values['mfa'] ?? null;
        $this->mfaMode = $values['mfaMode'] ?? null;
        $this->snProvider = $values['snProvider'] ?? null;
        $this->snIdentifier = $values['snIdentifier'] ?? null;
        $this->snData = $values['snData'] ?? null;
        $this->lastUpdated = $values['lastUpdated'] ?? null;
        $this->created = $values['created'] ?? null;
        $this->additionalFields = $values['additionalFields'] ?? null;
        $this->identifierFields = $values['identifierFields'] ?? null;
        $this->subscriptions = $values['subscriptions'] ?? null;
        $this->storedMethods = $values['storedMethods'] ?? null;
        $this->customerSummary = $values['customerSummary'] ?? null;
        $this->paypointLegalname = $values['paypointLegalname'] ?? null;
        $this->paypointDbaname = $values['paypointDbaname'] ?? null;
        $this->parentOrgName = $values['parentOrgName'] ?? null;
        $this->parentOrgId = $values['parentOrgId'] ?? null;
        $this->paypointEntryname = $values['paypointEntryname'] ?? null;
        $this->pageidentifier = $values['pageidentifier'] ?? null;
        $this->externalPaypointId = $values['externalPaypointId'] ?? null;
        $this->customerConsent = $values['customerConsent'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
