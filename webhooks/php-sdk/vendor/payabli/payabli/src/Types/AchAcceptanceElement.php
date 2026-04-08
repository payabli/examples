<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class AchAcceptanceElement extends JsonSerializableType
{
    /**
     * @var ?AchTypes $types
     */
    #[JsonProperty('types')]
    public ?AchTypes $types;

    /**
     * @var ?int $posCol
     */
    #[JsonProperty('posCol')]
    public ?int $posCol;

    /**
     * @var ?int $posRow
     */
    #[JsonProperty('posRow')]
    public ?int $posRow;

    /**
     * @var ?bool $readOnly
     */
    #[JsonProperty('readOnly')]
    public ?bool $readOnly;

    /**
     * @var ?string $value
     */
    #[JsonProperty('value')]
    public ?string $value;

    /**
     * @var ?bool $visible
     */
    #[JsonProperty('visible')]
    public ?bool $visible;

    /**
     * @param array{
     *   types?: ?AchTypes,
     *   posCol?: ?int,
     *   posRow?: ?int,
     *   readOnly?: ?bool,
     *   value?: ?string,
     *   visible?: ?bool,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->types = $values['types'] ?? null;
        $this->posCol = $values['posCol'] ?? null;
        $this->posRow = $values['posRow'] ?? null;
        $this->readOnly = $values['readOnly'] ?? null;
        $this->value = $values['value'] ?? null;
        $this->visible = $values['visible'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
