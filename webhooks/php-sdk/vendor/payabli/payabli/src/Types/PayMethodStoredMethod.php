<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * The required and recommended fields for a payment made with a stored payment method.
 */
class PayMethodStoredMethod extends JsonSerializableType
{
    /**
     * @var ?string $initiator
     */
    #[JsonProperty('initiator')]
    public ?string $initiator;

    /**
     * @var value-of<PayMethodStoredMethodMethod> $method Method to use for the transaction. Use either `card` or `ach`, depending on what kind of method was tokenized to use a saved payment method for this transaction.
     */
    #[JsonProperty('method')]
    public string $method;

    /**
     * @var ?string $storedMethodId Payabli identifier of a tokenized payment method.
     */
    #[JsonProperty('storedMethodId')]
    public ?string $storedMethodId;

    /**
     * @var ?string $storedMethodUsageType
     */
    #[JsonProperty('storedMethodUsageType')]
    public ?string $storedMethodUsageType;

    /**
     * @param array{
     *   method: value-of<PayMethodStoredMethodMethod>,
     *   initiator?: ?string,
     *   storedMethodId?: ?string,
     *   storedMethodUsageType?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->initiator = $values['initiator'] ?? null;
        $this->method = $values['method'];
        $this->storedMethodId = $values['storedMethodId'] ?? null;
        $this->storedMethodUsageType = $values['storedMethodUsageType'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
