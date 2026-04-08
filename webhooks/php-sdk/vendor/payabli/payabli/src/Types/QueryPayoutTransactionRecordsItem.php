<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;
use DateTime;
use Payabli\Core\Types\Date;

class QueryPayoutTransactionRecordsItem extends JsonSerializableType
{
    /**
     * @var ?string $batchNumber
     */
    #[JsonProperty('BatchNumber')]
    public ?string $batchNumber;

    /**
     * @var ?int $batchId Identifier of the batch associated with payout transaction.
     */
    #[JsonProperty('BatchId')]
    public ?int $batchId;

    /**
     * @var ?array<BillPayOutData> $bills Events associated with this transaction.
     */
    #[JsonProperty('Bills'), ArrayType([BillPayOutData::class])]
    public ?array $bills;

    /**
     * @var ?string $cardToken
     */
    #[JsonProperty('CardToken')]
    public ?string $cardToken;

    /**
     * @var ?FileContent $checkData Object referencing paper check image.
     */
    #[JsonProperty('CheckData')]
    public ?FileContent $checkData;

    /**
     * @var ?string $checkNumber Paper check number related to payout transaction.
     */
    #[JsonProperty('CheckNumber')]
    public ?string $checkNumber;

    /**
     * @var ?string $comments Any comment or description for payout transaction.
     */
    #[JsonProperty('Comments')]
    public ?string $comments;

    /**
     * @var ?DateTime $createdAt Timestamp when the payment was created, in UTC.
     */
    #[JsonProperty('CreatedAt'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $createdAt;

    /**
     * @var ?string $entryName
     */
    #[JsonProperty('EntryName')]
    public ?string $entryName;

    /**
     * @var ?array<QueryTransactionEvents> $events Events associated with this transaction.
     */
    #[JsonProperty('Events'), ArrayType([QueryTransactionEvents::class])]
    public ?array $events;

    /**
     * @var ?string $externalPaypointId
     */
    #[JsonProperty('externalPaypointID')]
    public ?string $externalPaypointId;

    /**
     * @var ?float $feeAmount
     */
    #[JsonProperty('FeeAmount')]
    public ?float $feeAmount;

    /**
     * @var ?string $gateway
     */
    #[JsonProperty('Gateway')]
    public ?string $gateway;

    /**
     * @var ?bool $hasVcardTransactions
     */
    #[JsonProperty('HasVcardTransactions')]
    public ?bool $hasVcardTransactions;

    /**
     * @var ?int $idOut Identifier of payout transaction.
     */
    #[JsonProperty('IdOut')]
    public ?int $idOut;

    /**
     * @var ?bool $isSameDayAch
     */
    #[JsonProperty('IsSameDayACH')]
    public ?bool $isSameDayAch;

    /**
     * @var ?DateTime $lastUpdated Timestamp when payment record was updated.
     */
    #[JsonProperty('LastUpdated'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $lastUpdated;

    /**
     * @var ?float $netAmount Net amount paid.
     */
    #[JsonProperty('NetAmount')]
    public ?float $netAmount;

    /**
     * @var ?string $parentOrgName
     */
    #[JsonProperty('ParentOrgName')]
    public ?string $parentOrgName;

    /**
     * @var ?int $parentOrgId
     */
    #[JsonProperty('ParentOrgId')]
    public ?int $parentOrgId;

    /**
     * @var ?QueryPayoutTransactionRecordsItemPaymentData $paymentData
     */
    #[JsonProperty('PaymentData')]
    public ?QueryPayoutTransactionRecordsItemPaymentData $paymentData;

    /**
     * @var ?string $paymentId
     */
    #[JsonProperty('PaymentId')]
    public ?string $paymentId;

    /**
     * @var ?string $paymentMethod The payment method for the transaction.
     */
    #[JsonProperty('PaymentMethod')]
    public ?string $paymentMethod;

    /**
     * @var ?string $paymentStatus Status of payout transaction. See [Payout Transaction Statuses](guides/money-out-statuses#payout-transaction-statuses) for a full reference.
     */
    #[JsonProperty('PaymentStatus')]
    public ?string $paymentStatus;

    /**
     * @var ?string $payoutProgram
     */
    #[JsonProperty('PayoutProgram')]
    public ?string $payoutProgram;

    /**
     * @var ?string $paypointDbaname
     */
    #[JsonProperty('PaypointDbaname')]
    public ?string $paypointDbaname;

    /**
     * @var ?string $paypointLegalname Paypoint legal name.
     */
    #[JsonProperty('PaypointLegalname')]
    public ?string $paypointLegalname;

    /**
     * @var ?string $riskAction
     */
    #[JsonProperty('RiskAction')]
    public ?string $riskAction;

    /**
     * @var ?int $riskActionCode
     */
    #[JsonProperty('RiskActionCode')]
    public ?int $riskActionCode;

    /**
     * @var ?bool $riskFlagged
     */
    #[JsonProperty('RiskFlagged')]
    public ?bool $riskFlagged;

    /**
     * @var ?DateTime $riskFlaggedOn
     */
    #[JsonProperty('RiskFlaggedOn'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $riskFlaggedOn;

