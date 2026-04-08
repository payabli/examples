<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

class PayabliApiResponsePaylinks extends JsonSerializableType
{
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
     * @var int $responseCode
     */
    #[JsonProperty('responseCode')]
    public int $responseCode;

    /**
     * @var array<string, mixed> $responseData The paylink ID or error details.
     */
    #[JsonProperty('responseData'), ArrayType(['string' => 'mixed'])]
    public array $responseData;

    /**
     * @var string $responseText
     */
    #[JsonProperty('responseText')]
    public string $responseText;

    /**
     * @param array{
     *   isSuccess: bool,
     *   responseCode: int,
     *   responseData: array<string, mixed>,
     *   responseText: string,
     *   pageIdentifier?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->isSuccess = $values['isSuccess'];
        $this->pageIdentifier = $values['pageIdentifier'] ?? null;
        $this->responseCode = $values['responseCode'];
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
