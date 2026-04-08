<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

class CustomerSummaryRecord extends JsonSerializableType
{
    /**
     * @var ?int $numberofTransactions Number total of transactions or payments
     */
    #[JsonProperty('numberofTransactions')]
    public ?int $numberofTransactions;

    /**
     * @var ?array<TransactionQueryRecords> $recentTransactions List of more recent 5 transactions belonging to the customer
     */
    #[JsonProperty('recentTransactions'), ArrayType([TransactionQueryRecords::class])]
    public ?array $recentTransactions;

    /**
     * @var ?float $totalAmountTransactions Total amount in transactions
     */
    #[JsonProperty('totalAmountTransactions')]
    public ?float $totalAmountTransactions;

    /**
     * @var ?float $totalNetAmountTransactions Total net amount in transactions
     */
    #[JsonProperty('totalNetAmountTransactions')]
    public ?float $totalNetAmountTransactions;

    /**
     * @param array{
     *   numberofTransactions?: ?int,
     *   recentTransactions?: ?array<TransactionQueryRecords>,
     *   totalAmountTransactions?: ?float,
     *   totalNetAmountTransactions?: ?float,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->numberofTransactions = $values['numberofTransactions'] ?? null;
        $this->recentTransactions = $values['recentTransactions'] ?? null;
        $this->totalAmountTransactions = $values['totalAmountTransactions'] ?? null;
        $this->totalNetAmountTransactions = $values['totalNetAmountTransactions'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
