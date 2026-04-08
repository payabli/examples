<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class SplitFundingContent extends JsonSerializableType
{
    /**
     * @var ?string $accountId The accountId for the account the split should be sent to.
     */
    #[JsonProperty('accountId')]
    public ?string $accountId;

    /**
     * @var ?float $amount Amount from the transaction to sent to this recipient.
     */
    #[JsonProperty('amount')]
    public ?float $amount;

    /**
     * @var ?string $description A description for the split.
     */
    #[JsonProperty('description')]
    public ?string $description;

    /**
     * @var ?string $recipientEntryPoint The entrypoint the split should be sent to.
     */
    #[JsonProperty('recipientEntryPoint')]
    public ?string $recipientEntryPoint;

    /**
     * @param array{
     *   accountId?: ?string,
     *   amount?: ?float,
     *   description?: ?string,
     *   recipientEntryPoint?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->accountId = $values['accountId'] ?? null;
        $this->amount = $values['amount'] ?? null;
        $this->description = $values['description'] ?? null;
        $this->recipientEntryPoint = $values['recipientEntryPoint'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
