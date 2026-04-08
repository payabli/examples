<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;
use DateTime;
use Payabli\Core\Types\Date;

class BillData extends JsonSerializableType
{
    /**
     * @var ?string $additionalData
     */
    #[JsonProperty('AdditionalData')]
    public ?string $additionalData;

    /**
     * @var ?array<FileContent> $attachments
     */
    #[JsonProperty('attachments'), ArrayType([FileContent::class])]
    public ?array $attachments;

    /**
     * @var ?string $company Company name of the recipient of the invoice.
     */
    #[JsonProperty('company')]
    public ?string $company;

    /**
     * @var ?float $discount
     */
    #[JsonProperty('discount')]
    public ?float $discount;

    /**
     * @var ?float $dutyAmount
     */
    #[JsonProperty('dutyAmount')]
    public ?float $dutyAmount;

    /**
     * @var ?string $firstName First name of the recipient of the invoice.
     */
    #[JsonProperty('firstName')]
    public ?string $firstName;

    /**
     * @var ?float $freightAmount
     */
    #[JsonProperty('freightAmount')]
    public ?float $freightAmount;

    /**
     * @var ?value-of<Frequency> $frequency Frequency of scheduled invoice.
     */
    #[JsonProperty('frequency')]
    public ?string $frequency;

    /**
     * @var ?float $invoiceAmount
     */
    #[JsonProperty('invoiceAmount')]
    public ?float $invoiceAmount;

    /**
     * @var ?DateTime $invoiceDate Invoice date in any of the accepted formats: YYYY-MM-DD, MM/DD/YYYY.
     */
    #[JsonProperty('invoiceDate'), Date(Date::TYPE_DATE)]
    public ?DateTime $invoiceDate;

    /**
     * @var ?DateTime $invoiceDueDate Invoice due date in one of the accepted formats: YYYY-MM-DD, MM/DD/YYYY.
     */
    #[JsonProperty('invoiceDueDate'), Date(Date::TYPE_DATE)]
    public ?DateTime $invoiceDueDate;

    /**
     * @var ?DateTime $invoiceEndDate Indicate the date to finish a scheduled invoice cycle (`invoiceType`` = 1) in any of the accepted formats: YYYY-MM-DD, MM/DD/YYYY.
     */
    #[JsonProperty('invoiceEndDate'), Date(Date::TYPE_DATE)]
    public ?DateTime $invoiceEndDate;

    /**
     * @var ?string $invoiceNumber Invoice number. Identifies the invoice under a paypoint.
     */
    #[JsonProperty('invoiceNumber')]
    public ?string $invoiceNumber;

    /**
     * @var ?int $invoiceStatus
     */
    #[JsonProperty('invoiceStatus')]
    public ?int $invoiceStatus;

    /**
     * @var ?int $invoiceType
     */
    #[JsonProperty('invoiceType')]
    public ?int $invoiceType;

    /**
     * @var ?array<BillItem> $items Array of line items included in the invoice.
     */
    #[JsonProperty('items'), ArrayType([BillItem::class])]
    public ?array $items;

    /**
     * @var ?string $lastName Last name of the recipient of the invoice.
     */
    #[JsonProperty('lastName')]
    public ?string $lastName;

    /**
     * @var ?string $notes Notes included in the invoice.
     */
    #[JsonProperty('notes')]
    public ?string $notes;

    /**
     * @var ?value-of<BillDataPaymentTerms> $paymentTerms
     */
    #[JsonProperty('paymentTerms')]
    public ?string $paymentTerms;

    /**
     * @var ?string $purchaseOrder
     */
    #[JsonProperty('purchaseOrder')]
    public ?string $purchaseOrder;

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
     * @var ?string $shippingEmail Shipping recipient's contact email address.
     */
    #[JsonProperty('shippingEmail')]
    public ?string $shippingEmail;

    /**
     * @var ?string $shippingFromZip
     */
    #[JsonProperty('shippingFromZip')]
    public ?string $shippingFromZip;

