<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

class DocumentSectionTermsAndConditions extends JsonSerializableType
{
    /**
     * @var ?array<DocumentSectionTermsAndConditionsTcLinksItem> $tcLinks
     */
    #[JsonProperty('tcLinks'), ArrayType([DocumentSectionTermsAndConditionsTcLinksItem::class])]
    public ?array $tcLinks;

    /**
     * @var ?bool $visible
     */
    #[JsonProperty('visible')]
    public ?bool $visible;

    /**
     * @param array{
     *   tcLinks?: ?array<DocumentSectionTermsAndConditionsTcLinksItem>,
     *   visible?: ?bool,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->tcLinks = $values['tcLinks'] ?? null;
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
