<?php

namespace Payabli\PaymentLink\Requests;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\PaymentLink\Types\PaymentPageRequestBody;

class PayLinkDataBill extends JsonSerializableType
{
    /**
     * @var ?bool $amountFixed Indicates whether customer can modify the payment amount. A value of `true` means the amount isn't modifiable, a value `false` means the payor can modify the amount to pay.
     */
    public ?bool $amountFixed;

    /**
     * @var ?string $mail2 List of recipient email addresses. When there is more than one, separate them by a semicolon (;).
     */
    public ?string $mail2;

    /**
     * @var ?string $idempotencyKey
     */
    public ?string $idempotencyKey;

    /**
     * @var PaymentPageRequestBody $body
     */
    public PaymentPageRequestBody $body;

    /**
     * @param array{
     *   body: PaymentPageRequestBody,
     *   amountFixed?: ?bool,
     *   mail2?: ?string,
     *   idempotencyKey?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->amountFixed = $values['amountFixed'] ?? null;
        $this->mail2 = $values['mail2'] ?? null;
        $this->idempotencyKey = $values['idempotencyKey'] ?? null;
        $this->body = $values['body'];
    }
}