    /**
     * @var ?string $shippingPhone Recipient phone number.
     */
    #[JsonProperty('shippingPhone')]
    public ?string $shippingPhone;

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

    /**
     * @var ?string $summaryCommodityCode
     */
    #[JsonProperty('summaryCommodityCode')]
    public ?string $summaryCommodityCode;

    /**
     * @var ?float $tax
     */
    #[JsonProperty('tax')]
    public ?float $tax;

    /**
     * @var ?string $termsConditions
     */
    #[JsonProperty('termsConditions')]
    public ?string $termsConditions;

    /**
     * @param array{
     *   additionalData?: ?string,
     *   attachments?: ?array<FileContent>,
     *   company?: ?string,
     *   discount?: ?float,
     *   dutyAmount?: ?float,
     *   firstName?: ?string,
     *   freightAmount?: ?float,
     *   frequency?: ?value-of<Frequency>,
     *   invoiceAmount?: ?float,
     *   invoiceDate?: ?DateTime,
     *   invoiceDueDate?: ?DateTime,
     *   invoiceEndDate?: ?DateTime,
     *   invoiceNumber?: ?string,
     *   invoiceStatus?: ?int,
     *   invoiceType?: ?int,
     *   items?: ?array<BillItem>,
     *   lastName?: ?string,
     *   notes?: ?string,
     *   paymentTerms?: ?value-of<BillDataPaymentTerms>,
     *   purchaseOrder?: ?string,
     *   shippingAddress1?: ?string,
     *   shippingAddress2?: ?string,
     *   shippingCity?: ?string,
     *   shippingCountry?: ?string,
     *   shippingEmail?: ?string,
     *   shippingFromZip?: ?string,
     *   shippingPhone?: ?string,
     *   shippingState?: ?string,
     *   shippingZip?: ?string,
     *   summaryCommodityCode?: ?string,
     *   tax?: ?float,
     *   termsConditions?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->additionalData = $values['additionalData'] ?? null;
        $this->attachments = $values['attachments'] ?? null;
        $this->company = $values['company'] ?? null;
        $this->discount = $values['discount'] ?? null;
        $this->dutyAmount = $values['dutyAmount'] ?? null;
        $this->firstName = $values['firstName'] ?? null;
        $this->freightAmount = $values['freightAmount'] ?? null;
        $this->frequency = $values['frequency'] ?? null;
        $this->invoiceAmount = $values['invoiceAmount'] ?? null;
        $this->invoiceDate = $values['invoiceDate'] ?? null;
        $this->invoiceDueDate = $values['invoiceDueDate'] ?? null;
        $this->invoiceEndDate = $values['invoiceEndDate'] ?? null;
        $this->invoiceNumber = $values['invoiceNumber'] ?? null;
        $this->invoiceStatus = $values['invoiceStatus'] ?? null;
        $this->invoiceType = $values['invoiceType'] ?? null;
        $this->items = $values['items'] ?? null;
        $this->lastName = $values['lastName'] ?? null;
        $this->notes = $values['notes'] ?? null;
        $this->paymentTerms = $values['paymentTerms'] ?? null;
        $this->purchaseOrder = $values['purchaseOrder'] ?? null;
        $this->shippingAddress1 = $values['shippingAddress1'] ?? null;
        $this->shippingAddress2 = $values['shippingAddress2'] ?? null;
        $this->shippingCity = $values['shippingCity'] ?? null;
        $this->shippingCountry = $values['shippingCountry'] ?? null;
        $this->shippingEmail = $values['shippingEmail'] ?? null;
        $this->shippingFromZip = $values['shippingFromZip'] ?? null;
        $this->shippingPhone = $values['shippingPhone'] ?? null;
        $this->shippingState = $values['shippingState'] ?? null;
        $this->shippingZip = $values['shippingZip'] ?? null;
        $this->summaryCommodityCode = $values['summaryCommodityCode'] ?? null;
        $this->tax = $values['tax'] ?? null;
        $this->termsConditions = $values['termsConditions'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
