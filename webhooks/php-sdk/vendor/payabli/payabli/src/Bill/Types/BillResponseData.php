<?php

namespace Payabli\Bill\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use DateTime;
use Payabli\Core\Types\Date;
use Payabli\Types\BillItem;
use Payabli\Core\Types\ArrayType;
use Payabli\Types\VendorDataResponse;
use Payabli\Types\Frequency;
use Payabli\Types\TransactionOutQueryRecord;
use Payabli\Types\GeneralEvents;
use Payabli\Types\BillQueryRecord2BillApprovalsItem;
use Payabli\Core\Types\Union;
use Payabli\Types\DocumentsRef;

class BillResponseData extends JsonSerializableType
{
    /**
     * @var ?int $idBill
     */
    #[JsonProperty('IdBill')]
    public ?int $idBill;

    /**
     * @var ?string $billNumber Unique identifier for the bill.
     */
    #[JsonProperty('BillNumber')]
    public ?string $billNumber;

    /**
     * @var ?float $netAmount Net amount owed in bill.
     */
    #[JsonProperty('NetAmount')]
    public ?float $netAmount;

    /**
     * @var ?float $discount Bill discount amount.
     */
    #[JsonProperty('Discount')]
    public ?float $discount;

    /**
     * @var ?float $totalAmount Total amount for the bill.
     */
    #[JsonProperty('TotalAmount')]
    public ?float $totalAmount;

    /**
     * @var ?DateTime $billDate Date of bill. Accepted formats: YYYY-MM-DD, MM/DD/YYYY
     */
    #[JsonProperty('BillDate'), Date(Date::TYPE_DATE)]
    public ?DateTime $billDate;

    /**
     * @var ?DateTime $dueDate Due Date of bill. Accepted formats: YYYY-MM-DD, MM/DD/YYYY
     */
    #[JsonProperty('DueDate'), Date(Date::TYPE_DATE)]
    public ?DateTime $dueDate;

    /**
     * @var ?string $comments
     */
    #[JsonProperty('Comments')]
    public ?string $comments;

    /**
     * @var ?string $batchNumber The batch number that the bill belongs to.
     */
    #[JsonProperty('BatchNumber')]
    public ?string $batchNumber;

    /**
     * @var ?array<BillItem> $billItems Array of `LineItems` contained in bill.
     */
    #[JsonProperty('BillItems'), ArrayType([BillItem::class])]
    public ?array $billItems;

    /**
     * @var ?int $mode Bill mode: value `0` for single/one-time bills, `1` for scheduled bills.
     */
    #[JsonProperty('Mode')]
    public ?int $mode;

    /**
     * @var ?string $paymentMethod Payment method used for the bill.
     */
    #[JsonProperty('PaymentMethod')]
    public ?string $paymentMethod;

    /**
     * @var ?string $paymentId Payment ID associated with the bill.
     */
    #[JsonProperty('PaymentId')]
    public ?string $paymentId;

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
     * @var ?string $terms
     */
    #[JsonProperty('Terms')]
    public ?string $terms;

    /**
     * @var ?string $source The source of the bill, such as "API" or "UI".
     */
    #[JsonProperty('Source')]
    public ?string $source;

    /**
     * @var ?string $additionalData
     */
    #[JsonProperty('AdditionalData')]
    public ?string $additionalData;

    /**
     * @var ?VendorDataResponse $vendor
     */
    #[JsonProperty('Vendor')]
    public ?VendorDataResponse $vendor;

    /**
     * @var ?int $status
     */
    #[JsonProperty('Status')]
    public ?int $status;

    /**
     * @var ?DateTime $createdAt
     */
    #[JsonProperty('CreatedAt'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $createdAt;

    /**
     * @var ?DateTime $endDate End date for scheduled bills. Applied only in `Mode` = 1.
     */
    #[JsonProperty('EndDate'), Date(Date::TYPE_DATE)]
    public ?DateTime $endDate;

    /**
     * @var ?DateTime $lastUpdated
     */
    #[JsonProperty('LastUpdated'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $lastUpdated;

    /**
     * @var ?value-of<Frequency> $frequency Frequency for scheduled bills. Applied only in `Mode` = 1.
     */
    #[JsonProperty('Frequency')]
    public ?string $frequency;

    /**
     * @var ?TransactionOutQueryRecord $transaction MoneyOut transaction associated to the bill
     */
    #[JsonProperty('Transaction')]
    public ?TransactionOutQueryRecord $transaction;

    /**
     * @var ?array<GeneralEvents> $billEvents
     */
    #[JsonProperty('billEvents'), ArrayType([GeneralEvents::class])]
    public ?array $billEvents;

    /**
     * @var ?array<?BillQueryRecord2BillApprovalsItem> $billApprovals
     */
    #[JsonProperty('billApprovals'), ArrayType([new Union(BillQueryRecord2BillApprovalsItem::class, 'null')])]
    public ?array $billApprovals;

    /**
     * @var ?string $paypointLegalname
     */
    #[JsonProperty('PaypointLegalname')]
    public ?string $paypointLegalname;

