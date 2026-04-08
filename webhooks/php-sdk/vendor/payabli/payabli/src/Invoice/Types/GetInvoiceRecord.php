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

class GetInvoiceRecord extends JsonSerializableType
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
     * @var ?DateTime $invoiceDate
     */
    #[JsonProperty('invoiceDate'), Date(Date::TYPE_DATE)]
    public ?DateTime $invoiceDate;

    /**
     * @var ?DateTime $invoiceDueDate
     */
    #[JsonProperty('invoiceDueDate'), Date(Date::TYPE_DATE)]
    public ?DateTime $invoiceDueDate;

    /**
     * @var ?DateTime $invoiceSentDate
     */
    #[JsonProperty('invoiceSentDate'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $invoiceSentDate;

    /**
     * @var ?DateTime $invoiceEndDate
     */
    #[JsonProperty('invoiceEndDate'), Date(Date::TYPE_DATE)]
    public ?DateTime $invoiceEndDate;

    /**
     * @var ?DateTime $lastPaymentDate
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
     * @var value-of<Frequency> $frequency
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
     * @var ?string $notes
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
     * @var string $purchaseOrder
     */
    #[JsonProperty('purchaseOrder')]
    public string $purchaseOrder;

    /**
     * @var ?string $firstName First name of the recipient of the invoice.
     */
    #[JsonProperty('firstName')]
    public ?string $firstName;

    /**
     * @var ?string $lastName Last name of the recipient of the invoice.
     */
    #[JsonProperty('lastName')]
    public ?string $lastName;

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
     * @var string $shippingEmail
     */
    #[JsonProperty('shippingEmail')]
    public string $shippingEmail;

    /**
     * @var string $shippingPhone
     */
    #[JsonProperty('shippingPhone')]
    public string $shippingPhone;

    /**
     * @var string $summaryCommodityCode
     */
    #[JsonProperty('summaryCommodityCode')]
    public string $summaryCommodityCode;

    /**
     * @var array<BillItem> $items
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
     * @var BillOptions $scheduledOptions
     */
    #[JsonProperty('scheduledOptions')]
    public BillOptions $scheduledOptions;

    /**
     * @var string $paypointLegalname
     */
    #[JsonProperty('PaypointLegalname')]
    public string $paypointLegalname;

    /**
     * @var string $paypointDbaname
     */
    #[JsonProperty('PaypointDbaname')]
    public string $paypointDbaname;

    /**
     * @var string $paypointEntryname
     */
    #[JsonProperty('PaypointEntryname')]
    public string $paypointEntryname;

    /**
     * @var string $parentOrgName
     */
    #[JsonProperty('ParentOrgName')]
    public string $parentOrgName;

    /**
     * @var ?string $additionalData
     */
    #[JsonProperty('AdditionalData')]
    public ?string $additionalData;

    /**
     * @var DocumentsRef $documentsRef
     */
    #[JsonProperty('DocumentsRef')]
    public DocumentsRef $documentsRef;

    /**
     * @var ?string $externalPaypointId
     */
    #[JsonProperty('externalPaypointID')]
    public ?string $externalPaypointId;

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
     *   purchaseOrder: string,
     *   shippingAddress1: string,
     *   shippingAddress2: string,
     *   shippingCity: string,
     *   shippingState: string,
     *   shippingZip: string,
     *   shippingFromZip: string,
     *   shippingCountry: string,
     *   shippingEmail: string,
     *   shippingPhone: string,
     *   summaryCommodityCode: string,
     *   items: array<BillItem>,
     *   customer: PayorDataResponse,
     *   paylinkId: string,
     *   scheduledOptions: BillOptions,
     *   paypointLegalname: string,
     *   paypointDbaname: string,
     *   paypointEntryname: string,
     *   parentOrgName: string,
     *   documentsRef: DocumentsRef,
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
     *   firstName?: ?string,
     *   lastName?: ?string,
     *   company?: ?string,
     *   billEvents?: ?array<GeneralEvents>,
     *   additionalData?: ?string,
     *   externalPaypointId?: ?string,
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
        $this->purchaseOrder = $values['purchaseOrder'];
        $this->firstName = $values['firstName'] ?? null;
        $this->lastName = $values['lastName'] ?? null;
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
        $this->summaryCommodityCode = $values['summaryCommodityCode'];
        $this->items = $values['items'];
        $this->customer = $values['customer'];
        $this->paylinkId = $values['paylinkId'];
        $this->billEvents = $values['billEvents'] ?? null;
        $this->scheduledOptions = $values['scheduledOptions'];
        $this->paypointLegalname = $values['paypointLegalname'];
        $this->paypointDbaname = $values['paypointDbaname'];
        $this->paypointEntryname = $values['paypointEntryname'];
        $this->parentOrgName = $values['parentOrgName'];
        $this->additionalData = $values['additionalData'] ?? null;
        $this->documentsRef = $values['documentsRef'];
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
