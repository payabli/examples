<?php

namespace Payabli\MoneyIn\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class RefundWithInstructionsResponse extends JsonSerializableType
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
     * @var ResponseDataRefunds $responseData
     */
    #[JsonProperty('responseData')]
    public ResponseDataRefunds $responseData;

    /**
     * @var ?string $pageidentifier
     */
    #[JsonProperty('pageidentifier')]
    public ?string $pageidentifier;

    /**
     * @param array{
     *   responseText: string,
     *   isSuccess: bool,
     *   responseData: ResponseDataRefunds,
     *   pageidentifier?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->responseText = $values['responseText'];
        $this->isSuccess = $values['isSuccess'];
        $this->responseData = $values['responseData'];
        $this->pageidentifier = $values['pageidentifier'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
