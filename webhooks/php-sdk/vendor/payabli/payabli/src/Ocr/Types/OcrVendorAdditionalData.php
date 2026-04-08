<?php

namespace Payabli\Ocr\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class OcrVendorAdditionalData extends JsonSerializableType
{
    /**
     * @var ?string $web
     */
    #[JsonProperty('web')]
    public ?string $web;

    /**
     * @param array{
     *   web?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->web = $values['web'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
