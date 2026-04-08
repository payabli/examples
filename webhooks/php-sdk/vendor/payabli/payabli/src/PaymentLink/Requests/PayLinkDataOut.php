<?php

namespace Payabli\PaymentLink\Requests;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\PaymentLink\Types\PaymentPageRequestBody;

class PayLinkDataOut extends JsonSerializableType
{
    /**
     * @var string $entryPoint
     */
    public string $entryPoint;

    /**
     * @var string $vendorNumber The vendor number for the vendor being paid with this payment link.
     */
    public string $vendorNumber;

    /**
     * @var ?string $mail2 List of recipient email addresses. When there is more than one, separate them by a semicolon (;).
     */
    public ?string $mail2;

    /**
     * @var ?string $amountFixed Indicates whether customer can modify the payment amount. A value of `true` means the amount isn't modifiable, a value `false` means the payor can modify the amount to pay.
     */
    public ?string $amountFixed;

    /**
     * @var PaymentPageRequestBody $body
     */
    public PaymentPageRequestBody $body;

    /**
     * @param array{
     *   entryPoint: string,
     *   vendorNumber: string,
     *   body: PaymentPageRequestBody,
     *   mail2?: ?string,
     *   amountFixed?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->entryPoint = $values['entryPoint'];
        $this->vendorNumber = $values['vendorNumber'];
        $this->mail2 = $values['mail2'] ?? null;
        $this->amountFixed = $values['amountFixed'] ?? null;
        $this->body = $values['body'];
    }
}
