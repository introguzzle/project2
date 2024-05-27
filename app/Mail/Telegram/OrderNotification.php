<?php

namespace App\Mail\Telegram;

use App\Models\Order;
use App\Models\Status;

class OrderNotification extends Notification
{
    protected Order $order;
    protected int $id;

    public function __construct(Order $order)
    {
        $this->order   = $order;
        $this->id      = $order->getId();

        $this->content = $this->buildContent();
        $this->buttons = $this->buildButtons();
    }

    public function buildButtons(): array
    {
        $accept = Status::findByName(Status::CONFIRMED)->getId();
        $reject = Status::findByName(Status::CANCELLED)->getId();

        return [
            'inline_keyboard' => [
                [
                    ['text' => 'Принять', 'callback_data' => $this->id . 'split' . $accept]
                ],
                [
                    ['text' => 'Отклонить', 'callback_data' => $this->id . 'split' . $reject]
                ]
            ]
        ];
    }

    public function buildContent(): string
    {
        $profile = $this->order->profile;
        $status = $this->order->status;

        $eol = PHP_EOL;
        $orderDetails = route('admin.associated.order', ['id' => $this->order->getId()]);

        $productDetails = '';
        foreach ($this->order->products as $product) {
            $productDetails .= "<b>{$product->getName()}</b>";
            $productDetails .= "<b> - {$product->getOrderQuantity($this->order)} шт. {$eol}</b>";
        }

        return "<b>Заказ {$this->order->getId()}</b>{$eol}
<b>Имя:</b> {$profile->getName()}
<b>Телефон:</b> {$this->order->phone}
<b>Адрес:</b> {$this->order->getAddress()}
<b>Общая сумма:</b> {$this->order->getTotalAmount()} ₽
<b>Количество товаров:</b> {$this->order->getTotalQuantity()}
<b>Статус:</b> {$status->getName()}{$eol}
<b>Продукты:</b>{$eol}{$productDetails}
<b><a href=\"$orderDetails\">Перейти к заказу</a></b>";
    }
}