    /**
     * @var ?string $paypointDbaname
     */
    #[JsonProperty('PaypointDbaname')]
    public ?string $paypointDbaname;

    /**
     * @var ?int $parentOrgId
     */
    #[JsonProperty('ParentOrgId')]
    public ?int $parentOrgId;

    /**
     * @var ?string $parentOrgName
     */
    #[JsonProperty('ParentOrgName')]
    public ?string $parentOrgName;

    /**
     * @var ?string $paypointEntryname
     */
    #[JsonProperty('PaypointEntryname')]
    public ?string $paypointEntryname;

    /**
     * @var ?string $paylinkId
     */
    #[JsonProperty('paylinkId')]
    public ?string $paylinkId;

    /**
     * @var ?DocumentsRef $documentsRef Object with the attached documents.
     */
    #[JsonProperty('DocumentsRef')]
    public ?DocumentsRef $documentsRef;

    /**
     * @var ?string $externalPaypointId
     */
    #[JsonProperty('externalPaypointID')]
    public ?string $externalPaypointId;

    /**
     * @var ?string $lotNumber Lot number of the bill.
     */
    #[JsonProperty('LotNumber')]
    public ?string $lotNumber;

    /**
     * @var ?int $entityId
     */
    #[JsonProperty('EntityID')]
    public ?int $entityId;

    /**
     * @param array{
     *   idBill?: ?int,
     *   billNumber?: ?string,
     *   netAmount?: ?float,
     *   discount?: ?float,
     *   totalAmount?: ?float,
     *   billDate?: ?DateTime,
     *   dueDate?: ?DateTime,
     *   comments?: ?string,
     *   batchNumber?: ?string,
     *   billItems?: ?array<BillItem>,
     *   mode?: ?int,
     *   paymentMethod?: ?string,
     *   paymentId?: ?string,
     *   accountingField1?: ?string,
     *   accountingField2?: ?string,
     *   terms?: ?string,
     *   source?: ?string,
     *   additionalData?: ?string,
     *   vendor?: ?VendorDataResponse,
     *   status?: ?int,
     *   createdAt?: ?DateTime,
     *   endDate?: ?DateTime,
     *   lastUpdated?: ?DateTime,
     *   frequency?: ?value-of<Frequency>,
     *   transaction?: ?TransactionOutQueryRecord,
     *   billEvents?: ?array<GeneralEvents>,
     *   billApprovals?: ?array<?BillQueryRecord2BillApprovalsItem>,
     *   paypointLegalname?: ?string,
     *   paypointDbaname?: ?string,
     *   parentOrgId?: ?int,
     *   parentOrgName?: ?string,
     *   paypointEntryname?: ?string,
     *   paylinkId?: ?string,
     *   documentsRef?: ?DocumentsRef,
     *   externalPaypointId?: ?string,
     *   lotNumber?: ?string,
     *   entityId?: ?int,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->idBill = $values['idBill'] ?? null;
        $this->billNumber = $values['billNumber'] ?? null;
        $this->netAmount = $values['netAmount'] ?? null;
        $this->discount = $values['discount'] ?? null;
        $this->totalAmount = $values['totalAmount'] ?? null;
        $this->billDate = $values['billDate'] ?? null;
        $this->dueDate = $values['dueDate'] ?? null;
        $this->comments = $values['comments'] ?? null;
        $this->batchNumber = $values['batchNumber'] ?? null;
        $this->billItems = $values['billItems'] ?? null;
        $this->mode = $values['mode'] ?? null;
        $this->paymentMethod = $values['paymentMethod'] ?? null;
        $this->paymentId = $values['paymentId'] ?? null;
        $this->accountingField1 = $values['accountingField1'] ?? null;
        $this->accountingField2 = $values['accountingField2'] ?? null;
        $this->terms = $values['terms'] ?? null;
        $this->source = $values['source'] ?? null;
        $this->additionalData = $values['additionalData'] ?? null;
        $this->vendor = $values['vendor'] ?? null;
        $this->status = $values['status'] ?? null;
        $this->createdAt = $values['createdAt'] ?? null;
        $this->endDate = $values['endDate'] ?? null;
        $this->lastUpdated = $values['lastUpdated'] ?? null;
        $this->frequency = $values['frequency'] ?? null;
        $this->transaction = $values['transaction'] ?? null;
        $this->billEvents = $values['billEvents'] ?? null;
        $this->billApprovals = $values['billApprovals'] ?? null;
        $this->paypointLegalname = $values['paypointLegalname'] ?? null;
        $this->paypointDbaname = $values['paypointDbaname'] ?? null;
        $this->parentOrgId = $values['parentOrgId'] ?? null;
        $this->parentOrgName = $values['parentOrgName'] ?? null;
        $this->paypointEntryname = $values['paypointEntryname'] ?? null;
        $this->paylinkId = $values['paylinkId'] ?? null;
        $this->documentsRef = $values['documentsRef'] ?? null;
        $this->externalPaypointId = $values['externalPaypointId'] ?? null;
        $this->lotNumber = $values['lotNumber'] ?? null;
        $this->entityId = $values['entityId'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
