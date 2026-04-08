<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;
use DateTime;
use Payabli\Core\Types\Date;

class VendorDataResponse extends JsonSerializableType
{
    /**
     * @var string $vendorNumber
     */
    #[JsonProperty('VendorNumber')]
    public string $vendorNumber;

    /**
     * @var string $name1 Primary name for vendor.
     */
    #[JsonProperty('Name1')]
    public string $name1;

    /**
     * @var string $name2 Secondary name for vendor.
     */
    #[JsonProperty('Name2')]
    public string $name2;

    /**
     * @var string $ein EIN/Tax ID for vendor. In responses, this field is masked, and looks like: `"ein": "XXXXX6789"`.
     */
    #[JsonProperty('EIN')]
    public string $ein;

    /**
     * @var string $phone Vendor's phone number.
     */
    #[JsonProperty('Phone')]
    public string $phone;

    /**
     * @var string $email
     */
    #[JsonProperty('Email')]
    public string $email;

    /**
     * @var ?string $remitEmail Email address for remittance
     */
    #[JsonProperty('RemitEmail')]
    public ?string $remitEmail;

    /**
     * @var string $address1 Vendor's address.
     */
    #[JsonProperty('Address1')]
    public string $address1;

    /**
     * @var string $address2 Additional line for vendor's address.
     */
    #[JsonProperty('Address2')]
    public string $address2;

    /**
     * @var string $city Vendor's city.
     */
    #[JsonProperty('City')]
    public string $city;

    /**
     * @var string $state Vendor's state. Must be a two-character state code.
     */
    #[JsonProperty('State')]
    public string $state;

    /**
     * @var string $zip Vendor's zip code.
     */
    #[JsonProperty('Zip')]
    public string $zip;

    /**
     * @var string $country Vendor's country. Payabli supports only US and Canadian vendors.
     */
    #[JsonProperty('Country')]
    public string $country;

    /**
     * @var string $mcc
     */
    #[JsonProperty('Mcc')]
    public string $mcc;

    /**
     * @var string $locationCode
     */
    #[JsonProperty('LocationCode')]
    public string $locationCode;

    /**
     * @var array<ContactsResponse> $contacts Array of objects describing the vendor's contacts.
     */
    #[JsonProperty('Contacts'), ArrayType([ContactsResponse::class])]
    public array $contacts;

    /**
     * @var VendorResponseBillingData $billingData Object containing vendor's bank information.
     */
    #[JsonProperty('BillingData')]
    public VendorResponseBillingData $billingData;

    /**
     * @var value-of<VendorDataResponsePaymentMethod> $paymentMethod Preferred payment method for vendor.
     */
    #[JsonProperty('PaymentMethod')]
    public string $paymentMethod;

    /**
     * @var int $vendorStatus
     */
    #[JsonProperty('VendorStatus')]
    public int $vendorStatus;

    /**
     * @var int $vendorId
     */
    #[JsonProperty('VendorId')]
    public int $vendorId;

    /**
     * @var ?string $enrollmentStatus Vendor enrollment status
     */
    #[JsonProperty('EnrollmentStatus')]
    public ?string $enrollmentStatus;

    /**
     * @var VendorResponseSummary $summary Vendor bill summary statistics
     */
    #[JsonProperty('Summary')]
    public VendorResponseSummary $summary;

    /**
     * @var string $paypointLegalname Legal name of the paypoint
     */
    #[JsonProperty('PaypointLegalname')]
    public string $paypointLegalname;

    /**
     * @var string $paypointDbaname DBA name of the paypoint
     */
    #[JsonProperty('PaypointDbaname')]
    public string $paypointDbaname;

    /**
     * @var string $paypointEntryname Entry name of the paypoint
     */
    #[JsonProperty('PaypointEntryname')]
    public string $paypointEntryname;

    /**
     * @var string $parentOrgName Name of the parent organization
     */
    #[JsonProperty('ParentOrgName')]
    public string $parentOrgName;

    /**
     * @var int $parentOrgId ID of the parent organization
     */
    #[JsonProperty('ParentOrgId')]
    public int $parentOrgId;

    /**
     * @var DateTime $createdDate Date when vendor was created
     */
    #[JsonProperty('CreatedDate'), Date(Date::TYPE_DATETIME)]
    public DateTime $createdDate;

    /**
     * @var DateTime $lastUpdated Date when vendor was last updated
     */
    #[JsonProperty('LastUpdated'), Date(Date::TYPE_DATETIME)]
    public DateTime $lastUpdated;

    /**
     * @var string $remitAddress1
     */
    #[JsonProperty('remitAddress1')]
    public string $remitAddress1;

    /**
     * @var string $remitAddress2
     */
    #[JsonProperty('remitAddress2')]
    public string $remitAddress2;

    /**
     * @var string $remitCity
     */
    #[JsonProperty('remitCity')]
    public string $remitCity;

    /**
     * @var string $remitState
     */
    #[JsonProperty('remitState')]
    public string $remitState;

    /**
     * @var string $remitZip
     */
    #[JsonProperty('remitZip')]
    public string $remitZip;

