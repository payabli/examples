<?php

namespace Payabli\Cloud\Requests;

use Payabli\Core\Json\JsonSerializableType;

class ListDeviceRequest extends JsonSerializableType
{
    /**
     * @var ?bool $forceRefresh When `true`, the request retrieves an updated list of devices from the processor instead of returning a cached list of devices.
     */
    public ?bool $forceRefresh;

    /**
     * @param array{
     *   forceRefresh?: ?bool,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->forceRefresh = $values['forceRefresh'] ?? null;
    }
}
