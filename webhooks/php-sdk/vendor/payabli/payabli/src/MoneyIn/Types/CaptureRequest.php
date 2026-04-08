<?php

namespace Payabli\MoneyIn\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class CaptureRequest extends JsonSerializableType
{
    /**
     * @var CapturePaymentDetails $paymentDetails
     */
    #[JsonProperty('paymentDetails')]
    public CapturePaymentDetails $paymentDetails;

    /**
     * @param array{
     *   paymentDetails: CapturePaymentDetails,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->paymentDetails = $values['paymentDetails'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
