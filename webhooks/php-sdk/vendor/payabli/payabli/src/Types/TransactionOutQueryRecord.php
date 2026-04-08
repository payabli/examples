<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use DateTime;
use Payabli\Core\Types\Date;
use Payabli\Core\Types\ArrayType;

class TransactionOutQueryRecord extends JsonSerializableType
{
    /**
     * @var ?int $idOut Identifier of payout transaction.
     */
    #[JsonProperty('IdOut')]
    public ?int $idOut;

    /**
     * @var ?DateTime $createdAt
     */
    #[JsonProperty('CreatedAt'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $createdAt;

    /**
     * @var ?string $comments Any comment or description for payout transaction.
     */
    #[JsonProperty('Comments')]
    public ?string $comments;

    /**
     * @var ?VendorQueryRecord $vendor Vendor related to the payout transaction.
     */
    #[JsonProperty('Vendor')]
    public ?VendorQueryRecord $vendor;

    /**
     * @var ?string $paypointDbaname
     */
    #[JsonProperty('PaypointDbaname')]
    public ?string $paypointDbaname;

    /**
     * @var ?string $paypointLegalname
     */
    #[JsonProperty('PaypointLegalname')]
    public ?string $paypointLegalname;

    /**
     * @var ?int $status Internal status of transaction.
     */
    #[JsonProperty('Status')]
    public ?int $status;

    /**
     * @var ?DateTime $lastUpdated Timestamp when payment record was updated, in UTC.
     */
    #[JsonProperty('LastUpdated'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $lastUpdated;

    /**
     * @var ?float $totalAmount Transaction total amount (including service fee or sub-charge).
     */
    #[JsonProperty('TotalAmount')]
    public ?float $totalAmount;

    /**
     * @var ?float $netAmount
     */
    #[JsonProperty('NetAmount')]
    public ?float $netAmount;

    /**
     * @var ?float $feeAmount
     */
    #[JsonProperty('FeeAmount')]
    public ?float $feeAmount;

    /**
     * @var ?string $source
     */
    #[JsonProperty('Source')]
    public ?string $source;

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
     * @var ?string $batchNumber The batch number for the payout transaction.
     */
    #[JsonProperty('BatchNumber')]
    public ?string $batchNumber;

    /**
     * @var ?string $paymentStatus Status of payout transaction.
     */
    #[JsonProperty('PaymentStatus')]
    public ?string $paymentStatus;

    /**
     * @var ?string $paymentMethod Method of payment applied to the transaction.
     */
    #[JsonProperty('PaymentMethod')]
    public ?string $paymentMethod;

    /**
     * @var ?string $cardToken
     */
    #[JsonProperty('CardToken')]
    public ?string $cardToken;

    /**
     * @var ?string $checkNumber Paper check number related to payout transaction.
     */
    #[JsonProperty('CheckNumber')]
    public ?string $checkNumber;

    /**
     * @var ?FileContent $checkData Object referencing to paper check image.
     */
    #[JsonProperty('CheckData')]
    public ?FileContent $checkData;

    /**
     * @var ?string $paymentId
     */
    #[JsonProperty('PaymentId')]
    public ?string $paymentId;

    /**
     * @var ?QueryPaymentData $paymentData
     */
    #[JsonProperty('PaymentData')]
    public ?QueryPaymentData $paymentData;

    /**
     * @var ?array<BillPayOutData> $bills Events associated to this transaction.
     */
    #[JsonProperty('Bills'), ArrayType([BillPayOutData::class])]
    public ?array $bills;

    /**
     * @var ?array<QueryTransactionEvents> $events Events associated to this transaction.
     */
    #[JsonProperty('Events'), ArrayType([QueryTransactionEvents::class])]
    public ?array $events;

    /**
     * @var ?string $externalPaypointId
     */
    #[JsonProperty('externalPaypointID')]
    public ?string $externalPaypointId;

    /**
     * @var ?string $entryName
     */
    #[JsonProperty('EntryName')]
    public ?string $entryName;

    /**
     * @var ?string $gateway
     */
    #[JsonProperty('Gateway')]
    public ?string $gateway;

    /**
     * @var ?string $batchId ID of the batch the transaction belongs to.
     */
    #[JsonProperty('BatchId')]
    public ?string $batchId;

    /**
     * @var ?bool $hasVcardTransactions
     */
    #[JsonProperty('HasVcardTransactions')]
    public ?bool $hasVcardTransactions;

