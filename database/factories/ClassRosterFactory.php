<?php

namespace Database\Factories;

use App\Models\Classes;
use App\Models\ClassRoster;
use App\Models\Tutee;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClassRoster>
 */
class ClassRosterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = ClassRoster::class;

    public function definition(): array
    {
        $tutee = Tutee::inRandomOrder()->first();
        $class = null;

        for ($attempt = 0; $attempt < 10; $attempt++) { // Limit to 10 attempts
            $class = Classes::inRandomOrder()->first();

            // Check if the class is suitable for the tutee
            $existsInRoster = ClassRoster::where('class_id', $class->id)
                ->where('tutee_id', $tutee->id)
                ->exists();

            if (!$existsInRoster && $class->class_students > 0) {
                if ($class->class_category == 'individual') {
                    $class->class_students--;
                    $class->save();
                    return [
                        'class_id' => $class->id,
                        'tutee_id' => $tutee->id,
                        'attendance' => 'Pending',
                        'payment_status' => 'Pending',
                    ];
                } elseif ($class->class_category == 'group') {
                    $class->class_students--;
                    $class->save();
                    return [
                        'class_id' => $class->id,
                        'tutee_id' => $tutee->id,
                        'attendance' => 'Pending',
                        'payment_status' => 'Pending',
                    ];
                }
            }
        }

        // In case no suitable class is found after attempts, return default values or handle it as needed
        throw new ModelNotFoundException("No suitable class found for tutee ID {$tutee->id}.");
    }

}
