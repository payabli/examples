<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;
use DateTime;
use Payabli\Core\Types\Date;

class QueryCFeeTransaction extends JsonSerializableType
{
    /**
     * @var ?string $cFeeTransid
     */
    #[JsonProperty('cFeeTransid')]
    public ?string $cFeeTransid;

    /**
     * @var ?float $feeAmount
     */
    #[JsonProperty('feeAmount')]
    public ?float $feeAmount;

    /**
     * @var ?string $operation
     */
    #[JsonProperty('operation')]
    public ?string $operation;

    /**
     * @var ?int $refundId
     */
    #[JsonProperty('refundId')]
    public ?int $refundId;

    /**
     * @var ?array<string, mixed> $responseData
     */
    #[JsonProperty('responseData'), ArrayType(['string' => 'mixed'])]
    public ?array $responseData;

    /**
     * @var ?int $settlementStatus
     */
    #[JsonProperty('settlementStatus')]
    public ?int $settlementStatus;

    /**
     * @var ?DateTime $transactionTime
     */
    #[JsonProperty('transactionTime'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $transactionTime;

    /**
     * @var ?int $transStatus
     */
    #[JsonProperty('transStatus')]
    public ?int $transStatus;

    /**
     * @param array{
     *   cFeeTransid?: ?string,
     *   feeAmount?: ?float,
     *   operation?: ?string,
     *   refundId?: ?int,
     *   responseData?: ?array<string, mixed>,
     *   settlementStatus?: ?int,
     *   transactionTime?: ?DateTime,
     *   transStatus?: ?int,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->cFeeTransid = $values['cFeeTransid'] ?? null;
        $this->feeAmount = $values['feeAmount'] ?? null;
        $this->operation = $values['operation'] ?? null;
        $this->refundId = $values['refundId'] ?? null;
        $this->responseData = $values['responseData'] ?? null;
        $this->settlementStatus = $values['settlementStatus'] ?? null;
        $this->transactionTime = $values['transactionTime'] ?? null;
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
