<?php

namespace Payabli\Invoice\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use DateTime;
use Payabli\Core\Types\Date;
use Payabli\Types\Frequency;
use Payabli\Types\BillItem;
use Payabli\Core\Types\ArrayType;
use Payabli\Types\PayorDataResponse;
use Payabli\Types\GeneralEvents;
use Payabli\Types\BillOptions;
use Payabli\Types\DocumentsRef;

class QueryInvoiceResponseRecordsItem extends JsonSerializableType
{
    /**
     * @var int $invoiceId
     */
    #[JsonProperty('invoiceId')]
    public int $invoiceId;

    /**
     * @var int $customerId
     */
    #[JsonProperty('customerId')]
    public int $customerId;

    /**
     * @var int $paypointId
     */
    #[JsonProperty('paypointId')]
    public int $paypointId;

    /**
     * @var string $invoiceNumber
     */
    #[JsonProperty('invoiceNumber')]
    public string $invoiceNumber;

    /**
     * @var ?DateTime $invoiceDate Invoice date in any of the accepted formats: YYYY-MM-DD, MM/DD/YYYY.
     */
    #[JsonProperty('invoiceDate'), Date(Date::TYPE_DATE)]
    public ?DateTime $invoiceDate;

    /**
     * @var ?DateTime $invoiceDueDate Invoice due date in any of the accepted formats: YYYY-MM-DD, MM/DD/YYYY.
     */
    #[JsonProperty('invoiceDueDate'), Date(Date::TYPE_DATE)]
    public ?DateTime $invoiceDueDate;

    /**
     * @var ?DateTime $invoiceSentDate Invoice sent date in any of the accepted formats: YYYY-MM-DD, MM/DD/YYYY.
     */
    #[JsonProperty('invoiceSentDate'), Date(Date::TYPE_DATE)]
    public ?DateTime $invoiceSentDate;

    /**
     * @var ?DateTime $invoiceEndDate The end date for a scheduled invoice cycle (`invoiceType` = 1).
     */
    #[JsonProperty('invoiceEndDate'), Date(Date::TYPE_DATE)]
    public ?DateTime $invoiceEndDate;

    /**
     * @var ?DateTime $lastPaymentDate Timestamp of last payment.
     */
    #[JsonProperty('lastPaymentDate'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $lastPaymentDate;

    /**
     * @var DateTime $createdAt
     */
    #[JsonProperty('createdAt'), Date(Date::TYPE_DATETIME)]
    public DateTime $createdAt;

    /**
     * @var int $invoiceStatus
     */
    #[JsonProperty('invoiceStatus')]
    public int $invoiceStatus;

    /**
     * @var int $invoiceType
     */
    #[JsonProperty('invoiceType')]
    public int $invoiceType;

    /**
     * @var value-of<Frequency> $frequency Frequency of scheduled invoice.
     */
    #[JsonProperty('frequency')]
    public string $frequency;

    /**
     * @var string $paymentTerms
     */
    #[JsonProperty('paymentTerms')]
    public string $paymentTerms;

    /**
     * @var ?string $termsConditions
     */
    #[JsonProperty('termsConditions')]
    public ?string $termsConditions;

    /**
     * @var ?string $notes Invoice notes.
     */
    #[JsonProperty('notes')]
    public ?string $notes;

    /**
     * @var ?float $tax
     */
    #[JsonProperty('tax')]
    public ?float $tax;

    /**
     * @var ?float $discount
     */
    #[JsonProperty('discount')]
    public ?float $discount;

    /**
     * @var float $invoiceAmount
     */
    #[JsonProperty('invoiceAmount')]
    public float $invoiceAmount;

    /**
     * @var float $invoicePaidAmount
     */
    #[JsonProperty('invoicePaidAmount')]
    public float $invoicePaidAmount;

    /**
     * @var ?float $freightAmount
     */
    #[JsonProperty('freightAmount')]
    public ?float $freightAmount;

    /**
     * @var ?float $dutyAmount
     */
    #[JsonProperty('dutyAmount')]
    public ?float $dutyAmount;

    /**
     * @var ?string $purchaseOrder
     */
    #[JsonProperty('purchaseOrder')]
    public ?string $purchaseOrder;

    /**
     * @var string $firstName First name of the recipient of the invoice.
     */
    #[JsonProperty('firstName')]
    public string $firstName;

