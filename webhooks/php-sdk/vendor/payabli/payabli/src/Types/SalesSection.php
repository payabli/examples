<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class SalesSection extends JsonSerializableType
{
    /**
     * @var ?string $salesCode
     */
    #[JsonProperty('salesCode')]
    public ?string $salesCode;

    /**
     * @var ?string $salesCrm
     */
    #[JsonProperty('salesCRM')]
    public ?string $salesCrm;

    /**
     * @param array{
     *   salesCode?: ?string,
     *   salesCrm?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->salesCode = $values['salesCode'] ?? null;
        $this->salesCrm = $values['salesCrm'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
