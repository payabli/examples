<?php

namespace Payabli\PaymentLink\Requests;

use Payabli\Core\Json\JsonSerializableType;

class RefreshPayLinkFromIdRequest extends JsonSerializableType
{
    /**
     * @var ?bool $amountFixed Indicates whether customer can modify the payment amount. A value of `true` means the amount isn't modifiable, a value `false` means the payor can modify the amount to pay.
     */
    public ?bool $amountFixed;

    /**
     * @param array{
     *   amountFixed?: ?bool,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->amountFixed = $values['amountFixed'] ?? null;
    }
}
