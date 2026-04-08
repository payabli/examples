<?php

namespace Payabli\Ocr\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class OcrResponseData extends JsonSerializableType
{
    /**
     * @var ?OcrResultData $resultData
     */
    #[JsonProperty('resultData')]
    public ?OcrResultData $resultData;

    /**
     * @param array{
     *   resultData?: ?OcrResultData,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->resultData = $values['resultData'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
