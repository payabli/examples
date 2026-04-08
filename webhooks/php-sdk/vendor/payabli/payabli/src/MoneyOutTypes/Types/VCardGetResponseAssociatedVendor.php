<?php

namespace Payabli\MoneyOutTypes\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

/**
 * Information about the associated vendor.
 */
class VCardGetResponseAssociatedVendor extends JsonSerializableType
{
    /**
     * @var ?string $vendorNumber Unique code identifying the vendor.
     */
    #[JsonProperty('VendorNumber')]
    public ?string $vendorNumber;

    /**
     * @var ?string $name1 The primary name associated with the vendor.
     */
    #[JsonProperty('Name1')]
    public ?string $name1;

    /**
     * @var ?string $name2 Additional name information for the vendor.
     */
    #[JsonProperty('Name2')]
    public ?string $name2;

    /**
     * @var ?string $ein Employer Identification Number of the vendor.
     */
    #[JsonProperty('EIN')]
    public ?string $ein;

    /**
     * @var ?string $phone Contact phone number of the vendor.
     */
    #[JsonProperty('Phone')]
    public ?string $phone;

    /**
     * @var ?string $email Contact email address of the vendor.
     */
    #[JsonProperty('Email')]
    public ?string $email;

    /**
     * @var ?string $remitEmail Email address for remittance.
     */
    #[JsonProperty('RemitEmail')]
    public ?string $remitEmail;

    /**
     * @var ?string $address1 Primary address line of the vendor.
     */
    #[JsonProperty('Address1')]
    public ?string $address1;

    /**
     * @var ?string $address2 Secondary address line of the vendor.
     */
    #[JsonProperty('Address2')]
    public ?string $address2;

    /**
     * @var ?string $city City where the vendor is located.
     */
    #[JsonProperty('City')]
    public ?string $city;

    /**
     * @var ?string $state State where the vendor is located.
     */
    #[JsonProperty('State')]
    public ?string $state;

    /**
     * @var ?string $zip ZIP code for the vendor's location.
     */
    #[JsonProperty('Zip')]
    public ?string $zip;

    /**
     * @var ?string $country Country where the vendor is located.
     */
    #[JsonProperty('Country')]
    public ?string $country;

    /**
     * @var ?string $mcc Merchant Category Code for the vendor.
     */
    #[JsonProperty('Mcc')]
    public ?string $mcc;

    /**
     * @var ?string $locationCode
     */
    #[JsonProperty('LocationCode')]
    public ?string $locationCode;

    /**
     * @var ?array<VCardGetResponseContact> $contacts Array of objects describing the vendor's contacts.
     */
    #[JsonProperty('Contacts'), ArrayType([VCardGetResponseContact::class])]
    public ?array $contacts;

    /**
     * @var ?VCardGetResponseAssociatedVendorBillingData $billingData Billing data for the vendor.
     */
    #[JsonProperty('BillingData')]
    public ?VCardGetResponseAssociatedVendorBillingData $billingData;

    /**
     * @var ?string $paymentMethod Preferred payment method for vendor.
     */
    #[JsonProperty('PaymentMethod')]
    public ?string $paymentMethod;

    /**
     * @var ?int $vendorStatus Status of the vendor.
     */
    #[JsonProperty('VendorStatus')]
    public ?int $vendorStatus;

    /**
     * @var ?int $vendorId Unique identifier for the vendor.
     */
    #[JsonProperty('VendorId')]
    public ?int $vendorId;

    /**
     * @var ?string $enrollmentStatus Enrollment status of the vendor.
     */
    #[JsonProperty('EnrollmentStatus')]
    public ?string $enrollmentStatus;

    /**
     * @var ?VCardGetResponseAssociatedVendorSummary $summary Summary of vendor's billing and transaction status.
     */
    #[JsonProperty('Summary')]
    public ?VCardGetResponseAssociatedVendorSummary $summary;

    /**
     * @var ?string $paypointLegalname Legal name of the paypoint.
     */
    #[JsonProperty('PaypointLegalname')]
    public ?string $paypointLegalname;

