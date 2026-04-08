<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class MethodElement extends JsonSerializableType
{
    /**
     * @var ?bool $allMethodsChecked Flag indicating if all allowed payment methods will be pre-selected.
     */
    #[JsonProperty('allMethodsChecked')]
    public ?bool $allMethodsChecked;

    /**
     * @var ?bool $enabled
     */
    #[JsonProperty('enabled')]
    public ?bool $enabled;

    /**
     * @var ?string $header Header text for section
     */
    #[JsonProperty('header')]
    public ?string $header;

    /**
     * @var ?MethodsList $methods
     */
    #[JsonProperty('methods')]
    public ?MethodsList $methods;

    /**
     * @var ?int $order
     */
    #[JsonProperty('order')]
    public ?int $order;

    /**
     * @var ?MethodElementSettings $settings Settings for wallet payment methods.
     */
    #[JsonProperty('settings')]
    public ?MethodElementSettings $settings;

    /**
     * @param array{
     *   allMethodsChecked?: ?bool,
     *   enabled?: ?bool,
     *   header?: ?string,
     *   methods?: ?MethodsList,
     *   order?: ?int,
     *   settings?: ?MethodElementSettings,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->allMethodsChecked = $values['allMethodsChecked'] ?? null;
        $this->enabled = $values['enabled'] ?? null;
        $this->header = $values['header'] ?? null;
        $this->methods = $values['methods'] ?? null;
        $this->order = $values['order'] ?? null;
        $this->settings = $values['settings'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
