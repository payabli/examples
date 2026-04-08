<?php

namespace Payabli\MoneyIn\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * Response for MoneyIn/void endpoint
 */
class VoidResponse extends JsonSerializableType
{
    /**
     * @var int $responseCode
     */
    #[JsonProperty('responseCode')]
    public int $responseCode;

    /**
     * @var ?string $pageIdentifier
     */
    #[JsonProperty('pageIdentifier')]
    public ?string $pageIdentifier;

    /**
     * @var int $roomId
     */
    #[JsonProperty('roomId')]
    public int $roomId;

    /**
     * @var bool $isSuccess
     */
    #[JsonProperty('isSuccess')]
    public bool $isSuccess;

    /**
     * @var string $responseText
     */
    #[JsonProperty('responseText')]
    public string $responseText;

    /**
     * @var VoidResponseData $responseData
     */
    #[JsonProperty('responseData')]
    public VoidResponseData $responseData;

    /**
     * @param array{
     *   responseCode: int,
     *   roomId: int,
     *   isSuccess: bool,
     *   responseText: string,
     *   responseData: VoidResponseData,
     *   pageIdentifier?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->responseCode = $values['responseCode'];
        $this->pageIdentifier = $values['pageIdentifier'] ?? null;
        $this->roomId = $values['roomId'];
        $this->isSuccess = $values['isSuccess'];
        $this->responseText = $values['responseText'];
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
