<?php

namespace Payabli\QueryTypes\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Types\GeneralEvents;
use Payabli\Core\Types\ArrayType;
use DateTime;
use Payabli\Core\Types\Date;

class QueryBatchesResponseRecordsItem extends JsonSerializableType
{
    /**
     * @var ?int $idBatch The batch ID.
     */
    #[JsonProperty('IdBatch')]
    public ?int $idBatch;

    /**
     * @var ?string $batchNumber
     */
    #[JsonProperty('BatchNumber')]
    public ?string $batchNumber;

    /**
     * @var ?string $transferIdentifier
     */
    #[JsonProperty('TransferIdentifier')]
    public ?string $transferIdentifier;

    /**
     * @var ?array<GeneralEvents> $eventsData Events associated with the batch.
     */
    #[JsonProperty('EventsData'), ArrayType([GeneralEvents::class])]
    public ?array $eventsData;

    /**
     * @var ?string $connectorName
     */
    #[JsonProperty('ConnectorName')]
    public ?string $connectorName;

    /**
     * @var ?DateTime $batchDate The batch date.
     */
    #[JsonProperty('BatchDate'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $batchDate;

    /**
     * @var ?float $batchAmount The amount of the batch.
     */
    #[JsonProperty('BatchAmount')]
    public ?float $batchAmount;

    /**
     * @var ?float $batchFeesAmount The total of fees in the batch.
     */
    #[JsonProperty('BatchFeesAmount')]
    public ?float $batchFeesAmount;

    /**
     * @var ?float $batchAuthAmount
     */
    #[JsonProperty('BatchAuthAmount')]
    public ?float $batchAuthAmount;

    /**
     * @var ?float $batchReleasedAmount Previously held funds that have been released after a risk review.
     */
    #[JsonProperty('BatchReleasedAmount')]
    public ?float $batchReleasedAmount;

    /**
     * @var ?float $batchHoldAmount The total amount of the batch that's being held for fraud or risk concerns.
     */
    #[JsonProperty('BatchHoldAmount')]
    public ?float $batchHoldAmount;

    /**
     * @var ?float $batchReturnedAmount Total amount of ACH returns deducted from batch.
     */
    #[JsonProperty('BatchReturnedAmount')]
    public ?float $batchReturnedAmount;

    /**
     * @var ?float $batchRefundAmount The total amount of refunds deducted from batch.
     */
    #[JsonProperty('BatchRefundAmount')]
    public ?float $batchRefundAmount;

    /**
     * @var ?float $batchSplitAmount Total of split transactions that included split funding instructions at the time of authorization.
     */
    #[JsonProperty('BatchSplitAmount')]
    public ?float $batchSplitAmount;

    /**
     * @var int $batchStatus The batch status. See [Batch Status](/developers/references/money-in-statuses#batch-status) for more.
     */
    #[JsonProperty('BatchStatus')]
    public int $batchStatus;

    /**
     * @var int $batchRecords The number of records in the batch.
     */
    #[JsonProperty('BatchRecords')]
    public int $batchRecords;

    /**
     * @var ?int $paypointId
     */
    #[JsonProperty('PaypointId')]
    public ?int $paypointId;

    /**
     * @var ?string $paypointName
     */
    #[JsonProperty('PaypointName')]
    public ?string $paypointName;

    /**
     * @var ?string $paypointDba
     */
    #[JsonProperty('PaypointDba')]
    public ?string $paypointDba;

    /**
     * @var string $parentOrgName The entrypoint's parent org.
     */
    #[JsonProperty('ParentOrgName')]
    public string $parentOrgName;

    /**
     * @var int $parentOrgId The parent organization ID.
     */
    #[JsonProperty('ParentOrgId')]
    public int $parentOrgId;

    /**
     * @var ?string $externalPaypointId
     */
    #[JsonProperty('externalPaypointID')]
    public ?string $externalPaypointId;

    /**
     * @var string $entryName
     */
    #[JsonProperty('EntryName')]
    public string $entryName;

    /**
     * @var ?string $bankName The bank name.
     */
    #[JsonProperty('BankName')]
    public ?string $bankName;

