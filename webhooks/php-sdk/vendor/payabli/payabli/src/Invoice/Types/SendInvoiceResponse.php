<?php

namespace Payabli\Invoice\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class SendInvoiceResponse extends JsonSerializableType
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
     * @param array{
     *   isSuccess: bool,
     *   responseText: string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->isSuccess = $values['isSuccess'];
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
