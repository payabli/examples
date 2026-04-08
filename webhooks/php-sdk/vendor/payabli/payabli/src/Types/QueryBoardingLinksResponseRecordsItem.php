<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use DateTime;
use Payabli\Core\Types\Date;

class QueryBoardingLinksResponseRecordsItem extends JsonSerializableType
{
    /**
     * @var ?bool $acceptOauth
     */
    #[JsonProperty('AcceptOauth')]
    public ?bool $acceptOauth;

    /**
     * @var ?bool $acceptRegister
     */
    #[JsonProperty('AcceptRegister')]
    public ?bool $acceptRegister;

    /**
     * @var ?string $entryAttributes
     */
    #[JsonProperty('EntryAttributes')]
    public ?string $entryAttributes;

    /**
     * @var ?int $id The record ID.
     */
    #[JsonProperty('Id')]
    public ?int $id;

    /**
     * @var ?DateTime $lastUpdated
     */
    #[JsonProperty('LastUpdated'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $lastUpdated;

    /**
     * @var ?string $orgParentName
     */
    #[JsonProperty('OrgParentName')]
    public ?string $orgParentName;

    /**
     * @var ?string $referenceName
     */
    #[JsonProperty('ReferenceName')]
    public ?string $referenceName;

    /**
     * @var ?int $referenceTemplateId
     */
    #[JsonProperty('ReferenceTemplateId')]
    public ?int $referenceTemplateId;

    /**
     * @var ?string $templateCode
     */
    #[JsonProperty('TemplateCode')]
    public ?string $templateCode;

    /**
     * @var ?string $templateName
     */
    #[JsonProperty('TemplateName')]
    public ?string $templateName;

    /**
     * @param array{
     *   acceptOauth?: ?bool,
     *   acceptRegister?: ?bool,
     *   entryAttributes?: ?string,
     *   id?: ?int,
     *   lastUpdated?: ?DateTime,
     *   orgParentName?: ?string,
     *   referenceName?: ?string,
     *   referenceTemplateId?: ?int,
     *   templateCode?: ?string,
     *   templateName?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->acceptOauth = $values['acceptOauth'] ?? null;
        $this->acceptRegister = $values['acceptRegister'] ?? null;
        $this->entryAttributes = $values['entryAttributes'] ?? null;
        $this->id = $values['id'] ?? null;
        $this->lastUpdated = $values['lastUpdated'] ?? null;
        $this->orgParentName = $values['orgParentName'] ?? null;
        $this->referenceName = $values['referenceName'] ?? null;
        $this->referenceTemplateId = $values['referenceTemplateId'] ?? null;
        $this->templateCode = $values['templateCode'] ?? null;
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
