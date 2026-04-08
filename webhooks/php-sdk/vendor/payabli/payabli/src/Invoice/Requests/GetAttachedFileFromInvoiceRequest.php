<?php

namespace Payabli\Invoice\Requests;

use Payabli\Core\Json\JsonSerializableType;

class GetAttachedFileFromInvoiceRequest extends JsonSerializableType
{
    /**
     * @var ?bool $returnObject When `true`, the request returns the file content as a Base64-encoded string.
     */
    public ?bool $returnObject;

    /**
     * @param array{
     *   returnObject?: ?bool,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->returnObject = $values['returnObject'] ?? null;
    }
}
