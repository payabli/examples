<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class AchLinkTypes extends JsonSerializableType
{
    /**
     * @var ?LinkData $ccd
     */
    #[JsonProperty('ccd')]
    public ?LinkData $ccd;

    /**
     * @var ?LinkData $ppd
     */
    #[JsonProperty('ppd')]
    public ?LinkData $ppd;

    /**
     * @var ?LinkData $web
     */
    #[JsonProperty('web')]
    public ?LinkData $web;

    /**
     * @param array{
     *   ccd?: ?LinkData,
     *   ppd?: ?LinkData,
     *   web?: ?LinkData,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->ccd = $values['ccd'] ?? null;
        $this->ppd = $values['ppd'] ?? null;
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
