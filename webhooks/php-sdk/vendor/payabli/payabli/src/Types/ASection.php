<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class ASection extends JsonSerializableType
{
    /**
     * @var ?int $minimumDocuments
     */
    #[JsonProperty('minimumDocuments')]
    public ?int $minimumDocuments;

    /**
     * @var ?bool $multipleContacts
     */
    #[JsonProperty('multipleContacts')]
    public ?bool $multipleContacts;

    /**
     * @var ?bool $multipleOwners
     */
    #[JsonProperty('multipleOwners')]
    public ?bool $multipleOwners;

    /**
     * @param array{
     *   minimumDocuments?: ?int,
     *   multipleContacts?: ?bool,
     *   multipleOwners?: ?bool,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->minimumDocuments = $values['minimumDocuments'] ?? null;
        $this->multipleContacts = $values['multipleContacts'] ?? null;
        $this->multipleOwners = $values['multipleOwners'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