    /**
     * @var ?string $paypointDbaname DBA name of the paypoint.
     */
    #[JsonProperty('PaypointDbaname')]
    public ?string $paypointDbaname;

    /**
     * @var ?string $paypointEntryname Entryname of the paypoint.
     */
    #[JsonProperty('PaypointEntryname')]
    public ?string $paypointEntryname;

    /**
     * @var ?string $parentOrgName
     */
    #[JsonProperty('ParentOrgName')]
    public ?string $parentOrgName;

    /**
     * @var ?int $parentOrgId ID of the parent organization.
     */
    #[JsonProperty('ParentOrgId')]
    public ?int $parentOrgId;

    /**
     * @var ?string $createdDate Date when the vendor record was created.
     */
    #[JsonProperty('CreatedDate')]
    public ?string $createdDate;

    /**
     * @var ?string $lastUpdated Date when the vendor's information was last updated.
     */
    #[JsonProperty('LastUpdated')]
    public ?string $lastUpdated;

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
     * @var ?string $payeeName1 Primary name of the payee.
     */
    #[JsonProperty('payeeName1')]
    public ?string $payeeName1;

    /**
     * @var ?string $payeeName2 Secondary name of the payee.
     */
    #[JsonProperty('payeeName2')]
    public ?string $payeeName2;

    /**
     * @var ?string $customField1 A custom field for additional data.
     */
    #[JsonProperty('customField1')]
    public ?string $customField1;

    /**
     * @var ?string $customField2 Another custom field for extra data.
     */
    #[JsonProperty('customField2')]
    public ?string $customField2;

    /**
     * @var ?string $customerVendorAccount Account number of paypoint in the vendor side.
     */
    #[JsonProperty('customerVendorAccount')]
    public ?string $customerVendorAccount;

    /**
     * @var ?int $internalReferenceId Internal reference ID used within the system.
     */
    #[JsonProperty('InternalReferenceId')]
    public ?int $internalReferenceId;

    /**
     * @var ?string $additionalData Field for additional data, if any.
     */
    #[JsonProperty('additionalData')]
    public ?string $additionalData;

    /**
     * @var ?string $externalPaypointId
     */
    #[JsonProperty('externalPaypointID')]
    public ?string $externalPaypointId;

    /**
     * @var ?string $storedMethods Stored payment methods for the vendor.
     */
    #[JsonProperty('StoredMethods')]
    public ?string $storedMethods;

    /**
     * @param array{
     *   vendorNumber?: ?string,
     *   name1?: ?string,
     *   name2?: ?string,
     *   ein?: ?string,
     *   phone?: ?string,
     *   email?: ?string,
     *   remitEmail?: ?string,
     *   address1?: ?string,
     *   address2?: ?string,
     *   city?: ?string,
     *   state?: ?string,
     *   zip?: ?string,
     *   country?: ?string,
     *   mcc?: ?string,
     *   locationCode?: ?string,
     *   contacts?: ?array<VCardGetResponseContact>,
     *   billingData?: ?VCardGetResponseAssociatedVendorBillingData,
     *   paymentMethod?: ?string,
     *   vendorStatus?: ?int,
     *   vendorId?: ?int,
     *   enrollmentStatus?: ?string,
     *   summary?: ?VCardGetResponseAssociatedVendorSummary,
     *   paypointLegalname?: ?string,
     *   paypointDbaname?: ?string,
     *   paypointEntryname?: ?string,
     *   parentOrgName?: ?string,
     *   parentOrgId?: ?int,
     *   createdDate?: ?string,
     *   lastUpdated?: ?string,
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
     *   additionalData?: ?string,
     *   externalPaypointId?: ?string,
     *   storedMethods?: ?string,
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
        $this->remitEmail = $values['remitEmail'] ?? null;
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
        $this->parentOrgId = $values['parentOrgId'] ?? null;
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
        $this->storedMethods = $values['storedMethods'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
