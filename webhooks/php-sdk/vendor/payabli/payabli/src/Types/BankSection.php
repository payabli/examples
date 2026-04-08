<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * Details about a bank account.
 */
class BankSection extends JsonSerializableType
{
    /**
     * @var ?bool $visible
     */
    #[JsonProperty('visible')]
    public ?bool $visible;

    /**
     * @var ?TemplateElement $accountNumber
     */
    #[JsonProperty('accountNumber')]
    public ?TemplateElement $accountNumber;

    /**
     * @var ?TemplateElement $accountType
     */
    #[JsonProperty('accountType')]
    public ?TemplateElement $accountType;

    /**
     * @var ?TemplateElement $bankName
     */
    #[JsonProperty('bankName')]
    public ?TemplateElement $bankName;

    /**
     * @var ?TemplateElement $routingNumber
     */
    #[JsonProperty('routingNumber')]
    public ?TemplateElement $routingNumber;

    /**
     * @param array{
     *   visible?: ?bool,
     *   accountNumber?: ?TemplateElement,
     *   accountType?: ?TemplateElement,
     *   bankName?: ?TemplateElement,
     *   routingNumber?: ?TemplateElement,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->visible = $values['visible'] ?? null;
        $this->accountNumber = $values['accountNumber'] ?? null;
        $this->accountType = $values['accountType'] ?? null;
        $this->bankName = $values['bankName'] ?? null;
        $this->routingNumber = $values['routingNumber'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
