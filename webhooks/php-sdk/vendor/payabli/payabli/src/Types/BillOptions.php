<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class BillOptions extends JsonSerializableType
{
    /**
     * @var ?bool $includePaylink Flag to indicate if the scheduled invoice includes a payment link.
     */
    #[JsonProperty('includePaylink')]
    public ?bool $includePaylink;

    /**
     * @var ?bool $includePdf Flag to indicate if the scheduled invoice includes a PDF version of invoice
     */
    #[JsonProperty('includePdf')]
    public ?bool $includePdf;

    /**
     * @param array{
     *   includePaylink?: ?bool,
     *   includePdf?: ?bool,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->includePaylink = $values['includePaylink'] ?? null;
        $this->includePdf = $values['includePdf'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
