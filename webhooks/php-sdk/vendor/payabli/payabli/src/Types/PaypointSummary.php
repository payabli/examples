<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class PaypointSummary extends JsonSerializableType
{
    /**
     * @var ?float $amountSubs
     */
    #[JsonProperty('amountSubs')]
    public ?float $amountSubs;

    /**
     * @var ?float $amountTx
     */
    #[JsonProperty('amountTx')]
    public ?float $amountTx;

    /**
     * @var ?int $countSubs
     */
    #[JsonProperty('countSubs')]
    public ?int $countSubs;

    /**
     * @var ?int $countTx
     */
    #[JsonProperty('countTx')]
    public ?int $countTx;

    /**
     * @var ?int $customers
     */
    #[JsonProperty('customers')]
    public ?int $customers;

    /**
     * @param array{
     *   amountSubs?: ?float,
     *   amountTx?: ?float,
     *   countSubs?: ?int,
     *   countTx?: ?int,
     *   customers?: ?int,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->amountSubs = $values['amountSubs'] ?? null;
        $this->amountTx = $values['amountTx'] ?? null;
        $this->countSubs = $values['countSubs'] ?? null;
        $this->countTx = $values['countTx'] ?? null;
        $this->customers = $values['customers'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
