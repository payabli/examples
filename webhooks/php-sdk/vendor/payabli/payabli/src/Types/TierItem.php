<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class TierItem extends JsonSerializableType
{
    /**
     * @var ?float $amountxAuth
     */
    #[JsonProperty('amountxAuth')]
    public ?float $amountxAuth;

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
     * @var ?float $percentxAuth
     */
    #[JsonProperty('percentxAuth')]
    public ?float $percentxAuth;

    /**
     * @param array{
     *   amountxAuth?: ?float,
     *   highPayRange?: ?float,
     *   lowPayRange?: ?float,
     *   percentxAuth?: ?float,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->amountxAuth = $values['amountxAuth'] ?? null;
        $this->highPayRange = $values['highPayRange'] ?? null;
        $this->lowPayRange = $values['lowPayRange'] ?? null;
        $this->percentxAuth = $values['percentxAuth'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
