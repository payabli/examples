<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;
use DateTime;
use Payabli\Core\Types\Date;

class BillQueryRecord2 extends JsonSerializableType
{
    /**
     * @var ?string $accountingField1
     */
    #[JsonProperty('AccountingField1')]
    public ?string $accountingField1;

    /**
     * @var ?string $accountingField2
     */
    #[JsonProperty('AccountingField2')]
    public ?string $accountingField2;

    /**
     * @var ?array<string, string> $additionalData Additional data associated with the bill.
     */
    #[JsonProperty('AdditionalData'), ArrayType(['string' => 'string'])]
    public ?array $additionalData;

    /**
     * @var ?string $batchNumber Batch number associated with the bill.
     */
    #[JsonProperty('BatchNumber')]
    public ?string $batchNumber;

    /**
     * @var ?array<BillQueryRecord2BillApprovalsItem> $billApprovals
     */
    #[JsonProperty('billApprovals'), ArrayType([BillQueryRecord2BillApprovalsItem::class])]
    public ?array $billApprovals;

    /**
     * @var ?DateTime $billDate Bill creation date in one of the accepted formats: YYYY-MM-DD, MM/DD/YYYY.
     */
    #[JsonProperty('BillDate'), Date(Date::TYPE_DATE)]
    public ?DateTime $billDate;

    /**
     * @var ?array<GeneralEvents> $billEvents Events associated with the bill.
     */
    #[JsonProperty('billEvents'), ArrayType([GeneralEvents::class])]
    public ?array $billEvents;

    /**
     * @var ?array<BillItem> $billItems Array of items included in the bill.
     */
    #[JsonProperty('BillItems'), ArrayType([BillItem::class])]
    public ?array $billItems;

    /**
     * @var ?string $billNumber Bill number.
     */
    #[JsonProperty('BillNumber')]
    public ?string $billNumber;

    /**
     * @var ?string $comments Additional comments on the bill.
     */
    #[JsonProperty('Comments')]
    public ?string $comments;

    /**
     * @var ?DateTime $createdAt Timestamp of when bill was created, in UTC.
     */
    #[JsonProperty('CreatedAt'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $createdAt;

    /**
     * @var ?float $discount Discount amount applied to the bill.
     */
    #[JsonProperty('Discount')]
    public ?float $discount;

    /**
     * @var ?string $documentsRef Reference to documents associated with the bill.
     */
    #[JsonProperty('DocumentsRef')]
    public ?string $documentsRef;

    /**
     * @var ?DateTime $dueDate Bill due date in one of the accepted formats: YYYY-MM-DD, MM/DD/YYYY.
     */
    #[JsonProperty('DueDate'), Date(Date::TYPE_DATE)]
    public ?DateTime $dueDate;

    /**
     * @var ?DateTime $endDate End date for the bill.
     */
    #[JsonProperty('EndDate'), Date(Date::TYPE_DATE)]
    public ?DateTime $endDate;

    /**
     * @var ?string $entityId Entity identifier associated with the bill.
     */
    #[JsonProperty('EntityID')]
    public ?string $entityId;

    /**
     * @var ?string $externalPaypointId
     */
    #[JsonProperty('externalPaypointID')]
    public ?string $externalPaypointId;

    /**
     * @var ?value-of<Frequency> $frequency Frequency for scheduled bills. Applied only in `Mode` = 1.
     */
    #[JsonProperty('Frequency')]
    public ?string $frequency;

    /**
     * @var ?int $idBill Identifier of the bill.
     */
    #[JsonProperty('IdBill')]
    public ?int $idBill;

    /**
     * @var ?DateTime $lastUpdated Timestamp of when bill was last updated, in UTC.
     */
    #[JsonProperty('LastUpdated'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $lastUpdated;

    /**
     * @var ?string $lotNumber Lot number associated with the bill.
     */
    #[JsonProperty('LotNumber')]
    public ?string $lotNumber;

    /**
     * @var ?int $mode Bill mode: value `0` for single/one-time bills, `1` for scheduled bills.
     */
    #[JsonProperty('Mode')]
    public ?int $mode;

    /**
     * @var ?float $netAmount Net amount of the bill.
     */
    #[JsonProperty('NetAmount')]
    public ?float $netAmount;

    /**
     * @var ?int $parentOrgId Parent organization identifier.
     */
    #[JsonProperty('ParentOrgId')]
    public ?int $parentOrgId;

    /**
     * @var ?string $parentOrgName
     */
    #[JsonProperty('ParentOrgName')]
    public ?string $parentOrgName;

    /**
     * @var ?string $paymentId
     */
    #[JsonProperty('PaymentId')]
    public ?string $paymentId;

    /**
     * @var ?value-of<BillQueryRecord2PaymentMethod> $paymentMethod Preferred payment method used.
     */
    #[JsonProperty('PaymentMethod')]
    public ?string $paymentMethod;

    /**
     * @var ?string $paylinkId Paylink identifier associated with the bill.
     */
    #[JsonProperty('paylinkId')]
    public ?string $paylinkId;

    /**
     * @var ?string $paypointDbaname The paypoint's DBA name.
     */
    #[JsonProperty('PaypointDbaname')]
    public ?string $paypointDbaname;

