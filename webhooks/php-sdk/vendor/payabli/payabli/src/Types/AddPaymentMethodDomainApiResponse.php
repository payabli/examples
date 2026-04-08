<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * Response for the add payment method domain operation.
 */
class AddPaymentMethodDomainApiResponse extends JsonSerializableType
{
    /**
     * @var bool $isSuccess
     */
    #[JsonProperty('isSuccess')]
    public bool $isSuccess;

    /**
     * @var string $pageidentifier
     */
    #[JsonProperty('pageidentifier')]
    public string $pageidentifier;

    /**
     * @var PaymentMethodDomainApiResponse $responseData
     */
    #[JsonProperty('responseData')]
    public PaymentMethodDomainApiResponse $responseData;

    /**
     * @var string $responseText
     */
    #[JsonProperty('responseText')]
    public string $responseText;

    /**
     * @param array{
     *   isSuccess: bool,
     *   pageidentifier: string,
     *   responseData: PaymentMethodDomainApiResponse,
     *   responseText: string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->isSuccess = $values['isSuccess'];
        $this->pageidentifier = $values['pageidentifier'];
        $this->responseData = $values['responseData'];
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
