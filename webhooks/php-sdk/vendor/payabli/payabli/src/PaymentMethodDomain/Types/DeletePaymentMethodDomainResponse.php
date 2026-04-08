<?php

namespace Payabli\PaymentMethodDomain\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\Union;

class DeletePaymentMethodDomainResponse extends JsonSerializableType
{
    /**
     * @var bool $isSuccess
     */
    #[JsonProperty('isSuccess')]
    public bool $isSuccess;

    /**
     * @var string $pageIdentifier
     */
    #[JsonProperty('pageIdentifier')]
    public string $pageIdentifier;

    /**
     * @var (
     *    string
     *   |int
     * ) $responseData The deleted domain's domain ID.
     */
    #[JsonProperty('responseData'), Union('string', 'integer')]
    public string|int $responseData;

    /**
     * @var string $responseText
     */
    #[JsonProperty('responseText')]
    public string $responseText;

    /**
     * @param array{
     *   isSuccess: bool,
     *   pageIdentifier: string,
     *   responseData: (
     *    string
     *   |int
     * ),
     *   responseText: string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->isSuccess = $values['isSuccess'];
        $this->pageIdentifier = $values['pageIdentifier'];
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
