<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use DateTime;
use Payabli\Core\Types\Date;

class BillQueryRecord2BillApprovalsItem extends JsonSerializableType
{
    /**
     * @var ?int $approved Indicates whether the bill has been approved. `0` is false, and `1` is true.
     */
    #[JsonProperty('approved')]
    public ?int $approved;

    /**
     * @var ?DateTime $approvedTime Timestamp of when the approval was made, in UTC.
     */
    #[JsonProperty('approvedTime'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $approvedTime;

    /**
     * @var ?string $comments Additional comments on the approval.
     */
    #[JsonProperty('comments')]
    public ?string $comments;

    /**
     * @var ?string $email The approving user's email address.
     */
    #[JsonProperty('email')]
    public ?string $email;

    /**
     * @var ?int $id The approving user's ID.
     */
    #[JsonProperty('Id')]
    public ?int $id;

    /**
     * @param array{
     *   approved?: ?int,
     *   approvedTime?: ?DateTime,
     *   comments?: ?string,
     *   email?: ?string,
     *   id?: ?int,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->approved = $values['approved'] ?? null;
        $this->approvedTime = $values['approvedTime'] ?? null;
        $this->comments = $values['comments'] ?? null;
        $this->email = $values['email'] ?? null;
        $this->id = $values['id'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
