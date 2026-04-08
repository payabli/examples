<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;
use Payabli\Core\Types\Union;
use DateTime;
use Payabli\Core\Types\Date;

class VendorQueryRecord extends JsonSerializableType
{
    /**
     * @var ?array<string, ?array<string, mixed>> $additionalData
     */
    #[JsonProperty('additionalData'), ArrayType(['string' => new Union(['string' => 'mixed'], 'null')])]
    public ?array $additionalData;

    /**
     * @var ?string $address1
     */
    #[JsonProperty('Address1')]
    public ?string $address1;

    /**
     * @var ?string $address2
     */
    #[JsonProperty('Address2')]
    public ?string $address2;

    /**
     * @var ?BillingDataResponse $billingData
     */
    #[JsonProperty('BillingData')]
    public ?BillingDataResponse $billingData;

    /**
     * @var ?string $city
     */
    #[JsonProperty('City')]
    public ?string $city;

    /**
     * @var ?ContactsResponse $contacts
     */
    #[JsonProperty('Contacts')]
    public ?ContactsResponse $contacts;

    /**
     * @var ?string $country
     */
    #[JsonProperty('Country')]
    public ?string $country;

    /**
     * @var ?DateTime $createdDate
     */
    #[JsonProperty('CreatedDate'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $createdDate;

    /**
     * @var ?string $customerVendorAccount
     */
    #[JsonProperty('customerVendorAccount')]
    public ?string $customerVendorAccount;

    /**
     * @var ?string $customField1
     */
    #[JsonProperty('customField1')]
    public ?string $customField1;

    /**
     * @var ?string $customField2
     */
    #[JsonProperty('customField2')]
    public ?string $customField2;

    /**
     * @var ?string $ein
     */
    #[JsonProperty('EIN')]
    public ?string $ein;

    /**
     * @var ?string $email
     */
    #[JsonProperty('Email')]
    public ?string $email;

    /**
     * @var ?string $enrollmentStatus
     */
    #[JsonProperty('EnrollmentStatus')]
    public ?string $enrollmentStatus;

    /**
     * @var ?string $externalPaypointId
     */
    #[JsonProperty('externalPaypointID')]
    public ?string $externalPaypointId;

    /**
     * @var ?int $internalReferenceId
     */
    #[JsonProperty('InternalReferenceId')]
    public ?int $internalReferenceId;

    /**
     * @var ?DateTime $lastUpdated
     */
    #[JsonProperty('LastUpdated'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $lastUpdated;

    /**
     * @var ?string $locationCode
     */
    #[JsonProperty('LocationCode')]
    public ?string $locationCode;

    /**
     * @var ?string $mcc
     */
    #[JsonProperty('Mcc')]
    public ?string $mcc;

    /**
     * @var ?string $name1
     */
    #[JsonProperty('Name1')]
    public ?string $name1;

    /**
     * @var ?string $name2
     */
    #[JsonProperty('Name2')]
    public ?string $name2;

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
     * @var ?string $payeeName1
     */
    #[JsonProperty('payeeName1')]
    public ?string $payeeName1;

    /**
     * @var ?string $payeeName2
     */
    #[JsonProperty('payeeName2')]
    public ?string $payeeName2;

    /**
     * @var ?string $paymentMethod
     */
    #[JsonProperty('PaymentMethod')]
    public ?string $paymentMethod;

    /**
     * @var ?string $paypointDbaname
     */
    #[JsonProperty('PaypointDbaname')]
    public ?string $paypointDbaname;

    /**
     * @var ?string $paypointEntryname
     */
    #[JsonProperty('PaypointEntryname')]
    public ?string $paypointEntryname;

    /**
     * @var ?string $paypointLegalname
     */
    #[JsonProperty('PaypointLegalname')]
    public ?string $paypointLegalname;

    /**
     * @var ?string $phone
     */
    #[JsonProperty('Phone')]
    public ?string $phone;

    /**
     * @var ?string $remitAddress1
     */
    #[JsonProperty('remitAddress1')]
    public ?string $remitAddress1;

    /**
     * @var ?string $remitAddress2
     */
    #[JsonProperty('remitAddress2')]
    public ?string $remitAddress2;

    /**
     * @var ?string $remitCity
     */
    #[JsonProperty('remitCity')]
    public ?string $remitCity;

    /**
     * @var ?string $remitCountry
     */
    #[JsonProperty('remitCountry')]
    public ?string $remitCountry;

    /**
     * @var ?string $remitEmail
     */
    #[JsonProperty('RemitEmail')]
    public ?string $remitEmail;

    /**
     * @var ?string $remitState
     */
    #[JsonProperty('remitState')]
    public ?string $remitState;

    /**
     * @var ?string $remitZip
     */
    #[JsonProperty('remitZip')]
    public ?string $remitZip;

    /**
     * @var ?string $state
     */
    #[JsonProperty('State')]
    public ?string $state;

    /**
     * @var ?array<VendorResponseStoredMethod> $storedMethods
     */
    #[JsonProperty('StoredMethods'), ArrayType([VendorResponseStoredMethod::class])]
    public ?array $storedMethods;

