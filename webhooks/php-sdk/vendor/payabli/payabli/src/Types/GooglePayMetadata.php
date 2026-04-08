<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * This metadata appears only when the domain verification check fails. It gives more information about why the check failed.
 */
class GooglePayMetadata extends JsonSerializableType
{
    /**
     * @var ?int $statusCode The status code return by the domain verification URL.
     */
    #[JsonProperty('statusCode')]
    public ?int $statusCode;

    /**
     * @var ?string $redirectUrl If the domain verification URL is redirected, this is the URL it's redirected to.  For example, www.partner.com could redirect to www.partners-new-home-page.com. In this case, you should add www.partners-new-home-page.com as a domain instead of www.partner.com.
     */
    #[JsonProperty('redirectUrl')]
    public ?string $redirectUrl;

    /**
     * @var ?string $redirectDomainName The domain name if the domain verification URL returns a redirect.
     */
    #[JsonProperty('redirectDomainName')]
    public ?string $redirectDomainName;

    /**
     * @param array{
     *   statusCode?: ?int,
     *   redirectUrl?: ?string,
     *   redirectDomainName?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->statusCode = $values['statusCode'] ?? null;
        $this->redirectUrl = $values['redirectUrl'] ?? null;
        $this->redirectDomainName = $values['redirectDomainName'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
