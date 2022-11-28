<?php

namespace Tests\Feature\Dashboard\Company\Log;

use App\Models\Log\Log;
use App\Models\User\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogTest extends TestCase
{
    use WithFaker;

    /**
     * test populate company logs
     *
     * @return void
     */
    /*
    public function test_populate_company_logs()
    {
        $this->actingAs(
            $user = User::factory()->owner()->create()
        );

        $logs = Log::factory()->for($user->owner->company)
            ->name("user.login")
            ->causer($user)
            ->subject($user)
            ->count(10)
            ->create();

        $response = $this->getJson("/api/dashboard/companies/logs");

        $response->assertStatus(200);
        $response->assertJsonStructure(['logs']);
    }
    */

    /**
     * test populate company trashed logs
     *
     * @return void
     */
    /*
    public function test_populate_company_trashed_logs()
    {
        $this->actingAs(
            $user = User::factory()->owner()->create()
        );

        $logs = Log::factory()->for($user->owner->company)
            ->name("user.login")
            ->causer($user)
            ->subject($user)
            ->count(10)
            ->create();

        $response = $this->getJson("/api/dashboard/companies/logs");

        $response->assertStatus(200);
        $response->assertJsonStructure(['logs']);
    }
    */

    /**
     * test restore log by log ids
     *
     * @return void
     */
    public function test_restore_log_by_ids()
    {
        $this->actingAs(
            $user = User::factory()->owner()->create()
        );

        $logs = Log::factory()->for($user->owner->company)
            ->name("user.login")
            ->causer($user)
            ->subject($user)
            ->count(2)
            ->create();
        $logIds = $logs->pluck("id")->toArray();

        $this->assertTrue(
            !!Log::whereIn("id", $logIds)->delete()
        );
        foreach ($logs as $log) {
            $this->assertSoftDeleted((new Log())->getTable(), [
                "id" => $log->id,
            ]);
        }

        $response = $this->putJson("/api/dashboard/companies/logs/restore", [
            "ids" => $logIds,
            "force" => true,
        ]);

        foreach ($logs as $log) {
            $this->assertDatabaseHas((new Log())->getTable(), [
                "id" => $log->id,
                "deleted_at" => null,
            ]);
        }

        $response->assertStatus(200);
        $response->assertJsonStructure(['status', 'message']);
    }

    /**
     * test restore log by start and end
     *
     * @return void
     */
    public function test_restore_log_where_between_start_and_end()
    {
        $this->actingAs(
            $user = User::factory()->owner()->create()
        );

        $start = now()->copy()->subDays(2);
        $end = now()->copy();
        $logs = [];
        for ($i = 1; $i <= 2; $i++) {
            $logs[] = Log::factory()->for($user->owner->company)
                ->name("user.login")
                ->causer($user)
                ->subject($user)
                ->create([
                    "id" => generateUuid(),
                    "created_at" => $start->copy()->addHours(rand(6, 18))
                ]);
        }

        $this->assertTrue(
            !!Log::whereBetween("created_at", [
                $start,
                $end,
            ])->delete()
        );
        foreach ($logs as $log) {
            $this->assertSoftDeleted((new Log())->getTable(), [
                "id" => $log->id,
            ]);
        }

        $response = $this->putJson("/api/dashboard/companies/logs/restore", [
            "start" => $start->toDateTimeString(),
            "end" => $end->toDateTimeString(),
            "force" => true,
        ]);

        foreach ($logs as $log) {
            $this->assertDatabaseHas((new Log())->getTable(), [
                "id" => $log->id,
                "deleted_at" => null,
            ]);
        }

        $response->assertStatus(200);
        $response->assertJsonStructure(['status', 'message']);
    }

    /**
     * test force delete log by datetime
     *
     * @return void
     */
    public function test_restore_log_by_datetime()
    {
        $this->actingAs(
            $user = User::factory()->owner()->create()
        );

        $createdAt = now();
        $logs = [];
        for ($i = 1; $i <= 2; $i++) {
            $logs[] = Log::factory()->for($user->owner->company)
                ->name("user.login")
                ->causer($user)
                ->subject($user)
                ->create([
                    "id" => generateUuid(),
                    "created_at" => $createdAt
                ]);
        }

        $this->assertTrue(
            !!Log::whereBetween("created_at", [
                $createdAt->copy()->startOfHour(),
                $createdAt->copy()->endOfHour(),
            ])->delete()
        );
        foreach ($logs as $log) {
            $this->assertSoftDeleted((new Log())->getTable(), [
                "id" => $log->id,
            ]);
        }

        $response = $this->putJson("/api/dashboard/companies/logs/restore", [
            "datetime" => $createdAt->copy()->startOfHour()->toDateTimeString(),
            "force" => true,
        ]);

        foreach ($logs as $log) {
            $this->assertDatabaseHas((new Log())->getTable(), [
                "id" => $log->id,
                "deleted_at" => null,
            ]);
        }

        $response->assertStatus(200);
        $response->assertJsonStructure(['status', 'message']);
    }

    /**
     * * DELETE TEST
     * * DELETE TEST
     * * DELETE TEST
     */

    /**
     * test soft delete log by log ids
     *
     * @return void
     */
    public function test_soft_delete_log_by_ids()
    {
        $this->actingAs(
            $user = User::factory()->owner()->create()
        );

        $logs = Log::factory()->for($user->owner->company)
            ->name("user.login")
            ->causer($user)
            ->subject($user)
            ->count(2)
            ->create();
        $logIds = $logs->pluck("id")->toArray();

        $response = $this->deleteJson("/api/dashboard/companies/logs/delete", [
            "ids" => $logIds,
            "force" => false,
        ]);

        foreach ($logs as $log) {
            $this->assertSoftDeleted((new Log())->getTable(), [
                "id" => $log->id,
            ]);
        }

        $response->assertStatus(200);
        $response->assertJsonStructure(['status', 'message']);
    }

    /**
     * test soft delete log by start and end
     *
     * @return void
     */
    public function test_soft_delete_log_where_between_start_and_end()
    {
        $this->actingAs(
            $user = User::factory()->owner()->create()
        );

        $start = now()->copy()->subDays(2)->startOfHour();
        $end = now()->copy()->endOfHour();
        $logs = [];
        for ($i = 1; $i <= 2; $i++) {
            $logs[] = Log::factory()->for($user->owner->company)
                ->name("user.login")
                ->causer($user)
                ->subject($user)
                ->create([
                    "id" => generateUuid(),
                    "created_at" => $start->copy()->addHours(rand(6, 18)),
                    "deleted_at" => null,
                ]);
        }

        $response = $this->deleteJson("/api/dashboard/companies/logs/delete", [
            "start" => $start->copy()->toDateTimeString(),
            "end" => $end->copy()->toDateTimeString(),
            "force" => false,
        ]);

        foreach ($logs as $log) {
            $this->assertSoftDeleted((new Log())->getTable(), [
                "id" => $log->id,
            ]);
        }

        $response->assertStatus(200);
        $response->assertJsonStructure(['status', 'message']);
    }

    /**
     * test soft delete log by datetime
     *
     * @return void
     */
    public function test_soft_delete_log_by_datetime()
    {
        $this->actingAs(
            $user = User::factory()->owner()->create()
        );

        $createdAt = now();
        $logs = [];
        for ($i = 1; $i <= 2; $i++) {
            $logs[] = Log::factory()->for($user->owner->company)
                ->name("user.login")
                ->causer($user)
                ->subject($user)
                ->create([
                    "id" => generateUuid(),
                    "created_at" => $createdAt
                ]);
        }

        $response = $this->deleteJson("/api/dashboard/companies/logs/delete", [
            "datetime" => $createdAt->copy()->startOfHour()->toDateTimeString(),
            "force" => false,
        ]);

        foreach ($logs as $log) {
            $this->assertSoftDeleted((new Log())->getTable(), [
                "id" => $log->id,
            ]);
        }

        $response->assertStatus(200);
        $response->assertJsonStructure(['status', 'message']);
    }

    /**
     * test force delete log by log ids
     *
     * @return void
     */
    public function test_force_delete_log_by_ids()
    {
        $this->actingAs(
            $user = User::factory()->owner()->create()
        );

        $logs = Log::factory()->for($user->owner->company)
            ->name("user.login")
            ->causer($user)
            ->subject($user)
            ->count(2)
            ->create();
        $logIds = $logs->pluck("id")->toArray();

        $this->assertTrue(
            !!Log::whereIn("id", $logIds)->delete()
        );
        foreach ($logs as $log) {
            $this->assertSoftDeleted((new Log())->getTable(), [
                "id" => $log->id,
            ]);
        }

        $response = $this->deleteJson("/api/dashboard/companies/logs/delete", [
            "ids" => $logIds,
            "force" => true,
        ]);

        foreach ($logs as $log) {
            $this->assertDatabaseMissing((new Log())->getTable(), [
                "id" => $log->id,
            ]);
        }

        $response->assertStatus(200);
        $response->assertJsonStructure(['status', 'message']);
    }

    /**
     * test force delete log by start and end
     *
     * @return void
     */
    public function test_force_delete_log_where_between_start_and_end()
    {
        $this->actingAs(
            $user = User::factory()->owner()->create()
        );

        $start = now()->copy()->subDays(2);
        $end = now()->copy();
        $logs = [];
        for ($i = 1; $i <= 2; $i++) {
            $logs[] = Log::factory()->for($user->owner->company)
                ->name("user.login")
                ->causer($user)
                ->subject($user)
                ->create([
                    "id" => generateUuid(),
                    "created_at" => $start->copy()->addHours(rand(6, 18))
                ]);
        }

        $this->assertTrue(
            !!Log::whereBetween("created_at", [
                $start,
                $end,
            ])->delete()
        );
        foreach ($logs as $log) {
            $this->assertSoftDeleted((new Log())->getTable(), [
                "id" => $log->id,
            ]);
        }

        $response = $this->deleteJson("/api/dashboard/companies/logs/delete", [
            "start" => $start->toDateTimeString(),
            "end" => $end->toDateTimeString(),
            "force" => true,
        ]);

        foreach ($logs as $log) {
            $this->assertDatabaseMissing((new Log())->getTable(), [
                "id" => $log->id,
            ]);
        }

        $response->assertStatus(200);
        $response->assertJsonStructure(['status', 'message']);
    }

    /**
     * test force delete log by datetime
     *
     * @return void
     */
    public function test_force_delete_log_by_datetime()
    {
        $this->actingAs(
            $user = User::factory()->owner()->create()
        );

        $createdAt = now();
        $logs = [];
        for ($i = 1; $i <= 2; $i++) {
            $logs[] = Log::factory()->for($user->owner->company)
                ->name("user.login")
                ->causer($user)
                ->subject($user)
                ->create([
                    "id" => generateUuid(),
                    "created_at" => $createdAt
                ]);
        }

        $this->assertTrue(
            !!Log::whereBetween("created_at", [
                $createdAt->copy()->startOfHour(),
                $createdAt->copy()->endOfHour(),
            ])->delete()
        );
        foreach ($logs as $log) {
            $this->assertSoftDeleted((new Log())->getTable(), [
                "id" => $log->id,
            ]);
        }

        $response = $this->deleteJson("/api/dashboard/companies/logs/delete", [
            "datetime" => $createdAt->copy()->startOfHour()->toDateTimeString(),
            "force" => true,
        ]);

        foreach ($logs as $log) {
            $this->assertDatabaseMissing((new Log())->getTable(), [
                "id" => $log->id,
            ]);
        }

        $response->assertStatus(200);
        $response->assertJsonStructure(['status', 'message']);
    }
}
