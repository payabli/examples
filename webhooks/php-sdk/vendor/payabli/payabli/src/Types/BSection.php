<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class BSection extends JsonSerializableType
{
    /**
     * @var ?BAddress $address
     */
    #[JsonProperty('address')]
    public ?BAddress $address;

    /**
     * @var ?BDetails $details
     */
    #[JsonProperty('details')]
    public ?BDetails $details;

    /**
     * @param array{
     *   address?: ?BAddress,
     *   details?: ?BDetails,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->address = $values['address'] ?? null;
        $this->details = $values['details'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
