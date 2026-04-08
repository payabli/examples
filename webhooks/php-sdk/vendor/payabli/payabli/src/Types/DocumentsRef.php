<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

class DocumentsRef extends JsonSerializableType
{
    /**
     * @var ?array<PairFiles> $filelist Array of objects describing files contained in the ZIP file.
     */
    #[JsonProperty('filelist'), ArrayType([PairFiles::class])]
    public ?array $filelist;

    /**
     * @var ?string $zipfile Zip file containing attachments.
     */
    #[JsonProperty('zipfile')]
    public ?string $zipfile;

    /**
     * @param array{
     *   filelist?: ?array<PairFiles>,
     *   zipfile?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->filelist = $values['filelist'] ?? null;
        $this->zipfile = $values['zipfile'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