    /**
     * @var ?string $paypointEntryname Entry name of the paypoint.
     */
    #[JsonProperty('PaypointEntryname')]
    public ?string $paypointEntryname;

    /**
     * @var ?string $paypointLegalname The paypoint's legal name.
     */
    #[JsonProperty('PaypointLegalname')]
    public ?string $paypointLegalname;

    /**
     * @var ?string $source Source of the bill.
     */
    #[JsonProperty('Source')]
    public ?string $source;

    /**
     * @var ?int $status
     */
    #[JsonProperty('Status')]
    public ?int $status;

    /**
     * @var ?string $terms The payment terms for invoice. If no terms were defined initially, then response data for this field will default to `N30`.
     */
    #[JsonProperty('Terms')]
    public ?string $terms;

    /**
     * @var ?float $totalAmount Total amount of the bill including taxes and fees.
     */
    #[JsonProperty('TotalAmount')]
    public ?float $totalAmount;

    /**
     * @var ?TransactionOutQueryRecord $transaction MoneyOut transaction associated to the bill.
     */
    #[JsonProperty('Transaction')]
    public ?TransactionOutQueryRecord $transaction;

    /**
     * @var ?VendorOutData $vendor
     */
    #[JsonProperty('Vendor')]
    public ?VendorOutData $vendor;

    /**
     * @param array{
     *   accountingField1?: ?string,
     *   accountingField2?: ?string,
     *   additionalData?: ?array<string, string>,
     *   batchNumber?: ?string,
     *   billApprovals?: ?array<BillQueryRecord2BillApprovalsItem>,
     *   billDate?: ?DateTime,
     *   billEvents?: ?array<GeneralEvents>,
     *   billItems?: ?array<BillItem>,
     *   billNumber?: ?string,
     *   comments?: ?string,
     *   createdAt?: ?DateTime,
     *   discount?: ?float,
     *   documentsRef?: ?string,
     *   dueDate?: ?DateTime,
     *   endDate?: ?DateTime,
     *   entityId?: ?string,
     *   externalPaypointId?: ?string,
     *   frequency?: ?value-of<Frequency>,
     *   idBill?: ?int,
     *   lastUpdated?: ?DateTime,
     *   lotNumber?: ?string,
     *   mode?: ?int,
     *   netAmount?: ?float,
     *   parentOrgId?: ?int,
     *   parentOrgName?: ?string,
     *   paymentId?: ?string,
     *   paymentMethod?: ?value-of<BillQueryRecord2PaymentMethod>,
     *   paylinkId?: ?string,
     *   paypointDbaname?: ?string,
     *   paypointEntryname?: ?string,
     *   paypointLegalname?: ?string,
     *   source?: ?string,
     *   status?: ?int,
     *   terms?: ?string,
     *   totalAmount?: ?float,
     *   transaction?: ?TransactionOutQueryRecord,
     *   vendor?: ?VendorOutData,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->accountingField1 = $values['accountingField1'] ?? null;
        $this->accountingField2 = $values['accountingField2'] ?? null;
        $this->additionalData = $values['additionalData'] ?? null;
        $this->batchNumber = $values['batchNumber'] ?? null;
        $this->billApprovals = $values['billApprovals'] ?? null;
        $this->billDate = $values['billDate'] ?? null;
        $this->billEvents = $values['billEvents'] ?? null;
        $this->billItems = $values['billItems'] ?? null;
        $this->billNumber = $values['billNumber'] ?? null;
        $this->comments = $values['comments'] ?? null;
        $this->createdAt = $values['createdAt'] ?? null;
        $this->discount = $values['discount'] ?? null;
        $this->documentsRef = $values['documentsRef'] ?? null;
        $this->dueDate = $values['dueDate'] ?? null;
        $this->endDate = $values['endDate'] ?? null;
        $this->entityId = $values['entityId'] ?? null;
        $this->externalPaypointId = $values['externalPaypointId'] ?? null;
        $this->frequency = $values['frequency'] ?? null;
        $this->idBill = $values['idBill'] ?? null;
        $this->lastUpdated = $values['lastUpdated'] ?? null;
        $this->lotNumber = $values['lotNumber'] ?? null;
        $this->mode = $values['mode'] ?? null;
        $this->netAmount = $values['netAmount'] ?? null;
        $this->parentOrgId = $values['parentOrgId'] ?? null;
        $this->parentOrgName = $values['parentOrgName'] ?? null;
        $this->paymentId = $values['paymentId'] ?? null;
        $this->paymentMethod = $values['paymentMethod'] ?? null;
        $this->paylinkId = $values['paylinkId'] ?? null;
        $this->paypointDbaname = $values['paypointDbaname'] ?? null;
        $this->paypointEntryname = $values['paypointEntryname'] ?? null;
        $this->paypointLegalname = $values['paypointLegalname'] ?? null;
        $this->source = $values['source'] ?? null;
        $this->status = $values['status'] ?? null;
        $this->terms = $values['terms'] ?? null;
        $this->totalAmount = $values['totalAmount'] ?? null;
        $this->transaction = $values['transaction'] ?? null;
        $this->vendor = $values['vendor'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
