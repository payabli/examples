<?php

namespace Payabli\Subscription\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * The required and recommended fields for a payment made with a stored payment method.
 */
class RequestSchedulePaymentMethodInitiator extends JsonSerializableType
{
    /**
     * @var ?string $initiator
     */
    #[JsonProperty('initiator')]
    public ?string $initiator;

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
     *   initiator?: ?string,
     *   storedMethodId?: ?string,
     *   storedMethodUsageType?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->initiator = $values['initiator'] ?? null;
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
