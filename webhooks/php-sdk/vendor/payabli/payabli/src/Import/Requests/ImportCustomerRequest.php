<?php

namespace Payabli\Import\Requests;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Utils\File;

class ImportCustomerRequest extends JsonSerializableType
{
    /**
     * @var ?int $replaceExisting Flag indicating to replace existing customer with a new record. Possible values: 0 (do not replace), 1 (replace). Default is 0
     */
    public ?int $replaceExisting;

    /**
     * @var File $file
     */
    public File $file;

    /**
     * @param array{
     *   file: File,
     *   replaceExisting?: ?int,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->replaceExisting = $values['replaceExisting'] ?? null;
        $this->file = $values['file'];
    }
}
