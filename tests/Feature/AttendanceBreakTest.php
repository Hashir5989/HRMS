<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AttendanceBreakTest extends TestCase
{
    use DatabaseTransactions;

    public function test_employee_can_start_and_end_break_with_reason(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // 1. Clock in
        $this->post(route('attendance.clockIn'));

        $this->assertDatabaseHas('attendances', [
            'user_id' => $user->id,
            'date' => Carbon::today()->format('Y-m-d'),
        ]);

        // 2. Start break with reason
        $response = $this->post(route('attendance.startBreak'), [
            'break_reason' => 'Lunch break',
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('attendances', [
            'user_id' => $user->id,
            'break_reason' => 'Lunch break',
            'on_break' => true,
        ]);

        // 3. End break
        $response = $this->post(route('attendance.endBreak'));
        $response->assertSessionHas('success');

        $attendance = Attendance::where('user_id', $user->id)->first();
        $this->assertFalse((bool) $attendance->on_break);
        $this->assertNotNull($attendance->break_end);
    }

    public function test_employee_cannot_take_more_than_one_break_per_day(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Clock in & take first break
        $this->post(route('attendance.clockIn'));
        $this->post(route('attendance.startBreak'), [
            'break_reason' => 'First break',
        ]);
        $this->post(route('attendance.endBreak'));

        // Attempt second break
        $response = $this->post(route('attendance.startBreak'), [
            'break_reason' => 'Second break',
        ]);

        $response->assertSessionHas('error', 'Only 1 break is allowed per day. You have already taken your break today.');
    }
}
