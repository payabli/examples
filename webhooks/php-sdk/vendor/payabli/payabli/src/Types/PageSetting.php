<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class PageSetting extends JsonSerializableType
{
    /**
     * @var ?string $color An HTML color code in format #RRGGBB
     */
    #[JsonProperty('color')]
    public ?string $color;

    /**
     * @var ?string $customCssUrl Complete URL to a custom CSS file to be loaded with the page
     */
    #[JsonProperty('customCssUrl')]
    public ?string $customCssUrl;

    /**
     * @var ?string $language Two-letter code following ISO 639-1
     */
    #[JsonProperty('language')]
    public ?string $language;

    /**
     * @var ?FileContent $pageLogo Object containing logo file to upload/ use in page
     */
    #[JsonProperty('pageLogo')]
    public ?FileContent $pageLogo;

    /**
     * @var ?ButtonElement $paymentButton
     */
    #[JsonProperty('paymentButton')]
    public ?ButtonElement $paymentButton;

    /**
     * @var ?bool $redirectAfterApprove Flag indicating if the capability for redirection in the page will be activated
     */
    #[JsonProperty('redirectAfterApprove')]
    public ?bool $redirectAfterApprove;

    /**
     * @var ?string $redirectAfterApproveUrl Complete URL where the page will be redirected after completion
     */
    #[JsonProperty('redirectAfterApproveUrl')]
    public ?string $redirectAfterApproveUrl;

    /**
     * @param array{
     *   color?: ?string,
     *   customCssUrl?: ?string,
     *   language?: ?string,
     *   pageLogo?: ?FileContent,
     *   paymentButton?: ?ButtonElement,
     *   redirectAfterApprove?: ?bool,
     *   redirectAfterApproveUrl?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->color = $values['color'] ?? null;
        $this->customCssUrl = $values['customCssUrl'] ?? null;
        $this->language = $values['language'] ?? null;
        $this->pageLogo = $values['pageLogo'] ?? null;
        $this->paymentButton = $values['paymentButton'] ?? null;
        $this->redirectAfterApprove = $values['redirectAfterApprove'] ?? null;
        $this->redirectAfterApproveUrl = $values['redirectAfterApproveUrl'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
