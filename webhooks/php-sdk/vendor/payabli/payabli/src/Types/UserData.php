<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;
use Payabli\Core\Types\Union;

class UserData extends JsonSerializableType
{
    /**
     * @var ?array<UsrAccess> $access
     */
    #[JsonProperty('access'), ArrayType([UsrAccess::class])]
    public ?array $access;

    /**
     * @var ?array<string, ?array<string, mixed>> $additionalData
     */
    #[JsonProperty('additionalData'), ArrayType(['string' => new Union(['string' => 'mixed'], 'null')])]
    public ?array $additionalData;

    /**
     * @var ?string $email The user's email address.
     */
    #[JsonProperty('email')]
    public ?string $email;

    /**
     * @var ?string $language
     */
    #[JsonProperty('language')]
    public ?string $language;

    /**
     * @var ?MfaData $mfaData
     */
    #[JsonProperty('mfaData')]
    public ?MfaData $mfaData;

    /**
     * @var ?string $name
     */
    #[JsonProperty('name')]
    public ?string $name;

    /**
     * @var ?string $phone The user's phone number.
     */
    #[JsonProperty('phone')]
    public ?string $phone;

    /**
     * @var ?string $pwd
     */
    #[JsonProperty('pwd')]
    public ?string $pwd;

    /**
     * @var ?array<OrgScope> $scope
     */
    #[JsonProperty('scope'), ArrayType([OrgScope::class])]
    public ?array $scope;

    /**
     * @var ?int $timeZone
     */
    #[JsonProperty('timeZone')]
    public ?int $timeZone;

    /**
     * @var ?int $usrStatus
     */
    #[JsonProperty('usrStatus')]
    public ?int $usrStatus;

    /**
     * @param array{
     *   access?: ?array<UsrAccess>,
     *   additionalData?: ?array<string, ?array<string, mixed>>,
     *   email?: ?string,
     *   language?: ?string,
     *   mfaData?: ?MfaData,
     *   name?: ?string,
     *   phone?: ?string,
     *   pwd?: ?string,
     *   scope?: ?array<OrgScope>,
     *   timeZone?: ?int,
     *   usrStatus?: ?int,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->access = $values['access'] ?? null;
        $this->additionalData = $values['additionalData'] ?? null;
        $this->email = $values['email'] ?? null;
        $this->language = $values['language'] ?? null;
        $this->mfaData = $values['mfaData'] ?? null;
        $this->name = $values['name'] ?? null;
        $this->phone = $values['phone'] ?? null;
        $this->pwd = $values['pwd'] ?? null;
        $this->scope = $values['scope'] ?? null;
        $this->timeZone = $values['timeZone'] ?? null;
        $this->usrStatus = $values['usrStatus'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
