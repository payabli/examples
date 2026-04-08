<?php

namespace Payabli\Paypoint\Requests;

use Payabli\Core\Json\JsonSerializableType;

class GetEntryConfigRequest extends JsonSerializableType
{
    /**
     * @var ?string $entrypages
     */
    public ?string $entrypages;

    /**
     * @param array{
     *   entrypages?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->entrypages = $values['entrypages'] ?? null;
    }
}
