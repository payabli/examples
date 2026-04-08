<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * This metadata appears only when the domain verification check fails. It gives more information about why the check failed.
 */
class ApplePayMetadata extends JsonSerializableType
{
    /**
     * @var ?bool $isFileAvailable When `true`, indicates whether the domain verification file is available at the expected path. When `false`, Payabli was unable to find the file at the expected path. If the file is missing, make sure it's hosted at the correct path: `/.well-known/apple-developer-merchantid-domain-association`
     */
    #[JsonProperty('isFileAvailable')]
    public ?bool $isFileAvailable;

    /**
     * @var ?bool $isFileContentValid Indicates whether the domain verification file content is valid. If the file is invalid, try downloading it and hosting it again.
     */
    #[JsonProperty('isFileContentValid')]
    public ?bool $isFileContentValid;

    /**
     * @var ?string $redirectDomainName The domain name if the domain verification URL returns a redirect.
     */
    #[JsonProperty('redirectDomainName')]
    public ?string $redirectDomainName;

    /**
     * If the domain verification URL is redirected, this is the URL it's redirected to.
     * For example, www.partner.com could redirect to www.partners-new-home-page.com. In this case, you should add www.partners-new-home-page.com as a domain instead of www.partner.com.
     *
     * @var ?string $redirectUrl
     */
    #[JsonProperty('redirectUrl')]
    public ?string $redirectUrl;

    /**
     * @var ?int $statusCode The status code return by the domain verification URL.
     */
    #[JsonProperty('statusCode')]
    public ?int $statusCode;

    /**
     * @param array{
     *   isFileAvailable?: ?bool,
     *   isFileContentValid?: ?bool,
     *   redirectDomainName?: ?string,
     *   redirectUrl?: ?string,
     *   statusCode?: ?int,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->isFileAvailable = $values['isFileAvailable'] ?? null;
        $this->isFileContentValid = $values['isFileContentValid'] ?? null;
        $this->redirectDomainName = $values['redirectDomainName'] ?? null;
        $this->redirectUrl = $values['redirectUrl'] ?? null;
        $this->statusCode = $values['statusCode'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
