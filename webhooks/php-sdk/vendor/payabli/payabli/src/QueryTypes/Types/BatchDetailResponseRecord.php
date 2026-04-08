<?php

namespace Payabli\QueryTypes\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Types\QueryPaymentData;
use DateTime;
use Payabli\Core\Types\Date;
use Payabli\Types\QueryTransactionPayorData;
use Payabli\Types\QueryResponseData;
use Payabli\Types\AchHolderType;
use Payabli\Types\SplitFundingContent;
use Payabli\Core\Types\ArrayType;
use Payabli\Types\QueryCFeeTransaction;
use Payabli\Types\BillData;
use Payabli\Types\QueryTransactionEvents;

class BatchDetailResponseRecord extends JsonSerializableType
{
    /**
     * @var int $id
     */
    #[JsonProperty('Id')]
    public int $id;

    /**
     * @var string $method
     */
    #[JsonProperty('Method')]
    public string $method;

    /**
     * @var ?string $walletType
     */
    #[JsonProperty('WalletType')]
    public ?string $walletType;

    /**
     * @var float $settledAmount
     */
    #[JsonProperty('SettledAmount')]
    public float $settledAmount;

    /**
     * @var string $type
     */
    #[JsonProperty('Type')]
    public string $type;

    /**
     * @var string $batchNumber
     */
    #[JsonProperty('BatchNumber')]
    public string $batchNumber;

    /**
     * @var float $batchAmount
     */
    #[JsonProperty('BatchAmount')]
    public float $batchAmount;

    /**
     * @var string $paymentTransId
     */
    #[JsonProperty('PaymentTransId')]
    public string $paymentTransId;

    /**
     * @var int $paymentTransStatus
     */
    #[JsonProperty('PaymentTransStatus')]
    public int $paymentTransStatus;

    /**
     * @var int $scheduleReference
     */
    #[JsonProperty('ScheduleReference')]
    public int $scheduleReference;

    /**
     * @var string $gatewayTransId
     */
    #[JsonProperty('GatewayTransId')]
    public string $gatewayTransId;

    /**
     * @var string $orderId
     */
    #[JsonProperty('OrderId')]
    public string $orderId;

    /**
     * @var string $transMethod
     */
    #[JsonProperty('TransMethod')]
    public string $transMethod;

    /**
     * @var ?QueryPaymentData $paymentData
     */
    #[JsonProperty('PaymentData')]
    public ?QueryPaymentData $paymentData;

    /**
     * @var ?float $netAmount
     */
    #[JsonProperty('NetAmount')]
    public ?float $netAmount;

    /**
     * @var string $operation
     */
    #[JsonProperty('Operation')]
    public string $operation;

    /**
     * @var string $category
     */
    #[JsonProperty('Category')]
    public string $category;

    /**
     * @var ?string $source
     */
    #[JsonProperty('Source')]
    public ?string $source;

    /**
     * @var int $status
     */
    #[JsonProperty('Status')]
    public int $status;

    /**
     * @var DateTime $transactionTime
     */
    #[JsonProperty('TransactionTime'), Date(Date::TYPE_DATETIME)]
    public DateTime $transactionTime;

    /**
     * @var ?QueryTransactionPayorData $customer
     */
    #[JsonProperty('Customer')]
    public ?QueryTransactionPayorData $customer;

    /**
     * @var DateTime $settlementDate
     */
    #[JsonProperty('SettlementDate'), Date(Date::TYPE_DATETIME)]
    public DateTime $settlementDate;

    /**
     * @var int $paymentSettlementStatus
     */
    #[JsonProperty('PaymentSettlementStatus')]
    public int $paymentSettlementStatus;

    /**
     * @var int $batchStatus
     */
    #[JsonProperty('BatchStatus')]
    public int $batchStatus;

    /**
     * @var DateTime $depositDate
     */
    #[JsonProperty('DepositDate'), Date(Date::TYPE_DATETIME)]
    public DateTime $depositDate;

    /**
     * @var DateTime $expectedDepositDate
     */
    #[JsonProperty('ExpectedDepositDate'), Date(Date::TYPE_DATETIME)]
    public DateTime $expectedDepositDate;

    /**
     * @var string $maskedAccount
     */
    #[JsonProperty('MaskedAccount')]
    public string $maskedAccount;

