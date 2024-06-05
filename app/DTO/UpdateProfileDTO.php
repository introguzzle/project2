<?php

namespace App\DTO;

use Carbon\CarbonInterface;

class UpdateProfileDTO
{
    use FromRequest;

    public readonly ?string $name;
    public readonly ?CarbonInterface $birthday;
    public readonly ?string $address;

    /**
     * @param string|null $name
     * @param CarbonInterface|null $birthday
     * @param string|null $address
     */
    public function __construct(
        ?string $name,
        ?CarbonInterface $birthday,
        ?string $address
    )
    {
        $this->name = $name;
        $this->birthday = $birthday;
        $this->address = $address;
    }
}
