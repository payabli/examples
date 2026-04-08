<?php

namespace Payabli\CheckCapture\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * Request model for check capture processing.
 */
class CheckCaptureRequest extends JsonSerializableType
{
    /**
     * @var string $entryPoint
     */
    #[JsonProperty('entryPoint')]
    public string $entryPoint;

    /**
     * @var string $frontImage Base64-encoded image of the front of the check. Must be JPEG or PNG format and less than 1MB. Image must show the entire check clearly with no partial, blurry, or illegible portions.
     */
    #[JsonProperty('frontImage')]
    public string $frontImage;

    /**
     * @var string $rearImage Base64-encoded image of the back of the check. Must be JPEG or PNG format and less than 1MB. Image must show the entire check clearly with no partial, blurry, or illegible portions.
     */
    #[JsonProperty('rearImage')]
    public string $rearImage;

    /**
     * @var int $checkAmount Check amount in cents (maximum 32-bit integer value). For example, $125.50 is represented as 12550.
     */
    #[JsonProperty('checkAmount')]
    public int $checkAmount;

    /**
     * @param array{
     *   entryPoint: string,
     *   frontImage: string,
     *   rearImage: string,
     *   checkAmount: int,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->entryPoint = $values['entryPoint'];
        $this->frontImage = $values['frontImage'];
        $this->rearImage = $values['rearImage'];
        $this->checkAmount = $values['checkAmount'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
