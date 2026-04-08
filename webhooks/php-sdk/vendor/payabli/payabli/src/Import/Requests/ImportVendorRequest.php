<?php

namespace Payabli\Import\Requests;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Utils\File;

class ImportVendorRequest extends JsonSerializableType
{
    /**
     * @var File $file
     */
    public File $file;

    /**
     * @param array{
     *   file: File,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->file = $values['file'];
    }
}
