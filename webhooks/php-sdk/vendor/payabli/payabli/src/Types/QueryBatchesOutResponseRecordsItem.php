<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use DateTime;
use Payabli\Core\Types\Date;

class QueryBatchesOutResponseRecordsItem extends JsonSerializableType
{
    /**
     * @var ?float $achAmount
     */
    #[JsonProperty('AchAmount')]
    public ?float $achAmount;

    /**
     * @var ?int $achRecords
     */
    #[JsonProperty('AchRecords')]
    public ?int $achRecords;

    /**
     * @var ?int $achStatus
     */
    #[JsonProperty('AchStatus')]
    public ?int $achStatus;

    /**
     * @var ?string $achStatusText
     */
    #[JsonProperty('AchStatusText')]
    public ?string $achStatusText;

    /**
     * @var ?float $batchAmount The amount of the batch.
     */
    #[JsonProperty('BatchAmount')]
    public ?float $batchAmount;

    /**
     * @var ?float $batchCancelledAmount
     */
    #[JsonProperty('BatchCancelledAmount')]
    public ?float $batchCancelledAmount;

    /**
     * @var ?int $batchCancelledRecords
     */
    #[JsonProperty('BatchCancelledRecords')]
    public ?int $batchCancelledRecords;

    /**
     * @var ?DateTime $batchDate The batch date.
     */
    #[JsonProperty('BatchDate'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $batchDate;

    /**
     * @var ?string $batchNumber
     */
    #[JsonProperty('BatchNumber')]
    public ?string $batchNumber;

    /**
     * @var ?float $batchPaidAmount
     */
    #[JsonProperty('BatchPaidAmount')]
    public ?float $batchPaidAmount;

    /**
     * @var ?int $batchPaidRecords
     */
    #[JsonProperty('BatchPaidRecords')]
    public ?int $batchPaidRecords;

    /**
     * @var ?float $batchProcessedAmount
     */
    #[JsonProperty('BatchProcessedAmount')]
    public ?float $batchProcessedAmount;

    /**
     * @var ?int $batchProcessedRecords
     */
    #[JsonProperty('BatchProcessedRecords')]
    public ?int $batchProcessedRecords;

    /**
     * @var ?float $batchProcessingAmount
     */
    #[JsonProperty('BatchProcessingAmount')]
    public ?float $batchProcessingAmount;

    /**
     * @var ?int $batchProcessingRecords
     */
    #[JsonProperty('BatchProcessingRecords')]
    public ?int $batchProcessingRecords;

    /**
     * @var ?int $batchRecords The number of records in the batch.
     */
    #[JsonProperty('BatchRecords')]
    public ?int $batchRecords;

    /**
     * @var ?int $batchStatus The batch status. See [Batch Status](/developers/references/money-out-statuses#batch-statuses) for more.
     */
    #[JsonProperty('BatchStatus')]
    public ?int $batchStatus;

    /**
     * @var ?string $batchStatusText A text description of the batch status.
     */
    #[JsonProperty('BatchStatusText')]
    public ?string $batchStatusText;

    /**
     * @var ?float $cardAmount
     */
    #[JsonProperty('CardAmount')]
    public ?float $cardAmount;

    /**
     * @var ?int $cardRecords
     */
    #[JsonProperty('CardRecords')]
    public ?int $cardRecords;

    /**
     * @var ?int $cardStatus
     */
    #[JsonProperty('CardStatus')]
    public ?int $cardStatus;

    /**
     * @var ?string $cardStatusText
     */
    #[JsonProperty('CardStatusText')]
    public ?string $cardStatusText;

    /**
     * @var ?float $checkAmount
     */
    #[JsonProperty('CheckAmount')]
    public ?float $checkAmount;

    /**
     * @var ?int $checkRecords
     */
    #[JsonProperty('CheckRecords')]
    public ?int $checkRecords;

    /**
     * @var ?int $checkStatus
     */
    #[JsonProperty('CheckStatus')]
    public ?int $checkStatus;

    /**
     * @var ?string $checkStatusText
     */
    #[JsonProperty('CheckStatusText')]
    public ?string $checkStatusText;

    /**
     * @var ?string $entryName
     */
    #[JsonProperty('EntryName')]
    public ?string $entryName;

    /**
     * @var ?string $externalPaypointId
     */
    #[JsonProperty('externalPaypointID')]
    public ?string $externalPaypointId;

    /**
     * @var ?int $idBatch The batch ID.
     */
    #[JsonProperty('IdBatch')]
    public ?int $idBatch;

    /**
     * @var ?string $parentOrgName The entrypoint's parent org.
     */
    #[JsonProperty('ParentOrgName')]
    public ?string $parentOrgName;

    /**
     * @var ?string $paypointDba Paypoint DBA name.
     */
    #[JsonProperty('PaypointDba')]
    public ?string $paypointDba;

    /**
     * @var ?int $paypointId Paypoint ID.
     */
    #[JsonProperty('PaypointId')]
    public ?int $paypointId;

    /**
     * @var ?string $paypointName Paypoint legal name.
     */
    #[JsonProperty('PaypointName')]
    public ?string $paypointName;

    /**
     * @var ?float $vcardAmount
     */
    #[JsonProperty('VcardAmount')]
    public ?float $vcardAmount;

    /**
     * @var ?int $vcardRecords
     */
    #[JsonProperty('VcardRecords')]
    public ?int $vcardRecords;

