<?php

use App\Models\Role;
use App\Models\Status;
use App\Utils\ModelRecordResolver;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->initRoles();
        $this->initImages();
        $this->initCategories();
        $this->initStatuses();

        $imageId = $this->initImages();

        foreach (array_keys($this->initProducts()) as $productId) {
            DB::table('product_image')->insert([
                ['product_id' => $productId, 'image_id' => $imageId, 'main' => true] + $this->timestamps()
            ]);
        }

        $this->initAdmin();
    }

    public function initAdmin(): void
    {
        $profileId = DB::table('profiles')->insertGetId([
            'name' => 'admin',
            'role_id' => Role::findByName(Role::USER)->getId(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('identities')->insert([
            [
                'profile_id' => $profileId,
                'password' => '$2y$12$YLvmYgn60AYj8kpwAiBxyuBbJpKvO4XBM0v0.iAmV0jn2rLKeDgty',
                'email' => 'deimosfromsolarsystem@gmail.com',
                'email_verified_at' => now()
            ] + $this->timestamps()
        ]);
    }
    public function timestamps(): array
    {
        return ['created_at' => now(), 'updated_at' => now()];
    }

    public function initRoles(): void
    {
        $records = ModelRecordResolver::getRecords(Role::class);

        foreach ($records as $record) {
            DB::table('roles')->insert([
                ['name' => $record] + $this->timestamps()
            ]);
        }
    }

    public function initCategories(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            DB::table('categories')->insert([
                ['name' => 'Категория ' . $i] + $this->timestamps()
            ]);
        }
    }

    public function initStatuses(): void
    {
        $records = ModelRecordResolver::getRecords(Status::class);

        foreach ($records as $record) {
            DB::table('statuses')
                ->insert(['name' => $record] + $this->timestamps());
        }
    }

    public function initProducts(): array
    {
        $productIds = [];

        for ($i = 1; $i <= 40; $i++) {
            $productIds[$i] = DB::table('products')->insertGetId([
                'name' => 'Товар ' . $i,
                'category_id' => random_int(1, 10),
                'short_description' => 'Короткое описание ' . $i,
                'full_description' => Str::random(300),
                'price' => $i * 100,
                'weight' => random_int(0, 30) * 0.666,
                'availability' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return $productIds;
    }

    public function initImages(): int
    {
        return DB::table('images')->insertGetId([
            'path' => 'https://media.dodostatic.net/image/r:584x584/11EE7D61706D472F9A5D71EB94149304.avif',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
