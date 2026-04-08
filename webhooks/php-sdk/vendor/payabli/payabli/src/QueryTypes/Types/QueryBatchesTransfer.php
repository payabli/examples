<?php

namespace Payabli\QueryTypes\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use DateTime;
use Payabli\Core\Types\Date;

/**
 * Transfer details within a batch response.
 */
class QueryBatchesTransfer extends JsonSerializableType
{
    /**
     * @var ?int $transferId The transfer ID.
     */
    #[JsonProperty('TransferId')]
    public ?int $transferId;

    /**
     * @var ?DateTime $transferDate The transfer date.
     */
    #[JsonProperty('TransferDate'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $transferDate;

    /**
     * @var ?string $processor The processor used for the transfer.
     */
    #[JsonProperty('Processor')]
    public ?string $processor;

    /**
     * @var ?int $transferStatus The transfer status.
     */
    #[JsonProperty('TransferStatus')]
    public ?int $transferStatus;

    /**
     * @var ?float $grossAmount The gross amount of the transfer.
     */
    #[JsonProperty('GrossAmount')]
    public ?float $grossAmount;

    /**
     * @var ?float $chargeBackAmount The chargeback amount.
     */
    #[JsonProperty('ChargeBackAmount')]
    public ?float $chargeBackAmount;

    /**
     * @var ?float $returnedAmount The returned amount.
     */
    #[JsonProperty('ReturnedAmount')]
    public ?float $returnedAmount;

    /**
     * @var ?float $refundAmount The refund amount.
     */
    #[JsonProperty('RefundAmount')]
    public ?float $refundAmount;

    /**
     * @var ?float $holdAmount The amount being held.
     */
    #[JsonProperty('HoldAmount')]
    public ?float $holdAmount;

    /**
     * @var ?float $releasedAmount The amount that has been released.
     */
    #[JsonProperty('ReleasedAmount')]
    public ?float $releasedAmount;

    /**
     * @var ?float $billingFeesAmount The billing fees amount.
     */
    #[JsonProperty('BillingFeesAmount')]
    public ?float $billingFeesAmount;

    /**
     * @var ?float $thirdPartyPaidAmount The third party paid amount.
     */
    #[JsonProperty('ThirdPartyPaidAmount')]
    public ?float $thirdPartyPaidAmount;

    /**
     * @var ?float $adjustmentsAmount The adjustments amount.
     */
    #[JsonProperty('AdjustmentsAmount')]
    public ?float $adjustmentsAmount;

    /**
     * @var ?float $netFundedAmount The net funded amount.
     */
    #[JsonProperty('NetFundedAmount')]
    public ?float $netFundedAmount;

    /**
     * @param array{
     *   transferId?: ?int,
     *   transferDate?: ?DateTime,
     *   processor?: ?string,
     *   transferStatus?: ?int,
     *   grossAmount?: ?float,
     *   chargeBackAmount?: ?float,
     *   returnedAmount?: ?float,
     *   refundAmount?: ?float,
     *   holdAmount?: ?float,
     *   releasedAmount?: ?float,
     *   billingFeesAmount?: ?float,
     *   thirdPartyPaidAmount?: ?float,
     *   adjustmentsAmount?: ?float,
     *   netFundedAmount?: ?float,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->transferId = $values['transferId'] ?? null;
        $this->transferDate = $values['transferDate'] ?? null;
        $this->processor = $values['processor'] ?? null;
        $this->transferStatus = $values['transferStatus'] ?? null;
        $this->grossAmount = $values['grossAmount'] ?? null;
        $this->chargeBackAmount = $values['chargeBackAmount'] ?? null;
        $this->returnedAmount = $values['returnedAmount'] ?? null;
        $this->refundAmount = $values['refundAmount'] ?? null;
        $this->holdAmount = $values['holdAmount'] ?? null;
        $this->releasedAmount = $values['releasedAmount'] ?? null;
        $this->billingFeesAmount = $values['billingFeesAmount'] ?? null;
        $this->thirdPartyPaidAmount = $values['thirdPartyPaidAmount'] ?? null;
        $this->adjustmentsAmount = $values['adjustmentsAmount'] ?? null;
        $this->netFundedAmount = $values['netFundedAmount'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
