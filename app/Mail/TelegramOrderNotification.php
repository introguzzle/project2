<?php

namespace App\Mail;

use App\Models\Order;
use Stringable;

class TelegramOrderNotification implements Stringable
{
    private string $content;

    /**
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $profile = $order->getRelatedProfile();
        $status = $order->getRelatedStatus();

        $eol = PHP_EOL;
        $orderDetails = route('admin.associated.order', ['id' => $order->getId()]);

        $this->content = "<b>Заказ {$order->getId()}</b>{$eol}
<b>Имя:</b> {$profile->getName()}
<b>Телефон:</b> {$order->getPhone()}
<b>Адрес:</b> {$order->getAddress()}
<b>Общая сумма:</b> {$order->getTotalAmount()} ₽
<b>Количество товаров:</b> {$order->getTotalQuantity()}
<b>Статус:</b> {$status->getName()}{$eol}
<b><a href=\"$orderDetails\">Перейти к заказу</a></b>";
    }

    public function __toString(): string
    {
        return $this->content;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
