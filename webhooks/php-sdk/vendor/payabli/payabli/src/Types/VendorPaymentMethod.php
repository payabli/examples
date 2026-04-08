<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * Payment method object to use for the payout.
 * - `{ method: "managed" }` - Managed payment method
 * - `{ method: "vcard" }` - Virtual card payment method
 * - `{ method: "check" }` - Check payment method
 * - `{ method: "ach", storedMethodId?: "..." }` - ACH payment method with optional stored method ID
 */
class VendorPaymentMethod extends JsonSerializableType
{
    /**
     * @var string $method Payment method type - "managed", "vcard", "check", or "ach"
     */
    #[JsonProperty('method')]
    public string $method;

    /**
     * @var ?string $storedMethodId ID of the stored ACH payment method. Only applicable when method is "ach". Required when using a previously saved ACH method when the vendor has more than one saved method. See the [Payouts with saved ACH payment methods](/developers/developer-guides/pay-out-manage-payouts) section for more details.
     */
    #[JsonProperty('storedMethodId')]
    public ?string $storedMethodId;

    /**
     * @param array{
     *   method: string,
     *   storedMethodId?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->method = $values['method'];
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
