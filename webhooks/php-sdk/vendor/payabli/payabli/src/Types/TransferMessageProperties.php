<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class TransferMessageProperties extends JsonSerializableType
{
    /**
     * @var ?string $originalTransferStatus
     */
    #[JsonProperty('originalTransferStatus')]
    public ?string $originalTransferStatus;

    /**
     * @var ?string $currentTransferStatus
     */
    #[JsonProperty('currentTransferStatus')]
    public ?string $currentTransferStatus;

    /**
     * @param array{
     *   originalTransferStatus?: ?string,
     *   currentTransferStatus?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->originalTransferStatus = $values['originalTransferStatus'] ?? null;
        $this->currentTransferStatus = $values['currentTransferStatus'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
