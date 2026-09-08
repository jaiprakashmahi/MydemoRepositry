<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\LocationLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class JourneyTimelineSeeder extends Seeder
{
    public function run(): void
    {
        $date = Carbon::today();

        $raj = Employee::updateOrCreate(
            ['phone' => '9876543210'],
            ['name' => 'Raj Kumar', 'designation' => 'Field Sales Executive', 'is_active' => true]
        );

        $deepak = Employee::updateOrCreate(
            ['phone' => '7526972793'],
            ['name' => 'Deepak Kumar', 'designation' => 'Field Sales Executive', 'is_active' => true]
        );

        LocationLog::where('employee_id', $raj->id)
            ->whereBetween('recorded_at', [$date->copy()->startOfDay(), $date->copy()->endOfDay()])
            ->delete();

        // Stops taken straight from the spec example, with approximate
        // Patna-area coordinates so the demo route looks realistic.
        $stops = [
            ['name' => 'Home / Starting Point', 'time' => '08:45 AM', 'lat' => 25.6127, 'lng' => 85.1410, 'stay' => 5],
            ['name' => 'Office', 'time' => '09:05 AM', 'lat' => 25.6050, 'lng' => 85.1350, 'stay' => 15],
            ['name' => 'Customer Location A', 'time' => '10:15 AM', 'lat' => 25.5941, 'lng' => 85.1376, 'stay' => 20],
            ['name' => 'Customer Location B', 'time' => '11:30 AM', 'lat' => 25.5890, 'lng' => 85.1520, 'stay' => 18],
            ['name' => 'Lunch / Break', 'time' => '01:00 PM', 'lat' => 25.5975, 'lng' => 85.1602, 'stay' => 30],
            ['name' => 'Customer Location C', 'time' => '02:15 PM', 'lat' => 25.6033, 'lng' => 85.1685, 'stay' => 25],
            ['name' => 'Office / End Point', 'time' => '05:40 PM', 'lat' => 25.6050, 'lng' => 85.1350, 'stay' => 10],
        ];

        $this->seedJourney($raj, $date, $stops);

        // Deepak Kumar: just one GPS ping so far today (matches the
        // "0.00 KM / 0 min / 1 place / 0 trips" empty state in the UI).
        LocationLog::where('employee_id', $deepak->id)
            ->whereBetween('recorded_at', [$date->copy()->startOfDay(), $date->copy()->endOfDay()])
            ->delete();

        LocationLog::create([
            'employee_id' => $deepak->id,
            'latitude' => 25.6050,
            'longitude' => 85.1350,
            'place_name' => 'Location',
            'recorded_at' => $date->copy()->setTime(12, 12),
        ]);
    }

    protected function seedJourney(Employee $employee, Carbon $date, array $stops): void
    {
        $pingIntervalMinutes = 3;
        $previous = null;

        foreach ($stops as $stop) {
            $arrival = $date->copy()->setTimeFromTimeString(
                Carbon::createFromFormat('h:i A', $stop['time'])->format('H:i:s')
            );
            $departure = $arrival->copy()->addMinutes($stop['stay']);

            // Interpolate travel pings between the previous stop and this one.
            if ($previous !== null) {
                $this->seedTravelSegment(
                    $employee,
                    $previous['lat'],
                    $previous['lng'],
                    $previous['departure'],
                    $stop['lat'],
                    $stop['lng'],
                    $arrival,
                );
            }

            // Stationary pings while at the stop, every few minutes.
            for ($t = $arrival->copy(); $t->lte($departure); $t->addMinutes($pingIntervalMinutes)) {
                LocationLog::create([
                    'employee_id' => $employee->id,
                    'latitude' => $stop['lat'] + $this->jitter(),
                    'longitude' => $stop['lng'] + $this->jitter(),
                    'place_name' => $stop['name'],
                    'recorded_at' => $t->copy(),
                ]);
            }

            $previous = [
                'lat' => $stop['lat'],
                'lng' => $stop['lng'],
                'departure' => $departure,
            ];
        }
    }

    protected function seedTravelSegment(
        Employee $employee,
        float $fromLat,
        float $fromLng,
        Carbon $fromTime,
        float $toLat,
        float $toLng,
        Carbon $toTime
    ): void {
        $steps = max($fromTime->diffInMinutes($toTime), 1);
        $steps = (int) min($steps, 12); // cap number of interpolated pings

        for ($i = 1; $i < $steps; $i++) {
            $fraction = $i / $steps;

            LocationLog::create([
                'employee_id' => $employee->id,
                'latitude' => $fromLat + ($toLat - $fromLat) * $fraction,
                'longitude' => $fromLng + ($toLng - $fromLng) * $fraction,
                'place_name' => null,
                'recorded_at' => $fromTime->copy()->addMinutes(
                    (int) round($fromTime->diffInMinutes($toTime) * $fraction)
                ),
            ]);
        }
    }

    protected function jitter(): float
    {
        return random_int(-20, 20) / 100000; // ~±20 meters of GPS noise
    }
}
