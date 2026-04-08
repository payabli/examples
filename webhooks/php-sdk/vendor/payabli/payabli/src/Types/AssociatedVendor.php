<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;
use DateTime;
use Payabli\Core\Types\Date;
use Payabli\Core\Types\Union;

class AssociatedVendor extends JsonSerializableType
{
    /**
     * @var ?string $vendorNumber
     */
    #[JsonProperty('VendorNumber')]
    public ?string $vendorNumber;

    /**
     * @var ?string $name1 Primary name for vendor.
     */
    #[JsonProperty('Name1')]
    public ?string $name1;

    /**
     * @var ?string $name2 Secondary name for vendor.
     */
    #[JsonProperty('Name2')]
    public ?string $name2;

    /**
     * @var ?string $ein
     */
    #[JsonProperty('EIN')]
    public ?string $ein;

    /**
     * @var ?string $phone Vendor's phone number.
     */
    #[JsonProperty('Phone')]
    public ?string $phone;

    /**
     * @var ?string $email Vendor's email address.
     */
    #[JsonProperty('Email')]
    public ?string $email;

    /**
     * @var ?string $address1 Vendor's address.
     */
    #[JsonProperty('Address1')]
    public ?string $address1;

    /**
     * @var ?string $address2 Additional line for vendor's address.
     */
    #[JsonProperty('Address2')]
    public ?string $address2;

    /**
     * @var ?string $city Vendor's city.
     */
    #[JsonProperty('City')]
    public ?string $city;

    /**
     * @var ?string $state Vendor's state.
     */
    #[JsonProperty('State')]
    public ?string $state;

    /**
     * @var ?string $zip Vendor's postal code.
     */
    #[JsonProperty('Zip')]
    public ?string $zip;

    /**
     * @var ?string $country Vendor's country.
     */
    #[JsonProperty('Country')]
    public ?string $country;

    /**
     * @var ?string $mcc
     */
    #[JsonProperty('Mcc')]
    public ?string $mcc;

    /**
     * @var ?string $locationCode
     */
    #[JsonProperty('LocationCode')]
    public ?string $locationCode;

    /**
     * @var ?array<Contacts> $contacts Array of objects describing the vendor's contacts.
     */
    #[JsonProperty('Contacts'), ArrayType([Contacts::class])]
    public ?array $contacts;

    /**
     * @var ?BillingDataResponse $billingData
     */
    #[JsonProperty('BillingData')]
    public ?BillingDataResponse $billingData;

    /**
     * @var ?string $paymentMethod
     */
    #[JsonProperty('PaymentMethod')]
    public ?string $paymentMethod;

    /**
     * @var ?int $vendorStatus
     */
    #[JsonProperty('VendorStatus')]
    public ?int $vendorStatus;

    /**
     * @var ?int $vendorId
     */
    #[JsonProperty('VendorId')]
    public ?int $vendorId;

    /**
     * @var ?string $enrollmentStatus
     */
    #[JsonProperty('EnrollmentStatus')]
    public ?string $enrollmentStatus;

    /**
     * @var ?VendorSummary $summary
     */
    #[JsonProperty('Summary')]
    public ?VendorSummary $summary;

    /**
     * @var ?string $paypointLegalname The paypoint's legal name.
     */
    #[JsonProperty('PaypointLegalname')]
    public ?string $paypointLegalname;

    /**
     * @var ?string $paypointDbaname The paypoint's DBA name.
     */
    #[JsonProperty('PaypointDbaname')]
    public ?string $paypointDbaname;

    /**
     * @var ?string $paypointEntryname Paypoint's entryname.
     */
    #[JsonProperty('PaypointEntryname')]
    public ?string $paypointEntryname;

    /**
     * @var ?string $parentOrgName
     */
    #[JsonProperty('ParentOrgName')]
    public ?string $parentOrgName;

    /**
     * @var ?DateTime $createdDate
     */
    #[JsonProperty('CreatedDate'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $createdDate;

    /**
     * @var ?DateTime $lastUpdated
     */
    #[JsonProperty('LastUpdated'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $lastUpdated;

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
     * @var ?string $remitCountry
     */
    #[JsonProperty('remitCountry')]
    public ?string $remitCountry;

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
     * @var ?string $customerVendorAccount
     */
    #[JsonProperty('customerVendorAccount')]
    public ?string $customerVendorAccount;

    /**
     * @var ?int $internalReferenceId
     */
    #[JsonProperty('InternalReferenceId')]
    public ?int $internalReferenceId;

