<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class PayabliApiResponseError400 extends JsonSerializableType
{
    /**
     * @var ?bool $isSuccess Boolean indicating whether the operation was successful. A `true` value indicates success. A `false` value indicates failure.
     */
    #[JsonProperty('isSuccess')]
    public ?bool $isSuccess;

    /**
     * @var ?string $pageidentifier
     */
    #[JsonProperty('pageidentifier')]
    public ?string $pageidentifier;

    /**
     * @var ?int $responseCode A code that indicates the operation's failure reason. See [API Response Codes](https://docs.payabli.com/api-reference/api-responses) for a full reference.
     */
    #[JsonProperty('responseCode')]
    public ?int $responseCode;

    /**
     * @var ?PayabliApiResponseError400ResponseData $responseData Describes the reason for a failed operation and how to resolve it.
     */
    #[JsonProperty('responseData')]
    public ?PayabliApiResponseError400ResponseData $responseData;

    /**
     * @var ?string $responseText Response text for operation: 'Success' or 'Declined'.
     */
    #[JsonProperty('responseText')]
    public ?string $responseText;

    /**
     * @param array{
     *   isSuccess?: ?bool,
     *   pageidentifier?: ?string,
     *   responseCode?: ?int,
     *   responseData?: ?PayabliApiResponseError400ResponseData,
     *   responseText?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->isSuccess = $values['isSuccess'] ?? null;
        $this->pageidentifier = $values['pageidentifier'] ?? null;
        $this->responseCode = $values['responseCode'] ?? null;
        $this->responseData = $values['responseData'] ?? null;
        $this->responseText = $values['responseText'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
