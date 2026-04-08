<?php

namespace Payabli\MoneyIn\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * General response for GetPaid endpoint supporting multiple payment methods
 */
class PayabliApiResponseGetPaid extends JsonSerializableType
{
    /**
     * @var string $responseText
     */
    #[JsonProperty('responseText')]
    public string $responseText;

    /**
     * @var bool $isSuccess
     */
    #[JsonProperty('isSuccess')]
    public bool $isSuccess;

    /**
     * @var ?string $pageIdentifier
     */
    #[JsonProperty('pageIdentifier')]
    public ?string $pageIdentifier;

    /**
     * @var GetPaidResponseData $responseData
     */
    #[JsonProperty('responseData')]
    public GetPaidResponseData $responseData;

    /**
     * @param array{
     *   responseText: string,
     *   isSuccess: bool,
     *   responseData: GetPaidResponseData,
     *   pageIdentifier?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->responseText = $values['responseText'];
        $this->isSuccess = $values['isSuccess'];
        $this->pageIdentifier = $values['pageIdentifier'] ?? null;
        $this->responseData = $values['responseData'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