    /**
     * @var ?array<string, ?array<string, mixed>> $additionalData
     */
    #[JsonProperty('additionalData'), ArrayType(['string' => new Union(['string' => 'mixed'], 'null')])]
    public ?array $additionalData;

    /**
     * @var ?string $externalPaypointId
     */
    #[JsonProperty('externalPaypointID')]
    public ?string $externalPaypointId;

    /**
     * @param array{
     *   vendorNumber?: ?string,
     *   name1?: ?string,
     *   name2?: ?string,
     *   ein?: ?string,
     *   phone?: ?string,
     *   email?: ?string,
     *   address1?: ?string,
     *   address2?: ?string,
     *   city?: ?string,
     *   state?: ?string,
     *   zip?: ?string,
     *   country?: ?string,
     *   mcc?: ?string,
     *   locationCode?: ?string,
     *   contacts?: ?array<Contacts>,
     *   billingData?: ?BillingDataResponse,
     *   paymentMethod?: ?string,
     *   vendorStatus?: ?int,
     *   vendorId?: ?int,
     *   enrollmentStatus?: ?string,
     *   summary?: ?VendorSummary,
     *   paypointLegalname?: ?string,
     *   paypointDbaname?: ?string,
     *   paypointEntryname?: ?string,
     *   parentOrgName?: ?string,
     *   createdDate?: ?DateTime,
     *   lastUpdated?: ?DateTime,
     *   remitAddress1?: ?string,
     *   remitAddress2?: ?string,
     *   remitCity?: ?string,
     *   remitState?: ?string,
     *   remitZip?: ?string,
     *   remitCountry?: ?string,
     *   payeeName1?: ?string,
     *   payeeName2?: ?string,
     *   customField1?: ?string,
     *   customField2?: ?string,
     *   customerVendorAccount?: ?string,
     *   internalReferenceId?: ?int,
     *   additionalData?: ?array<string, ?array<string, mixed>>,
     *   externalPaypointId?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->vendorNumber = $values['vendorNumber'] ?? null;
        $this->name1 = $values['name1'] ?? null;
        $this->name2 = $values['name2'] ?? null;
        $this->ein = $values['ein'] ?? null;
        $this->phone = $values['phone'] ?? null;
        $this->email = $values['email'] ?? null;
        $this->address1 = $values['address1'] ?? null;
        $this->address2 = $values['address2'] ?? null;
        $this->city = $values['city'] ?? null;
        $this->state = $values['state'] ?? null;
        $this->zip = $values['zip'] ?? null;
        $this->country = $values['country'] ?? null;
        $this->mcc = $values['mcc'] ?? null;
        $this->locationCode = $values['locationCode'] ?? null;
        $this->contacts = $values['contacts'] ?? null;
        $this->billingData = $values['billingData'] ?? null;
        $this->paymentMethod = $values['paymentMethod'] ?? null;
        $this->vendorStatus = $values['vendorStatus'] ?? null;
        $this->vendorId = $values['vendorId'] ?? null;
        $this->enrollmentStatus = $values['enrollmentStatus'] ?? null;
        $this->summary = $values['summary'] ?? null;
        $this->paypointLegalname = $values['paypointLegalname'] ?? null;
        $this->paypointDbaname = $values['paypointDbaname'] ?? null;
        $this->paypointEntryname = $values['paypointEntryname'] ?? null;
        $this->parentOrgName = $values['parentOrgName'] ?? null;
        $this->createdDate = $values['createdDate'] ?? null;
        $this->lastUpdated = $values['lastUpdated'] ?? null;
        $this->remitAddress1 = $values['remitAddress1'] ?? null;
        $this->remitAddress2 = $values['remitAddress2'] ?? null;
        $this->remitCity = $values['remitCity'] ?? null;
        $this->remitState = $values['remitState'] ?? null;
        $this->remitZip = $values['remitZip'] ?? null;
        $this->remitCountry = $values['remitCountry'] ?? null;
        $this->payeeName1 = $values['payeeName1'] ?? null;
        $this->payeeName2 = $values['payeeName2'] ?? null;
        $this->customField1 = $values['customField1'] ?? null;
        $this->customField2 = $values['customField2'] ?? null;
        $this->customerVendorAccount = $values['customerVendorAccount'] ?? null;
        $this->internalReferenceId = $values['internalReferenceId'] ?? null;
        $this->additionalData = $values['additionalData'] ?? null;
        $this->externalPaypointId = $values['externalPaypointId'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
