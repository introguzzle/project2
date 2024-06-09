<?php

namespace App\Mail\Telegram;

use App\Models\Order;
use App\Models\Status;

class OrderNotification extends Notification
{
    protected Order $order;

    public function __construct(Order $order)
    {
        $this->order   = $order;

        $this->content = $this->buildContent();
        $this->buttons = $this->buildButtons();
    }

    public function buildButtons(): array
    {
        $accept = Status::findByName(Status::CONFIRMED)->id;
        $reject = Status::findByName(Status::CANCELLED)->id;

        return [
            'inline_keyboard' => [
                [
                    ['text' => 'Принять', 'callback_data' => $this->order->id . 'split' . $accept]
                ],
                [
                    ['text' => 'Отклонить', 'callback_data' => $this->order->id . 'split' . $reject]
                ]
            ]
        ];
    }

    public function buildContent(): string
    {
        $profile = $this->order->profile;
        $status = $this->order->status;

        $eol = PHP_EOL;
        $orderDetails = route('admin.orders.order.index', ['id' => $this->order->id]);

        $productDetails = '';
        foreach ($this->order->products as $product) {
            $productDetails .= "<b>$product->name</b>";
            $productDetails .= "<b> - {$product->getOrderQuantity($this->order)} шт. $eol</b>";
        }

        return "<b>Заказ {$this->order->id}</b>{$eol}
<b>Имя:</b> {$profile->name}
<b>Телефон:</b> {$this->order->phone}
<b>Адрес:</b> {$this->order->address}
<b>Общая сумма:</b> {$this->order->totalAmount} ₽
<b>Количество товаров:</b> {$this->order->totalQuantity}
<b>Статус:</b> $status->name{$eol}
<b>Продукты:</b>$eol{$productDetails}
<b><a href=\"$orderDetails\">Перейти к заказу</a></b>";
    }
}
