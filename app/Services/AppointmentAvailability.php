<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Service;
use Illuminate\Support\Carbon;

class AppointmentAvailability
{
    /**
     * @param  array<int, int|string>  $serviceIds
     * @return list<string>
     */
    public function unavailableTimes(
        string $date,
        int $userId,
        array $serviceIds,
        ?int $exceptAppointmentId = null
    ): array {
        $requestedDuration = max(30, (int) Service::whereKey($serviceIds)->sum('duration'));

        $appointments = Appointment::query()
            ->with('services:id,duration')
            ->whereDate('date', $date)
            ->where('status', '!=', 'canceled')
            ->where(function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->orWhereNull('user_id');
            })
            ->when($exceptAppointmentId, fn ($query) => $query->where('id', '!=', $exceptAppointmentId))
            ->get(['id', 'time']);

        $unavailableTimes = [];

        foreach ($this->availableTimes() as $time) {
            $start = $this->minutesAfterMidnight($time);
            $end = $start + $requestedDuration;

            foreach ($appointments as $existingAppointment) {
                $existingStart = $this->minutesAfterMidnight((string) $existingAppointment->time);
                $existingEnd = $existingStart + max(
                    30,
                    (int) $existingAppointment->services->sum('duration')
                );

                if ($start < $existingEnd && $end > $existingStart) {
                    $unavailableTimes[] = $time;
                    break;
                }
            }
        }

        return $unavailableTimes;
    }

    /**
     * @return list<string>
     */
    public function pastTimes(string $date): array
    {
        if ($date !== now('Europe/Madrid')->toDateString()) {
            return [];
        }

        return collect($this->availableTimes())
            ->filter(fn ($time) => now('Europe/Madrid')->greaterThanOrEqualTo(
                Carbon::parse($date.' '.$time, 'Europe/Madrid')
            ))
            ->values()
            ->all();
    }

    /**
     * @return list<string>
     */
    public function availableTimes(): array
    {
        $times = [];

        for ($hour = 9; $hour < 21; $hour++) {
            for ($minute = 0; $minute < 60; $minute += 30) {
                $times[] = sprintf('%02d:%02d', $hour, $minute);
            }
        }

        return $times;
    }

    private function minutesAfterMidnight(string $time): int
    {
        [$hours, $minutes] = array_map('intval', explode(':', substr($time, 0, 5)));

        return ($hours * 60) + $minutes;
    }
}
