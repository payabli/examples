<?php

namespace Payabli\Statistic\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class StatBasicQueryRecord extends JsonSerializableType
{
    /**
     * @var string $statX Statistical grouping identifier
     */
    #[JsonProperty('statX')]
    public string $statX;

    /**
     * @var int $inTransactions Number of incoming transactions
     */
    #[JsonProperty('inTransactions')]
    public int $inTransactions;

    /**
     * @var float $inTransactionsVolume Volume of incoming transactions
     */
    #[JsonProperty('inTransactionsVolume')]
    public float $inTransactionsVolume;

    /**
     * @param array{
     *   statX: string,
     *   inTransactions: int,
     *   inTransactionsVolume: float,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->statX = $values['statX'];
        $this->inTransactions = $values['inTransactions'];
        $this->inTransactionsVolume = $values['inTransactionsVolume'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
