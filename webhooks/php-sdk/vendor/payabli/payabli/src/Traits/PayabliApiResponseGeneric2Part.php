<?php

namespace Payabli\Traits;

use Payabli\Core\Json\JsonProperty;

/**
 * @property ?bool $isSuccess
 * @property string $responseText
 */
trait PayabliApiResponseGeneric2Part
{
    /**
     * @var ?bool $isSuccess
     */
    #[JsonProperty('isSuccess')]
    public ?bool $isSuccess;

    /**
     * @var string $responseText
     */
    #[JsonProperty('responseText')]
    public string $responseText;
}