    /**
     * @var ?bool $isSameDayAch
     */
    #[JsonProperty('IsSameDayACH')]
    public ?bool $isSameDayAch;

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
     * @var ?string $riskStatus
     */
    #[JsonProperty('RiskStatus')]
    public ?string $riskStatus;

    /**
     * @var ?string $riskReason
     */
    #[JsonProperty('RiskReason')]
    public ?string $riskReason;

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
     * @var ?string $payoutProgram
     */
    #[JsonProperty('PayoutProgram')]
    public ?string $payoutProgram;

    /**
     * @param array{
     *   idOut?: ?int,
     *   createdAt?: ?DateTime,
     *   comments?: ?string,
     *   vendor?: ?VendorQueryRecord,
     *   paypointDbaname?: ?string,
     *   paypointLegalname?: ?string,
     *   status?: ?int,
     *   lastUpdated?: ?DateTime,
     *   totalAmount?: ?float,
     *   netAmount?: ?float,
     *   feeAmount?: ?float,
     *   source?: ?string,
     *   parentOrgName?: ?string,
     *   parentOrgId?: ?int,
     *   batchNumber?: ?string,
     *   paymentStatus?: ?string,
     *   paymentMethod?: ?string,
     *   cardToken?: ?string,
     *   checkNumber?: ?string,
     *   checkData?: ?FileContent,
     *   paymentId?: ?string,
     *   paymentData?: ?QueryPaymentData,
     *   bills?: ?array<BillPayOutData>,
     *   events?: ?array<QueryTransactionEvents>,
     *   externalPaypointId?: ?string,
     *   entryName?: ?string,
     *   gateway?: ?string,
     *   batchId?: ?string,
     *   hasVcardTransactions?: ?bool,
     *   isSameDayAch?: ?bool,
     *   scheduleId?: ?int,
     *   settlementStatus?: ?string,
     *   riskFlagged?: ?bool,
     *   riskFlaggedOn?: ?DateTime,
     *   riskStatus?: ?string,
     *   riskReason?: ?string,
     *   riskAction?: ?string,
     *   riskActionCode?: ?int,
     *   payoutProgram?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->idOut = $values['idOut'] ?? null;
        $this->createdAt = $values['createdAt'] ?? null;
        $this->comments = $values['comments'] ?? null;
        $this->vendor = $values['vendor'] ?? null;
        $this->paypointDbaname = $values['paypointDbaname'] ?? null;
        $this->paypointLegalname = $values['paypointLegalname'] ?? null;
        $this->status = $values['status'] ?? null;
        $this->lastUpdated = $values['lastUpdated'] ?? null;
        $this->totalAmount = $values['totalAmount'] ?? null;
        $this->netAmount = $values['netAmount'] ?? null;
        $this->feeAmount = $values['feeAmount'] ?? null;
        $this->source = $values['source'] ?? null;
        $this->parentOrgName = $values['parentOrgName'] ?? null;
        $this->parentOrgId = $values['parentOrgId'] ?? null;
        $this->batchNumber = $values['batchNumber'] ?? null;
        $this->paymentStatus = $values['paymentStatus'] ?? null;
        $this->paymentMethod = $values['paymentMethod'] ?? null;
        $this->cardToken = $values['cardToken'] ?? null;
        $this->checkNumber = $values['checkNumber'] ?? null;
        $this->checkData = $values['checkData'] ?? null;
        $this->paymentId = $values['paymentId'] ?? null;
        $this->paymentData = $values['paymentData'] ?? null;
        $this->bills = $values['bills'] ?? null;
        $this->events = $values['events'] ?? null;
        $this->externalPaypointId = $values['externalPaypointId'] ?? null;
        $this->entryName = $values['entryName'] ?? null;
        $this->gateway = $values['gateway'] ?? null;
        $this->batchId = $values['batchId'] ?? null;
        $this->hasVcardTransactions = $values['hasVcardTransactions'] ?? null;
        $this->isSameDayAch = $values['isSameDayAch'] ?? null;
        $this->scheduleId = $values['scheduleId'] ?? null;
        $this->settlementStatus = $values['settlementStatus'] ?? null;
        $this->riskFlagged = $values['riskFlagged'] ?? null;
        $this->riskFlaggedOn = $values['riskFlaggedOn'] ?? null;
        $this->riskStatus = $values['riskStatus'] ?? null;
        $this->riskReason = $values['riskReason'] ?? null;
        $this->riskAction = $values['riskAction'] ?? null;
        $this->riskActionCode = $values['riskActionCode'] ?? null;
        $this->payoutProgram = $values['payoutProgram'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
