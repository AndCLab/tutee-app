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
        while (true) { // Infinite loop, will continue until a suitable class and tutee combination is found
            $tutee = Tutee::inRandomOrder()->first();
            $class = null;

            for ($i = 0; $i < 10; $i++) { // Limit to 10 attempts to find a suitable class
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
            // If no suitable class found after 10 attempts, try with a new tutee
        }
    }

    // If no suitable class is found in 10 tries, the loop fetches a new tutee and starts the process again.

}