    /**
     * @var ?int $vcardStatus
     */
    #[JsonProperty('VcardStatus')]
    public ?int $vcardStatus;

    /**
     * @var ?string $vcardStatusText
     */
    #[JsonProperty('VcardStatusText')]
    public ?string $vcardStatusText;

    /**
     * @var ?float $wireAmount
     */
    #[JsonProperty('WireAmount')]
    public ?float $wireAmount;

    /**
     * @var ?int $wireRecords
     */
    #[JsonProperty('WireRecords')]
    public ?int $wireRecords;

    /**
     * @var ?int $wireStatus
     */
    #[JsonProperty('WireStatus')]
    public ?int $wireStatus;

    /**
     * @var ?string $wireStatusText
     */
    #[JsonProperty('WireStatusText')]
    public ?string $wireStatusText;

    /**
     * @param array{
     *   achAmount?: ?float,
     *   achRecords?: ?int,
     *   achStatus?: ?int,
     *   achStatusText?: ?string,
     *   batchAmount?: ?float,
     *   batchCancelledAmount?: ?float,
     *   batchCancelledRecords?: ?int,
     *   batchDate?: ?DateTime,
     *   batchNumber?: ?string,
     *   batchPaidAmount?: ?float,
     *   batchPaidRecords?: ?int,
     *   batchProcessedAmount?: ?float,
     *   batchProcessedRecords?: ?int,
     *   batchProcessingAmount?: ?float,
     *   batchProcessingRecords?: ?int,
     *   batchRecords?: ?int,
     *   batchStatus?: ?int,
     *   batchStatusText?: ?string,
     *   cardAmount?: ?float,
     *   cardRecords?: ?int,
     *   cardStatus?: ?int,
     *   cardStatusText?: ?string,
     *   checkAmount?: ?float,
     *   checkRecords?: ?int,
     *   checkStatus?: ?int,
     *   checkStatusText?: ?string,
     *   entryName?: ?string,
     *   externalPaypointId?: ?string,
     *   idBatch?: ?int,
     *   parentOrgName?: ?string,
     *   paypointDba?: ?string,
     *   paypointId?: ?int,
     *   paypointName?: ?string,
     *   vcardAmount?: ?float,
     *   vcardRecords?: ?int,
     *   vcardStatus?: ?int,
     *   vcardStatusText?: ?string,
     *   wireAmount?: ?float,
     *   wireRecords?: ?int,
     *   wireStatus?: ?int,
     *   wireStatusText?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->achAmount = $values['achAmount'] ?? null;
        $this->achRecords = $values['achRecords'] ?? null;
        $this->achStatus = $values['achStatus'] ?? null;
        $this->achStatusText = $values['achStatusText'] ?? null;
        $this->batchAmount = $values['batchAmount'] ?? null;
        $this->batchCancelledAmount = $values['batchCancelledAmount'] ?? null;
        $this->batchCancelledRecords = $values['batchCancelledRecords'] ?? null;
        $this->batchDate = $values['batchDate'] ?? null;
        $this->batchNumber = $values['batchNumber'] ?? null;
        $this->batchPaidAmount = $values['batchPaidAmount'] ?? null;
        $this->batchPaidRecords = $values['batchPaidRecords'] ?? null;
        $this->batchProcessedAmount = $values['batchProcessedAmount'] ?? null;
        $this->batchProcessedRecords = $values['batchProcessedRecords'] ?? null;
        $this->batchProcessingAmount = $values['batchProcessingAmount'] ?? null;
        $this->batchProcessingRecords = $values['batchProcessingRecords'] ?? null;
        $this->batchRecords = $values['batchRecords'] ?? null;
        $this->batchStatus = $values['batchStatus'] ?? null;
        $this->batchStatusText = $values['batchStatusText'] ?? null;
        $this->cardAmount = $values['cardAmount'] ?? null;
        $this->cardRecords = $values['cardRecords'] ?? null;
        $this->cardStatus = $values['cardStatus'] ?? null;
        $this->cardStatusText = $values['cardStatusText'] ?? null;
        $this->checkAmount = $values['checkAmount'] ?? null;
        $this->checkRecords = $values['checkRecords'] ?? null;
        $this->checkStatus = $values['checkStatus'] ?? null;
        $this->checkStatusText = $values['checkStatusText'] ?? null;
        $this->entryName = $values['entryName'] ?? null;
        $this->externalPaypointId = $values['externalPaypointId'] ?? null;
        $this->idBatch = $values['idBatch'] ?? null;
        $this->parentOrgName = $values['parentOrgName'] ?? null;
        $this->paypointDba = $values['paypointDba'] ?? null;
        $this->paypointId = $values['paypointId'] ?? null;
        $this->paypointName = $values['paypointName'] ?? null;
        $this->vcardAmount = $values['vcardAmount'] ?? null;
        $this->vcardRecords = $values['vcardRecords'] ?? null;
        $this->vcardStatus = $values['vcardStatus'] ?? null;
        $this->vcardStatusText = $values['vcardStatusText'] ?? null;
        $this->wireAmount = $values['wireAmount'] ?? null;
        $this->wireRecords = $values['wireRecords'] ?? null;
        $this->wireStatus = $values['wireStatus'] ?? null;
        $this->wireStatusText = $values['wireStatusText'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
