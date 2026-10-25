<?php

namespace Database\Factories;

use App\Models\LabOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class LabOrderFactory extends Factory
{
    protected $model = LabOrder::class;

    public function definition(): array
    {
        return [
            'lab_supplier_name' => fake()->company(),
            'status' => fake()->randomElement(['MENUNGGU', 'DIPESAN', 'DITERIMA', 'SALAH']),
            'lab_cost' => fake()->randomElement([0, 150000, 300000, 500000]),
            'rejection_reason' => fake()->optional()->sentence(),
            'expected_completion_date' => fake()->optional()->dateBetween('+1 week', '+3 weeks'),
        ];
    }
}
