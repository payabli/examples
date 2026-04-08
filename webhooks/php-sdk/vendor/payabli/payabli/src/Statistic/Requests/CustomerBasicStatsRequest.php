<?php

namespace Payabli\Statistic\Requests;

use Payabli\Core\Json\JsonSerializableType;

class CustomerBasicStatsRequest extends JsonSerializableType
{
    /**
     * @var ?array<string, ?string> $parameters List of parameters.
     */
    public ?array $parameters;

    /**
     * @param array{
     *   parameters?: ?array<string, ?string>,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->parameters = $values['parameters'] ?? null;
    }
}
