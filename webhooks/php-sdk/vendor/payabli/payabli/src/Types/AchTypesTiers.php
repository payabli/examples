<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class AchTypesTiers extends JsonSerializableType
{
    /**
     * @var ?TierItem $ccd
     */
    #[JsonProperty('ccd')]
    public ?TierItem $ccd;

    /**
     * @var ?TierItem $ppd
     */
    #[JsonProperty('ppd')]
    public ?TierItem $ppd;

    /**
     * @var ?TierItem $web
     */
    #[JsonProperty('web')]
    public ?TierItem $web;

    /**
     * @param array{
     *   ccd?: ?TierItem,
     *   ppd?: ?TierItem,
     *   web?: ?TierItem,
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
