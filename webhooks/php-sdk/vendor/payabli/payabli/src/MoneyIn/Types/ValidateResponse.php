<?php

namespace Payabli\MoneyIn\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * Response for card validation endpoint
 */
class ValidateResponse extends JsonSerializableType
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
     * @var ValidateResponseData $responseData
     */
    #[JsonProperty('responseData')]
    public ValidateResponseData $responseData;

    /**
     * @param array{
     *   responseText: string,
     *   isSuccess: bool,
     *   responseData: ValidateResponseData,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->responseText = $values['responseText'];
        $this->isSuccess = $values['isSuccess'];
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
