<?php

namespace App\DTO;

class UpdateProfileDTO
{
    use FromRequest;

    private ?string $name;
    private ?string $birthday;
    private ?string $address;

    /**
     * @param string|null $name
     * @param string|null $birthday
     * @param string|null $address
     */
    public function __construct(?string $name, ?string $birthday, ?string $address)
    {
        $this->name = $name;
        $this->birthday = $birthday;
        $this->address = $address;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getBirthday(): ?string
    {
        return $this->birthday;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }


}
