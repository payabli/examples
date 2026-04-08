<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class PairFiles extends JsonSerializableType
{
    /**
     * @var ?string $originalName Original filename
     */
    #[JsonProperty('originalName')]
    public ?string $originalName;

    /**
     * @var ?string $zipName Filename assigned to zipped file. This is the name to use for reference in the API functions to get files in attachments.
     */
    #[JsonProperty('zipName')]
    public ?string $zipName;

    /**
     * @var ?string $descriptor Descriptor of the file.
     */
    #[JsonProperty('descriptor')]
    public ?string $descriptor;

    /**
     * @param array{
     *   originalName?: ?string,
     *   zipName?: ?string,
     *   descriptor?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->originalName = $values['originalName'] ?? null;
        $this->zipName = $values['zipName'] ?? null;
        $this->descriptor = $values['descriptor'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
