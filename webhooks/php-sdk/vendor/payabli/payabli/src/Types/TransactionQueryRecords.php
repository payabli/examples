<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;
use DateTime;
use Payabli\Core\Types\Date;

class TransactionQueryRecords extends JsonSerializableType
{
    /**
     * @var ?value-of<AchHolderType> $achHolderType
     */
    #[JsonProperty('AchHolderType')]
    public ?string $achHolderType;

    /**
     * @var ?string $achSecCode
     */
    #[JsonProperty('AchSecCode')]
    public ?string $achSecCode;

    /**
     * @var ?float $batchAmount Batch amount.
     */
    #[JsonProperty('BatchAmount')]
    public ?float $batchAmount;

    /**
     * @var ?string $batchNumber
     */
    #[JsonProperty('BatchNumber')]
    public ?string $batchNumber;

    /**
     * @var ?array<QueryCFeeTransaction> $cfeeTransactions Service Fee or sub-charge transaction associated to the main transaction.
     */
    #[JsonProperty('CfeeTransactions'), ArrayType([QueryCFeeTransaction::class])]
    public ?array $cfeeTransactions;

    /**
     * @var ?string $connectorName Connector used for transaction.
     */
    #[JsonProperty('ConnectorName')]
    public ?string $connectorName;

    /**
     * @var ?QueryTransactionPayorData $customer
     */
    #[JsonProperty('Customer')]
    public ?QueryTransactionPayorData $customer;

    /**
     * @var ?string $deviceId
     */
    #[JsonProperty('DeviceId')]
    public ?string $deviceId;

    /**
     * @var ?int $entrypageId
     */
    #[JsonProperty('EntrypageId')]
    public ?int $entrypageId;

    /**
     * @var ?string $externalProcessorInformation
     */
    #[JsonProperty('ExternalProcessorInformation')]
    public ?string $externalProcessorInformation;

    /**
     * @var ?float $feeAmount
     */
    #[JsonProperty('FeeAmount')]
    public ?float $feeAmount;

    /**
     * @var ?string $gatewayTransId Internal identifier used for processing.
     */
    #[JsonProperty('GatewayTransId')]
    public ?string $gatewayTransId;

    /**
     * @var ?BillData $invoiceData
     */
    #[JsonProperty('InvoiceData')]
    public ?BillData $invoiceData;

    /**
     * @var ?string $method Payment method used: card, ach, or wallet.
     */
    #[JsonProperty('Method')]
    public ?string $method;

    /**
     * @var ?float $netAmount Net amount paid.
     */
    #[JsonProperty('NetAmount')]
    public ?float $netAmount;

    /**
     * @var ?string $operation
     */
    #[JsonProperty('Operation')]
    public ?string $operation;

    /**
     * @var ?string $orderId
     */
    #[JsonProperty('OrderId')]
    public ?string $orderId;

    /**
     * @var ?int $orgId ID of immediate parent organization.
     */
    #[JsonProperty('OrgId')]
    public ?int $orgId;

    /**
     * @var ?string $parentOrgName
     */
    #[JsonProperty('ParentOrgName')]
    public ?string $parentOrgName;

    /**
     * @var ?QueryPaymentData $paymentData
     */
    #[JsonProperty('PaymentData')]
    public ?QueryPaymentData $paymentData;

    /**
     * @var ?string $paymentTransId Unique Transaction ID.
     */
    #[JsonProperty('PaymentTransId')]
    public ?string $paymentTransId;

    /**
     * @var ?int $payorId
     */
    #[JsonProperty('PayorId')]
    public ?int $payorId;

    /**
     * @var ?string $paypointDbaname Paypoint's DBA name.
     */
    #[JsonProperty('PaypointDbaname')]
    public ?string $paypointDbaname;

    /**
     * @var ?string $paypointEntryname Paypoint's entryname.
     */
    #[JsonProperty('PaypointEntryname')]
    public ?string $paypointEntryname;

    /**
     * @var ?int $paypointId InternalId for paypoint.
     */
    #[JsonProperty('PaypointId')]
    public ?int $paypointId;

    /**
     * @var ?string $paypointLegalname Paypoint's legal name.
     */
    #[JsonProperty('PaypointLegalname')]
    public ?string $paypointLegalname;

    /**
     * @var ?float $pendingFeeAmount
     */
    #[JsonProperty('PendingFeeAmount')]
    public ?float $pendingFeeAmount;

    /**
     * @var ?int $refundId
     */
    #[JsonProperty('RefundId')]
    public ?int $refundId;

    /**
     * @var ?QueryResponseData $responseData
     */
    #[JsonProperty('ResponseData')]
    public ?QueryResponseData $responseData;

    /**
     * @var ?int $returnedId
     */
    #[JsonProperty('ReturnedId')]
    public ?int $returnedId;

    /**
     * @var ?int $scheduleReference Reference to the subscription that originated the transaction.
     */
    #[JsonProperty('ScheduleReference')]
    public ?int $scheduleReference;

    /**
     * @var ?int $settlementStatus Settlement status for transaction. See [the docs](/developers/references/money-in-statuses#payment-funding-status) for a full reference.
     */
    #[JsonProperty('SettlementStatus')]
    public ?int $settlementStatus;

    /**
     * @var ?string $source
     */
    #[JsonProperty('Source')]
    public ?string $source;

