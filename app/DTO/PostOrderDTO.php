<?php

namespace App\DTO;

use App\Other\Requests;
use Illuminate\Http\Request;

class PostOrderDTO
{
    use FromRequest;

    public readonly ?string $name;
    public readonly ?string $phone;
    public readonly ?string $address;

    public readonly int $price;

    public readonly int $receiptMethodId;
    public readonly int $paymentMethodId;

    /**
     * @param string|null $name
     * @param string|null $phone
     * @param string|null $address
     * @param int $price
     * @param int $receiptMethodId
     * @param int $paymentMethodId
     */
    public function __construct(
        ?string $name,
        ?string $phone,
        ?string $address,
        int $price,
        int $receiptMethodId,
        int $paymentMethodId
    )
    {
        $this->name = $name;
        $this->phone = $phone;
        $this->address = $address;
        $this->price = $price;
        $this->receiptMethodId = $receiptMethodId;
        $this->paymentMethodId = $paymentMethodId;
    }


}
