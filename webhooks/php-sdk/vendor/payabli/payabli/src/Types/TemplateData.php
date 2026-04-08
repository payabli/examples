<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * Object containing the template's data.
 */
class TemplateData extends JsonSerializableType
{
    /**
     * @var ?int $orgId The ID of the organization the template belongs to.
     */
    #[JsonProperty('orgId')]
    public ?int $orgId;

    /**
     * @var ?int $pricingId
     */
    #[JsonProperty('pricingId')]
    public ?int $pricingId;

    /**
     * @var ?string $templateCode
     */
    #[JsonProperty('templateCode')]
    public ?string $templateCode;

    /**
     * @var ?TemplateContent $templateContent
     */
    #[JsonProperty('templateContent')]
    public ?TemplateContent $templateContent;

    /**
     * @var ?string $templateDescription A description for the template.
     */
    #[JsonProperty('templateDescription')]
    public ?string $templateDescription;

    /**
     * @var ?string $templateName
     */
    #[JsonProperty('templateName')]
    public ?string $templateName;

    /**
     * @param array{
     *   orgId?: ?int,
     *   pricingId?: ?int,
     *   templateCode?: ?string,
     *   templateContent?: ?TemplateContent,
     *   templateDescription?: ?string,
     *   templateName?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->orgId = $values['orgId'] ?? null;
        $this->pricingId = $values['pricingId'] ?? null;
        $this->templateCode = $values['templateCode'] ?? null;
        $this->templateContent = $values['templateContent'] ?? null;
        $this->templateDescription = $values['templateDescription'] ?? null;
        $this->templateName = $values['templateName'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
