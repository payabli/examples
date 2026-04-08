<?php

namespace Payabli\CheckCapture\Requests;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class CheckCaptureRequestBody extends JsonSerializableType
{
    /**
     * @var string $entryPoint
     */
    #[JsonProperty('entryPoint')]
    public string $entryPoint;

    /**
     * @var string $frontImage Base64-encoded front check image. Must be JPEG or PNG format and less than 1MB. Image must show the entire check clearly with no partial, blurry, or illegible portions.
     */
    #[JsonProperty('frontImage')]
    public string $frontImage;

    /**
     * @var string $rearImage Base64-encoded rear check image. Must be JPEG or PNG format and less than 1MB. Image must show the entire check clearly with no partial, blurry, or illegible portions.
     */
    #[JsonProperty('rearImage')]
    public string $rearImage;

    /**
     * @var int $checkAmount Check amount in cents (maximum 32-bit integer value).
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
}
