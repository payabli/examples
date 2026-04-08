<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class SplitFundingRefundContent extends JsonSerializableType
{
    /**
     * @var ?string $accountId The accountId for the account the transaction was routed to.
     */
    #[JsonProperty('accountId')]
    public ?string $accountId;

    /**
     * @var ?float $amount The amount to refund to this account.
     */
    #[JsonProperty('amount')]
    public ?float $amount;

    /**
     * @var ?string $description Refund description.
     */
    #[JsonProperty('description')]
    public ?string $description;

    /**
     * @var ?string $originationEntryPoint The entrypoint the transaction belongs to.
     */
    #[JsonProperty('originationEntryPoint')]
    public ?string $originationEntryPoint;

    /**
     * @param array{
     *   accountId?: ?string,
     *   amount?: ?float,
     *   description?: ?string,
     *   originationEntryPoint?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->accountId = $values['accountId'] ?? null;
        $this->amount = $values['amount'] ?? null;
        $this->description = $values['description'] ?? null;
        $this->originationEntryPoint = $values['originationEntryPoint'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
