<?php

namespace Payabli\Invoice\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * Response schema for operations for sending invoices or getting next invoice number.
 */
class InvoiceNumberResponse extends JsonSerializableType
{
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
     * @var string $responseData If `isSuccess` = true, this contains the next available invoice number in the format defined by paypoint settings. If `isSuccess` = false, this contains the reason for the error.
     */
    #[JsonProperty('responseData')]
    public string $responseData;

    /**
     * @param array{
     *   isSuccess: bool,
     *   responseText: string,
     *   responseData: string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
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