    /**
     * @var string $remitCountry
     */
    #[JsonProperty('remitCountry')]
    public string $remitCountry;

    /**
     * @var string $payeeName1
     */
    #[JsonProperty('payeeName1')]
    public string $payeeName1;

    /**
     * @var string $payeeName2
     */
    #[JsonProperty('payeeName2')]
    public string $payeeName2;

    /**
     * @var string $customField1 Custom field 1 for vendor
     */
    #[JsonProperty('customField1')]
    public string $customField1;

    /**
     * @var string $customField2 Custom field 2 for vendor
     */
    #[JsonProperty('customField2')]
    public string $customField2;

    /**
     * @var ?string $customerVendorAccount Account number of paypoint in the Vendor side.
     */
    #[JsonProperty('customerVendorAccount')]
    public ?string $customerVendorAccount;

    /**
     * @var int $internalReferenceId
     */
    #[JsonProperty('InternalReferenceId')]
    public int $internalReferenceId;

    /**
     * @var array<string, string> $additionalData
     */
    #[JsonProperty('additionalData'), ArrayType(['string' => 'string'])]
    public array $additionalData;

    /**
     * @var string $externalPaypointId External paypoint identifier
     */
    #[JsonProperty('externalPaypointID')]
    public string $externalPaypointId;

    /**
     * @var array<VendorResponseStoredMethod> $storedMethods Array of stored payment methods for vendor
     */
    #[JsonProperty('StoredMethods'), ArrayType([VendorResponseStoredMethod::class])]
    public array $storedMethods;

    /**
     * @param array{
     *   vendorNumber: string,
     *   name1: string,
     *   name2: string,
     *   ein: string,
     *   phone: string,
     *   email: string,
     *   address1: string,
     *   address2: string,
     *   city: string,
     *   state: string,
     *   zip: string,
     *   country: string,
     *   mcc: string,
     *   locationCode: string,
     *   contacts: array<ContactsResponse>,
     *   billingData: VendorResponseBillingData,
     *   paymentMethod: value-of<VendorDataResponsePaymentMethod>,
     *   vendorStatus: int,
     *   vendorId: int,
     *   summary: VendorResponseSummary,
     *   paypointLegalname: string,
     *   paypointDbaname: string,
     *   paypointEntryname: string,
     *   parentOrgName: string,
     *   parentOrgId: int,
     *   createdDate: DateTime,
     *   lastUpdated: DateTime,
     *   remitAddress1: string,
     *   remitAddress2: string,
     *   remitCity: string,
     *   remitState: string,
     *   remitZip: string,
     *   remitCountry: string,
     *   payeeName1: string,
     *   payeeName2: string,
     *   customField1: string,
     *   customField2: string,
     *   internalReferenceId: int,
     *   additionalData: array<string, string>,
     *   externalPaypointId: string,
     *   storedMethods: array<VendorResponseStoredMethod>,
     *   remitEmail?: ?string,
     *   enrollmentStatus?: ?string,
     *   customerVendorAccount?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->vendorNumber = $values['vendorNumber'];
        $this->name1 = $values['name1'];
        $this->name2 = $values['name2'];
        $this->ein = $values['ein'];
        $this->phone = $values['phone'];
        $this->email = $values['email'];
        $this->remitEmail = $values['remitEmail'] ?? null;
        $this->address1 = $values['address1'];
        $this->address2 = $values['address2'];
        $this->city = $values['city'];
        $this->state = $values['state'];
        $this->zip = $values['zip'];
        $this->country = $values['country'];
        $this->mcc = $values['mcc'];
        $this->locationCode = $values['locationCode'];
        $this->contacts = $values['contacts'];
        $this->billingData = $values['billingData'];
        $this->paymentMethod = $values['paymentMethod'];
        $this->vendorStatus = $values['vendorStatus'];
        $this->vendorId = $values['vendorId'];
        $this->enrollmentStatus = $values['enrollmentStatus'] ?? null;
        $this->summary = $values['summary'];
        $this->paypointLegalname = $values['paypointLegalname'];
        $this->paypointDbaname = $values['paypointDbaname'];
        $this->paypointEntryname = $values['paypointEntryname'];
        $this->parentOrgName = $values['parentOrgName'];
        $this->parentOrgId = $values['parentOrgId'];
        $this->createdDate = $values['createdDate'];
        $this->lastUpdated = $values['lastUpdated'];
        $this->remitAddress1 = $values['remitAddress1'];
        $this->remitAddress2 = $values['remitAddress2'];
        $this->remitCity = $values['remitCity'];
        $this->remitState = $values['remitState'];
        $this->remitZip = $values['remitZip'];
        $this->remitCountry = $values['remitCountry'];
        $this->payeeName1 = $values['payeeName1'];
        $this->payeeName2 = $values['payeeName2'];
        $this->customField1 = $values['customField1'];
        $this->customField2 = $values['customField2'];
        $this->customerVendorAccount = $values['customerVendorAccount'] ?? null;
        $this->internalReferenceId = $values['internalReferenceId'];
        $this->additionalData = $values['additionalData'];
        $this->externalPaypointId = $values['externalPaypointId'];
        $this->storedMethods = $values['storedMethods'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