    /**
     * @var ?array<SplitFundingContent> $splitFundingInstructions
     */
    #[JsonProperty('splitFundingInstructions'), ArrayType([SplitFundingContent::class])]
    public ?array $splitFundingInstructions;

    /**
     * @var ?float $totalAmount Transaction total amount (including service fee or sub-charge)
     */
    #[JsonProperty('TotalAmount')]
    public ?float $totalAmount;

    /**
     * @var ?array<QueryTransactionEvents> $transactionEvents Events associated with this transaction.
     */
    #[JsonProperty('TransactionEvents'), ArrayType([QueryTransactionEvents::class])]
    public ?array $transactionEvents;

    /**
     * @var ?DateTime $transactionTime Transaction date and time, in UTC.
     */
    #[JsonProperty('TransactionTime'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $transactionTime;

    /**
     * @var mixed $transAdditionalData
     */
    #[JsonProperty('TransAdditionalData')]
    public mixed $transAdditionalData;

    /**
     * @var ?int $transStatus Status of transaction. See [the docs](/developers/references/money-in-statuses#money-in-transaction-status) for a full reference.
     */
    #[JsonProperty('TransStatus')]
    public ?int $transStatus;

    /**
     * @param array{
     *   achHolderType?: ?value-of<AchHolderType>,
     *   achSecCode?: ?string,
     *   batchAmount?: ?float,
     *   batchNumber?: ?string,
     *   cfeeTransactions?: ?array<QueryCFeeTransaction>,
     *   connectorName?: ?string,
     *   customer?: ?QueryTransactionPayorData,
     *   deviceId?: ?string,
     *   entrypageId?: ?int,
     *   externalProcessorInformation?: ?string,
     *   feeAmount?: ?float,
     *   gatewayTransId?: ?string,
     *   invoiceData?: ?BillData,
     *   method?: ?string,
     *   netAmount?: ?float,
     *   operation?: ?string,
     *   orderId?: ?string,
     *   orgId?: ?int,
     *   parentOrgName?: ?string,
     *   paymentData?: ?QueryPaymentData,
     *   paymentTransId?: ?string,
     *   payorId?: ?int,
     *   paypointDbaname?: ?string,
     *   paypointEntryname?: ?string,
     *   paypointId?: ?int,
     *   paypointLegalname?: ?string,
     *   pendingFeeAmount?: ?float,
     *   refundId?: ?int,
     *   responseData?: ?QueryResponseData,
     *   returnedId?: ?int,
     *   scheduleReference?: ?int,
     *   settlementStatus?: ?int,
     *   source?: ?string,
     *   splitFundingInstructions?: ?array<SplitFundingContent>,
     *   totalAmount?: ?float,
     *   transactionEvents?: ?array<QueryTransactionEvents>,
     *   transactionTime?: ?DateTime,
     *   transAdditionalData?: mixed,
     *   transStatus?: ?int,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->achHolderType = $values['achHolderType'] ?? null;
        $this->achSecCode = $values['achSecCode'] ?? null;
        $this->batchAmount = $values['batchAmount'] ?? null;
        $this->batchNumber = $values['batchNumber'] ?? null;
        $this->cfeeTransactions = $values['cfeeTransactions'] ?? null;
        $this->connectorName = $values['connectorName'] ?? null;
        $this->customer = $values['customer'] ?? null;
        $this->deviceId = $values['deviceId'] ?? null;
        $this->entrypageId = $values['entrypageId'] ?? null;
        $this->externalProcessorInformation = $values['externalProcessorInformation'] ?? null;
        $this->feeAmount = $values['feeAmount'] ?? null;
        $this->gatewayTransId = $values['gatewayTransId'] ?? null;
        $this->invoiceData = $values['invoiceData'] ?? null;
        $this->method = $values['method'] ?? null;
        $this->netAmount = $values['netAmount'] ?? null;
        $this->operation = $values['operation'] ?? null;
        $this->orderId = $values['orderId'] ?? null;
        $this->orgId = $values['orgId'] ?? null;
        $this->parentOrgName = $values['parentOrgName'] ?? null;
        $this->paymentData = $values['paymentData'] ?? null;
        $this->paymentTransId = $values['paymentTransId'] ?? null;
        $this->payorId = $values['payorId'] ?? null;
        $this->paypointDbaname = $values['paypointDbaname'] ?? null;
        $this->paypointEntryname = $values['paypointEntryname'] ?? null;
        $this->paypointId = $values['paypointId'] ?? null;
        $this->paypointLegalname = $values['paypointLegalname'] ?? null;
        $this->pendingFeeAmount = $values['pendingFeeAmount'] ?? null;
        $this->refundId = $values['refundId'] ?? null;
        $this->responseData = $values['responseData'] ?? null;
        $this->returnedId = $values['returnedId'] ?? null;
        $this->scheduleReference = $values['scheduleReference'] ?? null;
        $this->settlementStatus = $values['settlementStatus'] ?? null;
        $this->source = $values['source'] ?? null;
        $this->splitFundingInstructions = $values['splitFundingInstructions'] ?? null;
        $this->totalAmount = $values['totalAmount'] ?? null;
        $this->transactionEvents = $values['transactionEvents'] ?? null;
        $this->transactionTime = $values['transactionTime'] ?? null;
        $this->transAdditionalData = $values['transAdditionalData'] ?? null;
        $this->transStatus = $values['transStatus'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