    /**
     * @var ?VendorSummary $summary
     */
    #[JsonProperty('Summary')]
    public ?VendorSummary $summary;

    /**
     * @var ?int $vendorId
     */
    #[JsonProperty('VendorId')]
    public ?int $vendorId;

    /**
     * @var ?string $vendorNumber
     */
    #[JsonProperty('VendorNumber')]
    public ?string $vendorNumber;

    /**
     * @var ?int $vendorStatus
     */
    #[JsonProperty('VendorStatus')]
    public ?int $vendorStatus;

    /**
     * @var ?string $zip
     */
    #[JsonProperty('Zip')]
    public ?string $zip;

    /**
     * @param array{
     *   additionalData?: ?array<string, ?array<string, mixed>>,
     *   address1?: ?string,
     *   address2?: ?string,
     *   billingData?: ?BillingDataResponse,
     *   city?: ?string,
     *   contacts?: ?ContactsResponse,
     *   country?: ?string,
     *   createdDate?: ?DateTime,
     *   customerVendorAccount?: ?string,
     *   customField1?: ?string,
     *   customField2?: ?string,
     *   ein?: ?string,
     *   email?: ?string,
     *   enrollmentStatus?: ?string,
     *   externalPaypointId?: ?string,
     *   internalReferenceId?: ?int,
     *   lastUpdated?: ?DateTime,
     *   locationCode?: ?string,
     *   mcc?: ?string,
     *   name1?: ?string,
     *   name2?: ?string,
     *   parentOrgName?: ?string,
     *   parentOrgId?: ?int,
     *   payeeName1?: ?string,
     *   payeeName2?: ?string,
     *   paymentMethod?: ?string,
     *   paypointDbaname?: ?string,
     *   paypointEntryname?: ?string,
     *   paypointLegalname?: ?string,
     *   phone?: ?string,
     *   remitAddress1?: ?string,
     *   remitAddress2?: ?string,
     *   remitCity?: ?string,
     *   remitCountry?: ?string,
     *   remitEmail?: ?string,
     *   remitState?: ?string,
     *   remitZip?: ?string,
     *   state?: ?string,
     *   storedMethods?: ?array<VendorResponseStoredMethod>,
     *   summary?: ?VendorSummary,
     *   vendorId?: ?int,
     *   vendorNumber?: ?string,
     *   vendorStatus?: ?int,
     *   zip?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->additionalData = $values['additionalData'] ?? null;
        $this->address1 = $values['address1'] ?? null;
        $this->address2 = $values['address2'] ?? null;
        $this->billingData = $values['billingData'] ?? null;
        $this->city = $values['city'] ?? null;
        $this->contacts = $values['contacts'] ?? null;
        $this->country = $values['country'] ?? null;
        $this->createdDate = $values['createdDate'] ?? null;
        $this->customerVendorAccount = $values['customerVendorAccount'] ?? null;
        $this->customField1 = $values['customField1'] ?? null;
        $this->customField2 = $values['customField2'] ?? null;
        $this->ein = $values['ein'] ?? null;
        $this->email = $values['email'] ?? null;
        $this->enrollmentStatus = $values['enrollmentStatus'] ?? null;
        $this->externalPaypointId = $values['externalPaypointId'] ?? null;
        $this->internalReferenceId = $values['internalReferenceId'] ?? null;
        $this->lastUpdated = $values['lastUpdated'] ?? null;
        $this->locationCode = $values['locationCode'] ?? null;
        $this->mcc = $values['mcc'] ?? null;
        $this->name1 = $values['name1'] ?? null;
        $this->name2 = $values['name2'] ?? null;
        $this->parentOrgName = $values['parentOrgName'] ?? null;
        $this->parentOrgId = $values['parentOrgId'] ?? null;
        $this->payeeName1 = $values['payeeName1'] ?? null;
        $this->payeeName2 = $values['payeeName2'] ?? null;
        $this->paymentMethod = $values['paymentMethod'] ?? null;
        $this->paypointDbaname = $values['paypointDbaname'] ?? null;
        $this->paypointEntryname = $values['paypointEntryname'] ?? null;
        $this->paypointLegalname = $values['paypointLegalname'] ?? null;
        $this->phone = $values['phone'] ?? null;
        $this->remitAddress1 = $values['remitAddress1'] ?? null;
        $this->remitAddress2 = $values['remitAddress2'] ?? null;
        $this->remitCity = $values['remitCity'] ?? null;
        $this->remitCountry = $values['remitCountry'] ?? null;
        $this->remitEmail = $values['remitEmail'] ?? null;
        $this->remitState = $values['remitState'] ?? null;
        $this->remitZip = $values['remitZip'] ?? null;
        $this->state = $values['state'] ?? null;
        $this->storedMethods = $values['storedMethods'] ?? null;
        $this->summary = $values['summary'] ?? null;
        $this->vendorId = $values['vendorId'] ?? null;
        $this->vendorNumber = $values['vendorNumber'] ?? null;
        $this->vendorStatus = $values['vendorStatus'] ?? null;
        $this->zip = $values['zip'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
