<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;
use DateTime;
use Payabli\Core\Types\Date;

class UserQueryRecord extends JsonSerializableType
{
    /**
     * @var ?array<UsrAccess> $access
     */
    #[JsonProperty('Access'), ArrayType([UsrAccess::class])]
    public ?array $access;

    /**
     * @var ?string $additionalData
     */
    #[JsonProperty('AdditionalData')]
    public ?string $additionalData;

    /**
     * @var ?DateTime $createdAt The timestamp for the user's creation, in UTC.
     */
    #[JsonProperty('createdAt'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $createdAt;

    /**
     * @var ?string $email The user's email address.
     */
    #[JsonProperty('Email')]
    public ?string $email;

    /**
     * @var ?string $language
     */
    #[JsonProperty('language')]
    public ?string $language;

    /**
     * @var ?DateTime $lastAccess The timestamp for the user's last activity, in UTC.
     */
    #[JsonProperty('lastAccess'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $lastAccess;

    /**
     * @var ?string $name
     */
    #[JsonProperty('Name')]
    public ?string $name;

    /**
     * @var ?string $phone The user's phone number.
     */
    #[JsonProperty('Phone')]
    public ?string $phone;

    /**
     * @var ?array<OrgXScope> $scope
     */
    #[JsonProperty('Scope'), ArrayType([OrgXScope::class])]
    public ?array $scope;

    /**
     * @var ?string $snData Additional data provided by the social network related to the customer.
     */
    #[JsonProperty('snData')]
    public ?string $snData;

    /**
     * @var ?string $snIdentifier Identifier or token for customer in linked social network.
     */
    #[JsonProperty('snIdentifier')]
    public ?string $snIdentifier;

    /**
     * @var ?string $snProvider Social network linked to customer. Possible values: facebook, google, twitter, microsoft.
     */
    #[JsonProperty('snProvider')]
    public ?string $snProvider;

    /**
     * @var ?int $timeZone
     */
    #[JsonProperty('timeZone')]
    public ?int $timeZone;

    /**
     * @var ?int $userId The user's ID in Payabli.
     */
    #[JsonProperty('userId')]
    public ?int $userId;

    /**
     * @var ?bool $usrMfa
     */
    #[JsonProperty('UsrMFA')]
    public ?bool $usrMfa;

    /**
     * @var ?int $usrMfaMode
     */
    #[JsonProperty('UsrMFAMode')]
    public ?int $usrMfaMode;

    /**
     * @var ?int $usrStatus
     */
    #[JsonProperty('UsrStatus')]
    public ?int $usrStatus;

    /**
     * @param array{
     *   access?: ?array<UsrAccess>,
     *   additionalData?: ?string,
     *   createdAt?: ?DateTime,
     *   email?: ?string,
     *   language?: ?string,
     *   lastAccess?: ?DateTime,
     *   name?: ?string,
     *   phone?: ?string,
     *   scope?: ?array<OrgXScope>,
     *   snData?: ?string,
     *   snIdentifier?: ?string,
     *   snProvider?: ?string,
     *   timeZone?: ?int,
     *   userId?: ?int,
     *   usrMfa?: ?bool,
     *   usrMfaMode?: ?int,
     *   usrStatus?: ?int,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->access = $values['access'] ?? null;
        $this->additionalData = $values['additionalData'] ?? null;
        $this->createdAt = $values['createdAt'] ?? null;
        $this->email = $values['email'] ?? null;
        $this->language = $values['language'] ?? null;
        $this->lastAccess = $values['lastAccess'] ?? null;
        $this->name = $values['name'] ?? null;
        $this->phone = $values['phone'] ?? null;
        $this->scope = $values['scope'] ?? null;
        $this->snData = $values['snData'] ?? null;
        $this->snIdentifier = $values['snIdentifier'] ?? null;
        $this->snProvider = $values['snProvider'] ?? null;
        $this->timeZone = $values['timeZone'] ?? null;
        $this->userId = $values['userId'] ?? null;
        $this->usrMfa = $values['usrMfa'] ?? null;
        $this->usrMfaMode = $values['usrMfaMode'] ?? null;
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
