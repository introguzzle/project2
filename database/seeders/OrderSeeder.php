<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ReceiptMethod;
use App\Models\Status;
use App\Models\User\Profile;
use Illuminate\Database\Seeder;
use Faker\Factory;
use Random\RandomException;

class OrderSeeder extends Seeder
{
    /**
     * @throws RandomException
     */
    public function run(): void
    {
        $faker = Factory::create('ru_RU');

        $profiles = Profile::all();
        $statuses = Status::all();
        $receiptMethods = ReceiptMethod::all();
        $paymentMethods = PaymentMethod::all();
        $products = Product::all();

        for ($i = 0; $i < 500; $i++) {
            foreach ($profiles as $profile) {
                $order = new Order();

                $order->profile()->associate($profile);
                $order->status()->associate($statuses->random());
                $order->receiptMethod()->associate($receiptMethods->random());
                $order->paymentMethod()->associate($paymentMethods->random());

                $order->name = $profile->name ?? 'name';
                $order->phone = $profile->phone ?? 'phone';
                $order->address = $profile->address ?? 'address';
                $order->description = $faker->realText();

                $order->totalQuantity = 0;
                $order->totalAmount = 0;

                $order->createdAt = now()->subDays($i)->addDays(random_int(0, 30));
                $order->updatedAt = $order->createdAt;

                $order->save();

                $totalQuantity = 0;
                $totalAmount = 0;

                $randomProducts = $products->random(random_int(1, 7));
                foreach ($randomProducts as $randomProduct) {
                    $quantity = random_int(1, 7);
                    $totalQuantity += $quantity;
                    $totalAmount += $randomProduct->price * $quantity;

                    $order->products()->attach($randomProduct->id, [
                        'quantity' => $quantity,
                        'created_at' => $order->createdAt,
                        'updated_at' => $order->updatedAt,
                    ]);
                }

                $order->totalQuantity = $totalQuantity;
                $order->totalAmount = $totalAmount;

                $order->save();
            }
        }
    }
}
