<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;
use Payabli\Core\Types\Union;

class VendorOutData extends JsonSerializableType
{
    /**
     * @var ?array<string, ?array<string, mixed>> $additionalData
     */
    #[JsonProperty('additionalData'), ArrayType(['string' => new Union(['string' => 'mixed'], 'null')])]
    public ?array $additionalData;

    /**
     * @var ?string $address1 Vendor's address
     */
    #[JsonProperty('Address1')]
    public ?string $address1;

    /**
     * @var ?string $address2 Additional line for vendor's address.
     */
    #[JsonProperty('Address2')]
    public ?string $address2;

    /**
     * @var ?BillingData $billingData Object containing vendor's bank information.
     */
    #[JsonProperty('BillingData')]
    public ?BillingData $billingData;

    /**
     * @var string $city Vendor's city.
     */
    #[JsonProperty('City')]
    public string $city;

    /**
     * @var ?array<Contacts> $contacts Array of objects describing the vendor's contacts.
     */
    #[JsonProperty('Contacts'), ArrayType([Contacts::class])]
    public ?array $contacts;

    /**
     * @var string $country Vendor's country.
     */
    #[JsonProperty('Country')]
    public string $country;

    /**
     * @var ?string $customerVendorAccount Account number of paypoint in the vendor side.
     */
    #[JsonProperty('customerVendorAccount')]
    public ?string $customerVendorAccount;

    /**
     * @var string $ein EIN/Tax ID for vendor. In reponses, this field is masked, and looks like: `XXXXX6789`.
     */
    #[JsonProperty('EIN')]
    public string $ein;

    /**
     * @var ?string $email Vendor's email address. Required for vCard.
     */
    #[JsonProperty('Email')]
    public ?string $email;

    /**
     * @var ?int $internalReferenceId Internal identifier for global vendor account.
     */
    #[JsonProperty('InternalReferenceId')]
    public ?int $internalReferenceId;

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
     * @var string $name1 Primary name for vendor. Required for new vendor.
     */
    #[JsonProperty('Name1')]
    public string $name1;

    /**
     * @var ?string $name2 Secondary name for vendor.
     */
    #[JsonProperty('Name2')]
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
     * @var ?VendorPaymentMethod $paymentMethod
     */
    #[JsonProperty('PaymentMethod')]
    public ?VendorPaymentMethod $paymentMethod;

    /**
     * @var string $phone Vendor's phone number
     */
    #[JsonProperty('Phone')]
    public string $phone;

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
     * @var string $state Vendor's state. Must be a 2 character state code.
     */
    #[JsonProperty('State')]
    public string $state;

    /**
     * @var ?int $vendorId Payabli identifier for vendor record. Required when `VendorNumber` isn't included.
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
     * @var string $zip Vendor's zip code.
     */
    #[JsonProperty('Zip')]
    public string $zip;

    /**
     * @param array{
     *   city: string,
     *   country: string,
     *   ein: string,
     *   name1: string,
     *   phone: string,
     *   state: string,
     *   zip: string,
     *   additionalData?: ?array<string, ?array<string, mixed>>,
     *   address1?: ?string,
     *   address2?: ?string,
     *   billingData?: ?BillingData,
     *   contacts?: ?array<Contacts>,
     *   customerVendorAccount?: ?string,
     *   email?: ?string,
     *   internalReferenceId?: ?int,
     *   locationCode?: ?string,
     *   mcc?: ?string,
     *   name2?: ?string,
     *   payeeName1?: ?string,
     *   payeeName2?: ?string,
     *   paymentMethod?: ?VendorPaymentMethod,
     *   remitAddress1?: ?string,
     *   remitAddress2?: ?string,
     *   remitCity?: ?string,
     *   remitCountry?: ?string,
     *   remitState?: ?string,
     *   remitZip?: ?string,
     *   vendorId?: ?int,
     *   vendorNumber?: ?string,
     *   vendorStatus?: ?int,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->additionalData = $values['additionalData'] ?? null;
        $this->address1 = $values['address1'] ?? null;
        $this->address2 = $values['address2'] ?? null;
        $this->billingData = $values['billingData'] ?? null;
        $this->city = $values['city'];
        $this->contacts = $values['contacts'] ?? null;
        $this->country = $values['country'];
        $this->customerVendorAccount = $values['customerVendorAccount'] ?? null;
        $this->ein = $values['ein'];
        $this->email = $values['email'] ?? null;
        $this->internalReferenceId = $values['internalReferenceId'] ?? null;
        $this->locationCode = $values['locationCode'] ?? null;
        $this->mcc = $values['mcc'] ?? null;
        $this->name1 = $values['name1'];
        $this->name2 = $values['name2'] ?? null;
        $this->payeeName1 = $values['payeeName1'] ?? null;
        $this->payeeName2 = $values['payeeName2'] ?? null;
        $this->paymentMethod = $values['paymentMethod'] ?? null;
        $this->phone = $values['phone'];
        $this->remitAddress1 = $values['remitAddress1'] ?? null;
        $this->remitAddress2 = $values['remitAddress2'] ?? null;
        $this->remitCity = $values['remitCity'] ?? null;
        $this->remitCountry = $values['remitCountry'] ?? null;
        $this->remitState = $values['remitState'] ?? null;
        $this->remitZip = $values['remitZip'] ?? null;
        $this->state = $values['state'];
        $this->vendorId = $values['vendorId'] ?? null;
        $this->vendorNumber = $values['vendorNumber'] ?? null;
        $this->vendorStatus = $values['vendorStatus'] ?? null;
        $this->zip = $values['zip'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
