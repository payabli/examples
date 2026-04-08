<?php

namespace Payabli\Ocr\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Types\Contacts;
use Payabli\Core\Types\ArrayType;

class OcrVendor extends JsonSerializableType
{
    /**
     * @var ?string $vendorNumber
     */
    #[JsonProperty('vendorNumber')]
    public ?string $vendorNumber;

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
     * @var ?string $ein
     */
    #[JsonProperty('ein')]
    public ?string $ein;

    /**
     * @var ?string $phone
     */
    #[JsonProperty('phone')]
    public ?string $phone;

    /**
     * @var ?string $email
     */
    #[JsonProperty('email')]
    public ?string $email;

    /**
     * @var ?string $address1
     */
    #[JsonProperty('address1')]
    public ?string $address1;

    /**
     * @var ?string $address2
     */
    #[JsonProperty('address2')]
    public ?string $address2;

    /**
     * @var ?string $city
     */
    #[JsonProperty('city')]
    public ?string $city;

    /**
     * @var ?string $state
     */
    #[JsonProperty('state')]
    public ?string $state;

    /**
     * @var ?string $zip
     */
    #[JsonProperty('zip')]
    public ?string $zip;

    /**
     * @var ?string $country
     */
    #[JsonProperty('country')]
    public ?string $country;

    /**
     * @var ?string $mcc
     */
    #[JsonProperty('mcc')]
    public ?string $mcc;

    /**
     * @var ?string $locationCode
     */
    #[JsonProperty('locationCode')]
    public ?string $locationCode;

    /**
     * @var ?array<Contacts> $contacts
     */
    #[JsonProperty('contacts'), ArrayType([Contacts::class])]
    public ?array $contacts;

    /**
     * @var ?OcrVendorBillingData $billingData
     */
    #[JsonProperty('billingData')]
    public ?OcrVendorBillingData $billingData;

    /**
     * @var ?string $paymentMethod
     */
    #[JsonProperty('paymentMethod')]
    public ?string $paymentMethod;

    /**
     * @var ?int $vendorStatus
     */
    #[JsonProperty('vendorStatus')]
    public ?int $vendorStatus;

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
     * @var ?string $customerVendorAccount
     */
    #[JsonProperty('customerVendorAccount')]
    public ?string $customerVendorAccount;

    /**
     * @var ?int $internalReferenceId
     */
    #[JsonProperty('internalReferenceId')]
    public ?int $internalReferenceId;

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
     * @var ?OcrVendorAdditionalData $additionalData
     */
    #[JsonProperty('additionalData')]
    public ?OcrVendorAdditionalData $additionalData;

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
     *   billingData?: ?OcrVendorBillingData,
     *   paymentMethod?: ?string,
     *   vendorStatus?: ?int,
     *   remitAddress1?: ?string,
     *   remitAddress2?: ?string,
     *   remitCity?: ?string,
     *   remitState?: ?string,
     *   remitZip?: ?string,
     *   remitCountry?: ?string,
     *   payeeName1?: ?string,
     *   payeeName2?: ?string,
     *   customerVendorAccount?: ?string,
     *   internalReferenceId?: ?int,
     *   customField1?: ?string,
     *   customField2?: ?string,
     *   additionalData?: ?OcrVendorAdditionalData,
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
        $this->remitAddress1 = $values['remitAddress1'] ?? null;
        $this->remitAddress2 = $values['remitAddress2'] ?? null;
        $this->remitCity = $values['remitCity'] ?? null;
        $this->remitState = $values['remitState'] ?? null;
        $this->remitZip = $values['remitZip'] ?? null;
        $this->remitCountry = $values['remitCountry'] ?? null;
        $this->payeeName1 = $values['payeeName1'] ?? null;
        $this->payeeName2 = $values['payeeName2'] ?? null;
        $this->customerVendorAccount = $values['customerVendorAccount'] ?? null;
        $this->internalReferenceId = $values['internalReferenceId'] ?? null;
        $this->customField1 = $values['customField1'] ?? null;
        $this->customField2 = $values['customField2'] ?? null;
        $this->additionalData = $values['additionalData'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
