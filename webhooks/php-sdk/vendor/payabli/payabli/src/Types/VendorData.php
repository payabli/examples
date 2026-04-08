<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;
use Payabli\Core\Types\Union;

class VendorData extends JsonSerializableType
{
    /**
     * @var ?string $vendorNumber
     */
    #[JsonProperty('vendorNumber')]
    public ?string $vendorNumber;

    /**
     * @var ?array<string, ?array<string, mixed>> $additionalData
     */
    #[JsonProperty('AdditionalData'), ArrayType(['string' => new Union(['string' => 'mixed'], 'null')])]
    public ?array $additionalData;

    /**
     * @var ?string $address1 Vendor's address
     */
    #[JsonProperty('address1')]
    public ?string $address1;

    /**
     * @var ?string $address2 Additional line for vendor's address.
     */
    #[JsonProperty('address2')]
    public ?string $address2;

    /**
     * @var ?BillingData $billingData Object containing vendor's bank information.
     */
    #[JsonProperty('billingData')]
    public ?BillingData $billingData;

    /**
     * @var ?string $city Vendor's city.
     */
    #[JsonProperty('city')]
    public ?string $city;

    /**
     * @var ?array<Contacts> $contacts Array of objects describing the vendor's contacts.
     */
    #[JsonProperty('contacts'), ArrayType([Contacts::class])]
    public ?array $contacts;

    /**
     * @var ?string $country Vendor's country.
     */
    #[JsonProperty('country')]
    public ?string $country;

    /**
     * @var ?string $customField1 Custom field 1 for vendor
     */
    #[JsonProperty('customField1')]
    public ?string $customField1;

    /**
     * @var ?string $customField2 Custom field 2 for vendor
     */
    #[JsonProperty('customField2')]
    public ?string $customField2;

    /**
     * @var ?string $customerVendorAccount Account number of paypoint in the vendor side.
     */
    #[JsonProperty('customerVendorAccount')]
    public ?string $customerVendorAccount;

    /**
     * @var ?string $ein
     */
    #[JsonProperty('ein')]
    public ?string $ein;

    /**
     * @var ?string $email Vendor's email address. Required for vCard.
     */
    #[JsonProperty('email')]
    public ?string $email;

    /**
     * @var ?int $internalReferenceId Internal identifier for global vendor account.
     */
    #[JsonProperty('internalReferenceId')]
    public ?int $internalReferenceId;

    /**
     * @var ?string $locationCode
     */
    #[JsonProperty('locationCode')]
    public ?string $locationCode;

    /**
     * @var ?string $mcc
     */
    #[JsonProperty('mcc')]
    public ?string $mcc;

    /**
     * @var ?string $name1
     */
    #[JsonProperty('name1')]
    public ?string $name1;

    /**
     * @var ?string $name2
     */
    #[JsonProperty('name2')]
    public ?string $name2;

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
    #[JsonProperty('paymentMethod')]
    public ?string $paymentMethod;

    /**
     * @var ?string $phone
     */
    #[JsonProperty('phone')]
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
    #[JsonProperty('remitEmail')]
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
     * @var ?string $state Vendor's state. Must be a 2 character state code.
     */
    #[JsonProperty('state')]
    public ?string $state;

    /**
     * @var ?int $vendorStatus
     */
    #[JsonProperty('vendorStatus')]
    public ?int $vendorStatus;

    /**
     * @var ?string $zip Vendor's zip code.
     */
    #[JsonProperty('zip')]
    public ?string $zip;

    /**
     * @param array{
     *   vendorNumber?: ?string,
     *   additionalData?: ?array<string, ?array<string, mixed>>,
     *   address1?: ?string,
     *   address2?: ?string,
     *   billingData?: ?BillingData,
     *   city?: ?string,
     *   contacts?: ?array<Contacts>,
     *   country?: ?string,
     *   customField1?: ?string,
     *   customField2?: ?string,
     *   customerVendorAccount?: ?string,
     *   ein?: ?string,
     *   email?: ?string,
     *   internalReferenceId?: ?int,
     *   locationCode?: ?string,
     *   mcc?: ?string,
     *   name1?: ?string,
     *   name2?: ?string,
     *   payeeName1?: ?string,
     *   payeeName2?: ?string,
     *   paymentMethod?: ?string,
     *   phone?: ?string,
     *   remitAddress1?: ?string,
     *   remitAddress2?: ?string,
     *   remitCity?: ?string,
     *   remitCountry?: ?string,
     *   remitEmail?: ?string,
     *   remitState?: ?string,
     *   remitZip?: ?string,
     *   state?: ?string,
     *   vendorStatus?: ?int,
     *   zip?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->vendorNumber = $values['vendorNumber'] ?? null;
        $this->additionalData = $values['additionalData'] ?? null;
        $this->address1 = $values['address1'] ?? null;
        $this->address2 = $values['address2'] ?? null;
        $this->billingData = $values['billingData'] ?? null;
        $this->city = $values['city'] ?? null;
        $this->contacts = $values['contacts'] ?? null;
        $this->country = $values['country'] ?? null;
        $this->customField1 = $values['customField1'] ?? null;
        $this->customField2 = $values['customField2'] ?? null;
        $this->customerVendorAccount = $values['customerVendorAccount'] ?? null;
        $this->ein = $values['ein'] ?? null;
        $this->email = $values['email'] ?? null;
        $this->internalReferenceId = $values['internalReferenceId'] ?? null;
        $this->locationCode = $values['locationCode'] ?? null;
        $this->mcc = $values['mcc'] ?? null;
        $this->name1 = $values['name1'] ?? null;
        $this->name2 = $values['name2'] ?? null;
        $this->payeeName1 = $values['payeeName1'] ?? null;
        $this->payeeName2 = $values['payeeName2'] ?? null;
        $this->paymentMethod = $values['paymentMethod'] ?? null;
        $this->phone = $values['phone'] ?? null;
        $this->remitAddress1 = $values['remitAddress1'] ?? null;
        $this->remitAddress2 = $values['remitAddress2'] ?? null;
        $this->remitCity = $values['remitCity'] ?? null;
        $this->remitCountry = $values['remitCountry'] ?? null;
        $this->remitEmail = $values['remitEmail'] ?? null;
        $this->remitState = $values['remitState'] ?? null;
        $this->remitZip = $values['remitZip'] ?? null;
        $this->state = $values['state'] ?? null;
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
