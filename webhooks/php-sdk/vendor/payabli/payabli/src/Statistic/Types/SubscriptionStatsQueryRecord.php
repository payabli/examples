<?php

namespace Payabli\Statistic\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class SubscriptionStatsQueryRecord extends JsonSerializableType
{
    /**
     * @var string $interval Time interval identifier
     */
    #[JsonProperty('interval')]
    public string $interval;

    /**
     * @var int $count Number of subscriptions
     */
    #[JsonProperty('count')]
    public int $count;

    /**
     * @var float $volume Subscription volume
     */
    #[JsonProperty('volume')]
    public float $volume;

    /**
     * @param array{
     *   interval: string,
     *   count: int,
     *   volume: float,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->interval = $values['interval'];
        $this->count = $values['count'];
        $this->volume = $values['volume'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
