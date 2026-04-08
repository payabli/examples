<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * Contains details about a file. Max upload size is 30 MB.
 */
class FileContent extends JsonSerializableType
{
    /**
     * @var ?string $fContent Content of file, Base64-encoded. Ignored if furl is specified. Max upload size is 30 MB.
     */
    #[JsonProperty('fContent')]
    public ?string $fContent;

    /**
     * @var ?string $filename The name of the attached file.
     */
    #[JsonProperty('filename')]
    public ?string $filename;

    /**
     * @var ?value-of<FileContentFtype> $ftype The MIME type of the file (if content is provided)
     */
    #[JsonProperty('ftype')]
    public ?string $ftype;

    /**
     * @var ?string $furl Optional URL provided to show or download the file remotely
     */
    #[JsonProperty('furl')]
    public ?string $furl;

    /**
     * @param array{
     *   fContent?: ?string,
     *   filename?: ?string,
     *   ftype?: ?value-of<FileContentFtype>,
     *   furl?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->fContent = $values['fContent'] ?? null;
        $this->filename = $values['filename'] ?? null;
        $this->ftype = $values['ftype'] ?? null;
        $this->furl = $values['furl'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
