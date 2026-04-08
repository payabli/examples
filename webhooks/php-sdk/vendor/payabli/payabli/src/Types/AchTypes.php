<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class AchTypes extends JsonSerializableType
{
    /**
     * @var ?BasicTemplateElement $ccd
     */
    #[JsonProperty('ccd')]
    public ?BasicTemplateElement $ccd;

    /**
     * @var ?BasicTemplateElement $ppd
     */
    #[JsonProperty('ppd')]
    public ?BasicTemplateElement $ppd;

    /**
     * @var ?BasicTemplateElement $web
     */
    #[JsonProperty('web')]
    public ?BasicTemplateElement $web;

    /**
     * @param array{
     *   ccd?: ?BasicTemplateElement,
     *   ppd?: ?BasicTemplateElement,
     *   web?: ?BasicTemplateElement,
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
