<?php

namespace Tests\Feature;

use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceRequestSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_cannot_update_request_status(): void
    {
        $student = User::factory()->create(['is_admin' => false]);
        $owner = User::factory()->create(['is_admin' => false]);

        $serviceRequest = ServiceRequest::create([
            'user_id' => $owner->id,
            'requester_name' => $owner->name,
            'requester_email' => $owner->email,
            'item_name' => 'Printer Ink',
            'quantity' => 1,
            'purpose' => 'Security test',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($student)
            ->patch(route('requests.updateStatus', $serviceRequest), [
                'status' => 'approved',
            ]);

        $response->assertForbidden();

        $this->assertDatabaseHas('requests', [
            'id' => $serviceRequest->id,
            'status' => 'pending',
        ]);
    }

    public function test_student_cannot_view_another_students_request(): void
    {
        $student = User::factory()->create(['is_admin' => false]);
        $owner = User::factory()->create(['is_admin' => false]);

        $serviceRequest = ServiceRequest::create([
            'user_id' => $owner->id,
            'requester_name' => $owner->name,
            'requester_email' => $owner->email,
            'item_name' => 'Bond Paper',
            'quantity' => 1,
            'purpose' => 'Ownership test',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($student)
            ->get(route('requests.show', $serviceRequest));

        $response->assertForbidden();
    }

    public function test_administrator_can_update_request_status(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $serviceRequest = ServiceRequest::create([
            'user_id' => null,
            'requester_name' => 'Student A',
            'requester_email' => 'studenta@example.com',
            'item_name' => 'Printer Ink',
            'quantity' => 1,
            'purpose' => 'Administrator access test',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)
            ->patch(route('requests.updateStatus', $serviceRequest), [
                'status' => 'approved',
            ]);

        $response->assertRedirect(
            route('requests.show', $serviceRequest)
        );

        $this->assertDatabaseHas('requests', [
            'id' => $serviceRequest->id,
            'status' => 'approved',
        ]);
    }

    public function test_student_cannot_create_request_with_zero_quantity(): void
    {
        $student = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($student)
            ->post(route('requests.store'), [
                'item_name' => 'Notebook',
                'quantity' => 0,
                'purpose' => 'Invalid quantity test',
            ]);

        $response->assertSessionHasErrors('quantity');

        $this->assertDatabaseMissing('requests', [
            'item_name' => 'Notebook',
            'purpose' => 'Invalid quantity test',
        ]);
    }
}