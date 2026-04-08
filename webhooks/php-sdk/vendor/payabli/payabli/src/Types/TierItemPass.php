<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class TierItemPass extends JsonSerializableType
{
    /**
     * @var ?float $amountFeeoneTime
     */
    #[JsonProperty('amountFeeone-time')]
    public ?float $amountFeeoneTime;

    /**
     * @var ?float $amountFeeRecurring
     */
    #[JsonProperty('amountFeeRecurring')]
    public ?float $amountFeeRecurring;

    /**
     * @var ?float $highPayRange
     */
    #[JsonProperty('highPayRange')]
    public ?float $highPayRange;

    /**
     * @var ?float $lowPayRange
     */
    #[JsonProperty('lowPayRange')]
    public ?float $lowPayRange;

    /**
     * @var ?float $percentFeeoneTime
     */
    #[JsonProperty('percentFeeone-time')]
    public ?float $percentFeeoneTime;

    /**
     * @var ?float $percentFeeRecurring
     */
    #[JsonProperty('percentFeeRecurring')]
    public ?float $percentFeeRecurring;

    /**
     * @param array{
     *   amountFeeoneTime?: ?float,
     *   amountFeeRecurring?: ?float,
     *   highPayRange?: ?float,
     *   lowPayRange?: ?float,
     *   percentFeeoneTime?: ?float,
     *   percentFeeRecurring?: ?float,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->amountFeeoneTime = $values['amountFeeoneTime'] ?? null;
        $this->amountFeeRecurring = $values['amountFeeRecurring'] ?? null;
        $this->highPayRange = $values['highPayRange'] ?? null;
        $this->lowPayRange = $values['lowPayRange'] ?? null;
        $this->percentFeeoneTime = $values['percentFeeoneTime'] ?? null;
        $this->percentFeeRecurring = $values['percentFeeRecurring'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