    /**
     * @var DateTime $createdAt
     */
    #[JsonProperty('CreatedAt'), Date(Date::TYPE_DATETIME)]
    public DateTime $createdAt;

    /**
     * @var string $paypointLegalname
     */
    #[JsonProperty('PaypointLegalname')]
    public string $paypointLegalname;

    /**
     * @var ?QueryResponseData $responseData
     */
    #[JsonProperty('ResponseData')]
    public ?QueryResponseData $responseData;

    /**
     * @var string $paypointDbaname
     */
    #[JsonProperty('PaypointDbaname')]
    public string $paypointDbaname;

    /**
     * @var string $parentOrgName
     */
    #[JsonProperty('ParentOrgName')]
    public string $parentOrgName;

    /**
     * @var int $parentOrgId
     */
    #[JsonProperty('ParentOrgId')]
    public int $parentOrgId;

    /**
     * @var string $paypointEntryname
     */
    #[JsonProperty('PaypointEntryname')]
    public string $paypointEntryname;

    /**
     * @var ?string $deviceId
     */
    #[JsonProperty('DeviceId')]
    public ?string $deviceId;

    /**
     * @var int $retrievalId
     */
    #[JsonProperty('RetrievalId')]
    public int $retrievalId;

    /**
     * @var int $chargebackId
     */
    #[JsonProperty('ChargebackId')]
    public int $chargebackId;

    /**
     * @var value-of<AchHolderType> $achHolderType
     */
    #[JsonProperty('AchHolderType')]
    public string $achHolderType;

    /**
     * @var string $achSecCode
     */
    #[JsonProperty('AchSecCode')]
    public string $achSecCode;

    /**
     * @var string $connectorName
     */
    #[JsonProperty('ConnectorName')]
    public string $connectorName;

    /**
     * @var int $entrypageId
     */
    #[JsonProperty('EntrypageId')]
    public int $entrypageId;

    /**
     * @var float $feeAmount
     */
    #[JsonProperty('FeeAmount')]
    public float $feeAmount;

    /**
     * @var int $orgId
     */
    #[JsonProperty('OrgId')]
    public int $orgId;

    /**
     * @var int $payorId
     */
    #[JsonProperty('PayorId')]
    public int $payorId;

    /**
     * @var int $paypointId
     */
    #[JsonProperty('PaypointId')]
    public int $paypointId;

    /**
     * @var ?float $pendingFeeAmount
     */
    #[JsonProperty('PendingFeeAmount')]
    public ?float $pendingFeeAmount;

    /**
     * @var int $refundId
     */
    #[JsonProperty('RefundId')]
    public int $refundId;

    /**
     * @var int $returnedId
     */
    #[JsonProperty('ReturnedId')]
    public int $returnedId;

    /**
     * @var ?array<SplitFundingContent> $splitFundingInstructions
     */
    #[JsonProperty('splitFundingInstructions'), ArrayType([SplitFundingContent::class])]
    public ?array $splitFundingInstructions;

    /**
     * @var float $totalAmount
     */
    #[JsonProperty('TotalAmount')]
    public float $totalAmount;

    /**
     * @var array<QueryCFeeTransaction> $cfeeTransactions
     */
    #[JsonProperty('CfeeTransactions'), ArrayType([QueryCFeeTransaction::class])]
    public array $cfeeTransactions;

    /**
     * @var ?BillData $invoiceData
     */
    #[JsonProperty('invoiceData')]
    public ?BillData $invoiceData;

    /**
     * @var array<QueryTransactionEvents> $transactionEvents
     */
    #[JsonProperty('TransactionEvents'), ArrayType([QueryTransactionEvents::class])]
    public array $transactionEvents;

    /**
     * @var string $externalPaypointId
     */
    #[JsonProperty('externalPaypointID')]
    public string $externalPaypointId;

    /**
     * @var int $isHold
     */
    #[JsonProperty('isHold')]
    public int $isHold;

