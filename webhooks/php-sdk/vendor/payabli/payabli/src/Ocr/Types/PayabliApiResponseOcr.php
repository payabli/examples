<?php

namespace Payabli\Ocr\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class PayabliApiResponseOcr extends JsonSerializableType
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
     * @var int $responseCode
     */
    #[JsonProperty('responseCode')]
    public int $responseCode;

    /**
     * @var ?OcrResponseData $responseData Details of the OCR processing result
     */
    #[JsonProperty('responseData')]
    public ?OcrResponseData $responseData;

    /**
     * @param array{
     *   isSuccess: bool,
     *   responseText: string,
     *   responseCode: int,
     *   responseData?: ?OcrResponseData,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->isSuccess = $values['isSuccess'];
        $this->responseText = $values['responseText'];
        $this->responseCode = $values['responseCode'];
        $this->responseData = $values['responseData'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
