<?php

namespace App\DTO;

use App\Utils\Requests;
use Illuminate\Http\Request;

class OrderDTO
{
    private ?string $name;
    private ?string $phone;
    private ?string $address;

    private mixed $price;

    /**
     * @param string|null $name
     * @param string|null $phone
     * @param string|null $address
     * @param mixed $price
     */
    public function __construct(
        ?string $name,
        ?string $phone,
        ?string $address,
        mixed $price
    )
    {
        $this->name = $name;
        $this->phone = $phone;
        $this->address = $address;
        $this->price = $price;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function getPrice(): mixed
    {
        return $this->price;
    }



    public static function fromRequest(Request $request): static
    {
        return Requests::compact($request, static::class);
    }
}
