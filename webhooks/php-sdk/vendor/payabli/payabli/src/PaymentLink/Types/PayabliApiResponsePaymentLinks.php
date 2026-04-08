<?php

namespace Payabli\PaymentLink\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class PayabliApiResponsePaymentLinks extends JsonSerializableType
{
    /**
     * @var bool $isSuccess
     */
    #[JsonProperty('isSuccess')]
    public bool $isSuccess;

    /**
     * @var ?string $responseData If `isSuccess` = true, this contains the payment link identifier. If `isSuccess` = false, this contains the reason of the error.
     */
    #[JsonProperty('responseData')]
    public ?string $responseData;

    /**
     * @var string $responseText
     */
    #[JsonProperty('responseText')]
    public string $responseText;

    /**
     * @param array{
     *   isSuccess: bool,
     *   responseText: string,
     *   responseData?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->isSuccess = $values['isSuccess'];
        $this->responseData = $values['responseData'] ?? null;
        $this->responseText = $values['responseText'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
