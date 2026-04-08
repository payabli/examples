<?php

namespace Payabli\Bill\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class BillOutDataScheduledOptions extends JsonSerializableType
{
    /**
     * @var ?string $storedMethodId The ID of the stored payment method to use for the bill.
     */
    #[JsonProperty('storedMethodId')]
    public ?string $storedMethodId;

    /**
     * @param array{
     *   storedMethodId?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->storedMethodId = $values['storedMethodId'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
