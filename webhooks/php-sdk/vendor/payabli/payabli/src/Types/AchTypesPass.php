<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class AchTypesPass extends JsonSerializableType
{
    /**
     * @var ?TierItemPass $ccd
     */
    #[JsonProperty('ccd')]
    public ?TierItemPass $ccd;

    /**
     * @var ?TierItemPass $ppd
     */
    #[JsonProperty('ppd')]
    public ?TierItemPass $ppd;

    /**
     * @var ?TierItemPass $web
     */
    #[JsonProperty('web')]
    public ?TierItemPass $web;

    /**
     * @param array{
     *   ccd?: ?TierItemPass,
     *   ppd?: ?TierItemPass,
     *   web?: ?TierItemPass,
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