    /**
     * @var string $lastName Last name of the recipient of the invoice.
     */
    #[JsonProperty('lastName')]
    public string $lastName;

    /**
     * @var ?string $company Company name of the recipient of the invoice.
     */
    #[JsonProperty('company')]
    public ?string $company;

    /**
     * @var string $shippingAddress1
     */
    #[JsonProperty('shippingAddress1')]
    public string $shippingAddress1;

    /**
     * @var string $shippingAddress2
     */
    #[JsonProperty('shippingAddress2')]
    public string $shippingAddress2;

    /**
     * @var string $shippingCity
     */
    #[JsonProperty('shippingCity')]
    public string $shippingCity;

    /**
     * @var string $shippingState
     */
    #[JsonProperty('shippingState')]
    public string $shippingState;

    /**
     * @var string $shippingZip
     */
    #[JsonProperty('shippingZip')]
    public string $shippingZip;

    /**
     * @var string $shippingFromZip
     */
    #[JsonProperty('shippingFromZip')]
    public string $shippingFromZip;

    /**
     * @var string $shippingCountry
     */
    #[JsonProperty('shippingCountry')]
    public string $shippingCountry;

    /**
     * @var string $shippingEmail Shipping recipient's contact email address.
     */
    #[JsonProperty('shippingEmail')]
    public string $shippingEmail;

    /**
     * @var string $shippingPhone Recipient phone number.
     */
    #[JsonProperty('shippingPhone')]
    public string $shippingPhone;

    /**
     * @var ?string $summaryCommodityCode
     */
    #[JsonProperty('summaryCommodityCode')]
    public ?string $summaryCommodityCode;

    /**
     * @var array<BillItem> $items Array of line items included in the invoice.
     */
    #[JsonProperty('items'), ArrayType([BillItem::class])]
    public array $items;

    /**
     * @var PayorDataResponse $customer
     */
    #[JsonProperty('Customer')]
    public PayorDataResponse $customer;

    /**
     * @var string $paylinkId
     */
    #[JsonProperty('paylinkId')]
    public string $paylinkId;

    /**
     * @var ?array<GeneralEvents> $billEvents
     */
    #[JsonProperty('billEvents'), ArrayType([GeneralEvents::class])]
    public ?array $billEvents;

    /**
     * @var ?BillOptions $scheduledOptions Object with options for scheduled invoices.
     */
    #[JsonProperty('scheduledOptions')]
    public ?BillOptions $scheduledOptions;

    /**
     * @var string $paypointLegalname Paypoint's legal name.
     */
    #[JsonProperty('PaypointLegalname')]
    public string $paypointLegalname;

    /**
     * @var string $paypointDbaname Paypoint's DBA name.
     */
    #[JsonProperty('PaypointDbaname')]
    public string $paypointDbaname;

    /**
     * @var string $paypointEntryname Paypoint's entryname.
     */
    #[JsonProperty('PaypointEntryname')]
    public string $paypointEntryname;

    /**
     * @var int $parentOrgId
     */
    #[JsonProperty('ParentOrgId')]
    public int $parentOrgId;

    /**
     * @var string $parentOrgName
     */
    #[JsonProperty('ParentOrgName')]
    public string $parentOrgName;

    /**
     * @var ?array<string, mixed> $additionalData Custom list of key:value pairs. This field is used to store any data related to the invoice or for your system.
     */
    #[JsonProperty('AdditionalData'), ArrayType(['string' => 'mixed'])]
    public ?array $additionalData;

    /**
     * @var ?DocumentsRef $documentsRef Object containing attachments associated to the invoice.
     */
    #[JsonProperty('DocumentsRef')]
    public ?DocumentsRef $documentsRef;

    /**
     * @var string $externalPaypointId
     */
    #[JsonProperty('externalPaypointID')]
    public string $externalPaypointId;

    /**
     * @var ?string $pageIdentifier
     */
    #[JsonProperty('pageIdentifier')]
    public ?string $pageIdentifier;

