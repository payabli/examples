<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class ContactElement extends JsonSerializableType
{
    /**
     * @var ?string $emailLabel Custom content for email
     */
    #[JsonProperty('emailLabel')]
    public ?string $emailLabel;

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
     * @var ?int $order
     */
    #[JsonProperty('order')]
    public ?int $order;

    /**
     * @var ?bool $paymentIcons Flag indicating if icons for accepted card brands will be shown
     */
    #[JsonProperty('paymentIcons')]
    public ?bool $paymentIcons;

    /**
     * @var ?string $phoneLabel Custom content for phone number
     */
    #[JsonProperty('phoneLabel')]
    public ?string $phoneLabel;

    /**
     * @param array{
     *   emailLabel?: ?string,
     *   enabled?: ?bool,
     *   header?: ?string,
     *   order?: ?int,
     *   paymentIcons?: ?bool,
     *   phoneLabel?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->emailLabel = $values['emailLabel'] ?? null;
        $this->enabled = $values['enabled'] ?? null;
        $this->header = $values['header'] ?? null;
        $this->order = $values['order'] ?? null;
        $this->paymentIcons = $values['paymentIcons'] ?? null;
        $this->phoneLabel = $values['phoneLabel'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