    /**
     * @var ?int $batchType The batch type.
     */
    #[JsonProperty('BatchType')]
    public ?int $batchType;

    /**
     * @var ?string $method The payment method used.
     */
    #[JsonProperty('Method')]
    public ?string $method;

    /**
     * @var ?DateTime $expectedDepositDate
     */
    #[JsonProperty('ExpectedDepositDate'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $expectedDepositDate;

    /**
     * @var ?DateTime $depositDate
     */
    #[JsonProperty('DepositDate'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $depositDate;

    /**
     * @var ?DateTime $transferDate The batch transfer date.
     */
    #[JsonProperty('TransferDate'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $transferDate;

    /**
     * @var ?QueryBatchesTransfer $transfer Transfer details for the batch.
     */
    #[JsonProperty('Transfer')]
    public ?QueryBatchesTransfer $transfer;

    /**
     * @param array{
     *   batchStatus: int,
     *   batchRecords: int,
     *   parentOrgName: string,
     *   parentOrgId: int,
     *   entryName: string,
     *   idBatch?: ?int,
     *   batchNumber?: ?string,
     *   transferIdentifier?: ?string,
     *   eventsData?: ?array<GeneralEvents>,
     *   connectorName?: ?string,
     *   batchDate?: ?DateTime,
     *   batchAmount?: ?float,
     *   batchFeesAmount?: ?float,
     *   batchAuthAmount?: ?float,
     *   batchReleasedAmount?: ?float,
     *   batchHoldAmount?: ?float,
     *   batchReturnedAmount?: ?float,
     *   batchRefundAmount?: ?float,
     *   batchSplitAmount?: ?float,
     *   paypointId?: ?int,
     *   paypointName?: ?string,
     *   paypointDba?: ?string,
     *   externalPaypointId?: ?string,
     *   bankName?: ?string,
     *   batchType?: ?int,
     *   method?: ?string,
     *   expectedDepositDate?: ?DateTime,
     *   depositDate?: ?DateTime,
     *   transferDate?: ?DateTime,
     *   transfer?: ?QueryBatchesTransfer,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->idBatch = $values['idBatch'] ?? null;
        $this->batchNumber = $values['batchNumber'] ?? null;
        $this->transferIdentifier = $values['transferIdentifier'] ?? null;
        $this->eventsData = $values['eventsData'] ?? null;
        $this->connectorName = $values['connectorName'] ?? null;
        $this->batchDate = $values['batchDate'] ?? null;
        $this->batchAmount = $values['batchAmount'] ?? null;
        $this->batchFeesAmount = $values['batchFeesAmount'] ?? null;
        $this->batchAuthAmount = $values['batchAuthAmount'] ?? null;
        $this->batchReleasedAmount = $values['batchReleasedAmount'] ?? null;
        $this->batchHoldAmount = $values['batchHoldAmount'] ?? null;
        $this->batchReturnedAmount = $values['batchReturnedAmount'] ?? null;
        $this->batchRefundAmount = $values['batchRefundAmount'] ?? null;
        $this->batchSplitAmount = $values['batchSplitAmount'] ?? null;
        $this->batchStatus = $values['batchStatus'];
        $this->batchRecords = $values['batchRecords'];
        $this->paypointId = $values['paypointId'] ?? null;
        $this->paypointName = $values['paypointName'] ?? null;
        $this->paypointDba = $values['paypointDba'] ?? null;
        $this->parentOrgName = $values['parentOrgName'];
        $this->parentOrgId = $values['parentOrgId'];
        $this->externalPaypointId = $values['externalPaypointId'] ?? null;
        $this->entryName = $values['entryName'];
        $this->bankName = $values['bankName'] ?? null;
        $this->batchType = $values['batchType'] ?? null;
        $this->method = $values['method'] ?? null;
        $this->expectedDepositDate = $values['expectedDepositDate'] ?? null;
        $this->depositDate = $values['depositDate'] ?? null;
        $this->transferDate = $values['transferDate'] ?? null;
        $this->transfer = $values['transfer'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