    /**
     * @param array{
     *   id: int,
     *   method: string,
     *   settledAmount: float,
     *   type: string,
     *   batchNumber: string,
     *   batchAmount: float,
     *   paymentTransId: string,
     *   paymentTransStatus: int,
     *   scheduleReference: int,
     *   gatewayTransId: string,
     *   orderId: string,
     *   transMethod: string,
     *   operation: string,
     *   category: string,
     *   status: int,
     *   transactionTime: DateTime,
     *   settlementDate: DateTime,
     *   paymentSettlementStatus: int,
     *   batchStatus: int,
     *   depositDate: DateTime,
     *   expectedDepositDate: DateTime,
     *   maskedAccount: string,
     *   createdAt: DateTime,
     *   paypointLegalname: string,
     *   paypointDbaname: string,
     *   parentOrgName: string,
     *   parentOrgId: int,
     *   paypointEntryname: string,
     *   retrievalId: int,
     *   chargebackId: int,
     *   achHolderType: value-of<AchHolderType>,
     *   achSecCode: string,
     *   connectorName: string,
     *   entrypageId: int,
     *   feeAmount: float,
     *   orgId: int,
     *   payorId: int,
     *   paypointId: int,
     *   refundId: int,
     *   returnedId: int,
     *   totalAmount: float,
     *   cfeeTransactions: array<QueryCFeeTransaction>,
     *   transactionEvents: array<QueryTransactionEvents>,
     *   externalPaypointId: string,
     *   isHold: int,
     *   walletType?: ?string,
     *   paymentData?: ?QueryPaymentData,
     *   netAmount?: ?float,
     *   source?: ?string,
     *   customer?: ?QueryTransactionPayorData,
     *   responseData?: ?QueryResponseData,
     *   deviceId?: ?string,
     *   pendingFeeAmount?: ?float,
     *   splitFundingInstructions?: ?array<SplitFundingContent>,
     *   invoiceData?: ?BillData,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->id = $values['id'];
        $this->method = $values['method'];
        $this->walletType = $values['walletType'] ?? null;
        $this->settledAmount = $values['settledAmount'];
        $this->type = $values['type'];
        $this->batchNumber = $values['batchNumber'];
        $this->batchAmount = $values['batchAmount'];
        $this->paymentTransId = $values['paymentTransId'];
        $this->paymentTransStatus = $values['paymentTransStatus'];
        $this->scheduleReference = $values['scheduleReference'];
        $this->gatewayTransId = $values['gatewayTransId'];
        $this->orderId = $values['orderId'];
        $this->transMethod = $values['transMethod'];
        $this->paymentData = $values['paymentData'] ?? null;
        $this->netAmount = $values['netAmount'] ?? null;
        $this->operation = $values['operation'];
        $this->category = $values['category'];
        $this->source = $values['source'] ?? null;
        $this->status = $values['status'];
        $this->transactionTime = $values['transactionTime'];
        $this->customer = $values['customer'] ?? null;
        $this->settlementDate = $values['settlementDate'];
        $this->paymentSettlementStatus = $values['paymentSettlementStatus'];
        $this->batchStatus = $values['batchStatus'];
        $this->depositDate = $values['depositDate'];
        $this->expectedDepositDate = $values['expectedDepositDate'];
        $this->maskedAccount = $values['maskedAccount'];
        $this->createdAt = $values['createdAt'];
        $this->paypointLegalname = $values['paypointLegalname'];
        $this->responseData = $values['responseData'] ?? null;
        $this->paypointDbaname = $values['paypointDbaname'];
        $this->parentOrgName = $values['parentOrgName'];
        $this->parentOrgId = $values['parentOrgId'];
        $this->paypointEntryname = $values['paypointEntryname'];
        $this->deviceId = $values['deviceId'] ?? null;
        $this->retrievalId = $values['retrievalId'];
        $this->chargebackId = $values['chargebackId'];
        $this->achHolderType = $values['achHolderType'];
        $this->achSecCode = $values['achSecCode'];
        $this->connectorName = $values['connectorName'];
        $this->entrypageId = $values['entrypageId'];
        $this->feeAmount = $values['feeAmount'];
        $this->orgId = $values['orgId'];
        $this->payorId = $values['payorId'];
        $this->paypointId = $values['paypointId'];
        $this->pendingFeeAmount = $values['pendingFeeAmount'] ?? null;
        $this->refundId = $values['refundId'];
        $this->returnedId = $values['returnedId'];
        $this->splitFundingInstructions = $values['splitFundingInstructions'] ?? null;
        $this->totalAmount = $values['totalAmount'];
        $this->cfeeTransactions = $values['cfeeTransactions'];
        $this->invoiceData = $values['invoiceData'] ?? null;
        $this->transactionEvents = $values['transactionEvents'];
        $this->externalPaypointId = $values['externalPaypointId'];
        $this->isHold = $values['isHold'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
