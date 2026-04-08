<?php

namespace Payabli\Invoice\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\Union;

/**
 * Response schema for invoice operations.
 */
class InvoiceResponseWithoutData extends JsonSerializableType
{
    /**
     * @var bool $isSuccess
     */
    #[JsonProperty('isSuccess')]
    public bool $isSuccess;

    /**
     * @var int $responseCode
     */
    #[JsonProperty('responseCode')]
    public int $responseCode;

    /**
     * @var (
     *    string
     *   |int
     * ) $responseData If `isSuccess` = true, this contains the identifier of the invoice. If `isSuccess` = false, this contains the reason for the failure.
     */
    #[JsonProperty('responseData'), Union('string', 'integer')]
    public string|int $responseData;

    /**
     * @var string $responseText
     */
    #[JsonProperty('responseText')]
    public string $responseText;

    /**
     * @var ?string $pageidentifier
     */
    #[JsonProperty('pageidentifier')]
    public ?string $pageidentifier;

    /**
     * @var int $roomId
     */
    #[JsonProperty('roomId')]
    public int $roomId;

    /**
     * @param array{
     *   isSuccess: bool,
     *   responseCode: int,
     *   responseData: (
     *    string
     *   |int
     * ),
     *   responseText: string,
     *   roomId: int,
     *   pageidentifier?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->isSuccess = $values['isSuccess'];
        $this->responseCode = $values['responseCode'];
        $this->responseData = $values['responseData'];
        $this->responseText = $values['responseText'];
        $this->pageidentifier = $values['pageidentifier'] ?? null;
        $this->roomId = $values['roomId'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