    /**
     * @param array{
     *   invoiceId: int,
     *   customerId: int,
     *   paypointId: int,
     *   invoiceNumber: string,
     *   createdAt: DateTime,
     *   invoiceStatus: int,
     *   invoiceType: int,
     *   frequency: value-of<Frequency>,
     *   paymentTerms: string,
     *   invoiceAmount: float,
     *   invoicePaidAmount: float,
     *   firstName: string,
     *   lastName: string,
     *   shippingAddress1: string,
     *   shippingAddress2: string,
     *   shippingCity: string,
     *   shippingState: string,
     *   shippingZip: string,
     *   shippingFromZip: string,
     *   shippingCountry: string,
     *   shippingEmail: string,
     *   shippingPhone: string,
     *   items: array<BillItem>,
     *   customer: PayorDataResponse,
     *   paylinkId: string,
     *   paypointLegalname: string,
     *   paypointDbaname: string,
     *   paypointEntryname: string,
     *   parentOrgId: int,
     *   parentOrgName: string,
     *   externalPaypointId: string,
     *   invoiceDate?: ?DateTime,
     *   invoiceDueDate?: ?DateTime,
     *   invoiceSentDate?: ?DateTime,
     *   invoiceEndDate?: ?DateTime,
     *   lastPaymentDate?: ?DateTime,
     *   termsConditions?: ?string,
     *   notes?: ?string,
     *   tax?: ?float,
     *   discount?: ?float,
     *   freightAmount?: ?float,
     *   dutyAmount?: ?float,
     *   purchaseOrder?: ?string,
     *   company?: ?string,
     *   summaryCommodityCode?: ?string,
     *   billEvents?: ?array<GeneralEvents>,
     *   scheduledOptions?: ?BillOptions,
     *   additionalData?: ?array<string, mixed>,
     *   documentsRef?: ?DocumentsRef,
     *   pageIdentifier?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->invoiceId = $values['invoiceId'];
        $this->customerId = $values['customerId'];
        $this->paypointId = $values['paypointId'];
        $this->invoiceNumber = $values['invoiceNumber'];
        $this->invoiceDate = $values['invoiceDate'] ?? null;
        $this->invoiceDueDate = $values['invoiceDueDate'] ?? null;
        $this->invoiceSentDate = $values['invoiceSentDate'] ?? null;
        $this->invoiceEndDate = $values['invoiceEndDate'] ?? null;
        $this->lastPaymentDate = $values['lastPaymentDate'] ?? null;
        $this->createdAt = $values['createdAt'];
        $this->invoiceStatus = $values['invoiceStatus'];
        $this->invoiceType = $values['invoiceType'];
        $this->frequency = $values['frequency'];
        $this->paymentTerms = $values['paymentTerms'];
        $this->termsConditions = $values['termsConditions'] ?? null;
        $this->notes = $values['notes'] ?? null;
        $this->tax = $values['tax'] ?? null;
        $this->discount = $values['discount'] ?? null;
        $this->invoiceAmount = $values['invoiceAmount'];
        $this->invoicePaidAmount = $values['invoicePaidAmount'];
        $this->freightAmount = $values['freightAmount'] ?? null;
        $this->dutyAmount = $values['dutyAmount'] ?? null;
        $this->purchaseOrder = $values['purchaseOrder'] ?? null;
        $this->firstName = $values['firstName'];
        $this->lastName = $values['lastName'];
        $this->company = $values['company'] ?? null;
        $this->shippingAddress1 = $values['shippingAddress1'];
        $this->shippingAddress2 = $values['shippingAddress2'];
        $this->shippingCity = $values['shippingCity'];
        $this->shippingState = $values['shippingState'];
        $this->shippingZip = $values['shippingZip'];
        $this->shippingFromZip = $values['shippingFromZip'];
        $this->shippingCountry = $values['shippingCountry'];
        $this->shippingEmail = $values['shippingEmail'];
        $this->shippingPhone = $values['shippingPhone'];
        $this->summaryCommodityCode = $values['summaryCommodityCode'] ?? null;
        $this->items = $values['items'];
        $this->customer = $values['customer'];
        $this->paylinkId = $values['paylinkId'];
        $this->billEvents = $values['billEvents'] ?? null;
        $this->scheduledOptions = $values['scheduledOptions'] ?? null;
        $this->paypointLegalname = $values['paypointLegalname'];
        $this->paypointDbaname = $values['paypointDbaname'];
        $this->paypointEntryname = $values['paypointEntryname'];
        $this->parentOrgId = $values['parentOrgId'];
        $this->parentOrgName = $values['parentOrgName'];
        $this->additionalData = $values['additionalData'] ?? null;
        $this->documentsRef = $values['documentsRef'] ?? null;
        $this->externalPaypointId = $values['externalPaypointId'];
        $this->pageIdentifier = $values['pageIdentifier'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