    /**
     * @var ?string $riskReason
     */
    #[JsonProperty('RiskReason')]
    public ?string $riskReason;

    /**
     * @var ?string $riskStatus
     */
    #[JsonProperty('RiskStatus')]
    public ?string $riskStatus;

    /**
     * @var ?int $scheduleId
     */
    #[JsonProperty('ScheduleId')]
    public ?int $scheduleId;

    /**
     * @var ?string $settlementStatus
     */
    #[JsonProperty('SettlementStatus')]
    public ?string $settlementStatus;

    /**
     * @var ?string $source
     */
    #[JsonProperty('Source')]
    public ?string $source;

    /**
     * @var ?int $status Internal status of transaction.
     */
    #[JsonProperty('Status')]
    public ?int $status;

    /**
     * @var ?float $totalAmount Transaction total amount (including service fee or sub-charge).
     */
    #[JsonProperty('TotalAmount')]
    public ?float $totalAmount;

    /**
     * @var ?VendorQueryRecord $vendor Vendor related to the payout transaction.
     */
    #[JsonProperty('Vendor')]
    public ?VendorQueryRecord $vendor;

    /**
     * @param array{
     *   batchNumber?: ?string,
     *   batchId?: ?int,
     *   bills?: ?array<BillPayOutData>,
     *   cardToken?: ?string,
     *   checkData?: ?FileContent,
     *   checkNumber?: ?string,
     *   comments?: ?string,
     *   createdAt?: ?DateTime,
     *   entryName?: ?string,
     *   events?: ?array<QueryTransactionEvents>,
     *   externalPaypointId?: ?string,
     *   feeAmount?: ?float,
     *   gateway?: ?string,
     *   hasVcardTransactions?: ?bool,
     *   idOut?: ?int,
     *   isSameDayAch?: ?bool,
     *   lastUpdated?: ?DateTime,
     *   netAmount?: ?float,
     *   parentOrgName?: ?string,
     *   parentOrgId?: ?int,
     *   paymentData?: ?QueryPayoutTransactionRecordsItemPaymentData,
     *   paymentId?: ?string,
     *   paymentMethod?: ?string,
     *   paymentStatus?: ?string,
     *   payoutProgram?: ?string,
     *   paypointDbaname?: ?string,
     *   paypointLegalname?: ?string,
     *   riskAction?: ?string,
     *   riskActionCode?: ?int,
     *   riskFlagged?: ?bool,
     *   riskFlaggedOn?: ?DateTime,
     *   riskReason?: ?string,
     *   riskStatus?: ?string,
     *   scheduleId?: ?int,
     *   settlementStatus?: ?string,
     *   source?: ?string,
     *   status?: ?int,
     *   totalAmount?: ?float,
     *   vendor?: ?VendorQueryRecord,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->batchNumber = $values['batchNumber'] ?? null;
        $this->batchId = $values['batchId'] ?? null;
        $this->bills = $values['bills'] ?? null;
        $this->cardToken = $values['cardToken'] ?? null;
        $this->checkData = $values['checkData'] ?? null;
        $this->checkNumber = $values['checkNumber'] ?? null;
        $this->comments = $values['comments'] ?? null;
        $this->createdAt = $values['createdAt'] ?? null;
        $this->entryName = $values['entryName'] ?? null;
        $this->events = $values['events'] ?? null;
        $this->externalPaypointId = $values['externalPaypointId'] ?? null;
        $this->feeAmount = $values['feeAmount'] ?? null;
        $this->gateway = $values['gateway'] ?? null;
        $this->hasVcardTransactions = $values['hasVcardTransactions'] ?? null;
        $this->idOut = $values['idOut'] ?? null;
        $this->isSameDayAch = $values['isSameDayAch'] ?? null;
        $this->lastUpdated = $values['lastUpdated'] ?? null;
        $this->netAmount = $values['netAmount'] ?? null;
        $this->parentOrgName = $values['parentOrgName'] ?? null;
        $this->parentOrgId = $values['parentOrgId'] ?? null;
        $this->paymentData = $values['paymentData'] ?? null;
        $this->paymentId = $values['paymentId'] ?? null;
        $this->paymentMethod = $values['paymentMethod'] ?? null;
        $this->paymentStatus = $values['paymentStatus'] ?? null;
        $this->payoutProgram = $values['payoutProgram'] ?? null;
        $this->paypointDbaname = $values['paypointDbaname'] ?? null;
        $this->paypointLegalname = $values['paypointLegalname'] ?? null;
        $this->riskAction = $values['riskAction'] ?? null;
        $this->riskActionCode = $values['riskActionCode'] ?? null;
        $this->riskFlagged = $values['riskFlagged'] ?? null;
        $this->riskFlaggedOn = $values['riskFlaggedOn'] ?? null;
        $this->riskReason = $values['riskReason'] ?? null;
        $this->riskStatus = $values['riskStatus'] ?? null;
        $this->scheduleId = $values['scheduleId'] ?? null;
        $this->settlementStatus = $values['settlementStatus'] ?? null;
        $this->source = $values['source'] ?? null;
        $this->status = $values['status'] ?? null;
        $this->totalAmount = $values['totalAmount'] ?? null;
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
