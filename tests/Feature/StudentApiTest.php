<?php

namespace Tests\Feature;

use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StudentApiTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    use RefreshDatabase;

    public function test_can_get_students_list(): void
    {
        Student::factory()->count(5)->create();

        $response = $this->getJson('/api/students');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Students fetched successfully',
            ]);
    }

    public function test_can_create_student(): void
    {
        $payload = [
            'first_name' => 'Deepak',
            'last_name' => 'Kumar',
            'email' => 'deepak@example.com',
            'phone' => '9876543210',
        ];

        $response = $this->postJson('/api/students', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Student created successfully',
            ]);

        $this->assertDatabaseHas('students', [
            'email' => 'deepak@example.com',
        ]);
    }

    public function test_can_show_student(): void
    {
        $student = Student::factory()->create();

        $response = $this->getJson("/api/students/{$student->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Student fetched successfully',
            ]);
    }

    public function test_returns_404_when_student_not_found(): void
    {
        $response = $this->getJson('/api/students/999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Student not found',
            ]);
    }

    public function test_can_update_student(): void
    {
        $student = Student::factory()->create();

        $payload = [
            'first_name' => 'Updated Name',
        ];

        $response = $this->putJson(
            "/api/students/{$student->id}",
            $payload
        );

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Student updated successfully',
            ]);

        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'first_name' => 'Updated Name',
        ]);
    }

    public function test_can_delete_student(): void
    {
        $student = Student::factory()->create();

        $response = $this->deleteJson(
            "/api/students/{$student->id}"
        );

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Student deleted successfully',
            ]);

        $this->assertDatabaseMissing('students', [
            'id' => $student->id,
        ]);
    }

    public function test_can_search_students(): void
    {
        Student::factory()->create([
            'first_name' => 'Deepak'
        ]);

        Student::factory()->create([
            'first_name' => 'Rahul'
        ]);

        $response = $this->getJson(
            '/api/students?search=Deepak'
        );

        $response->assertStatus(200)
            ->assertJsonFragment([
                'first_name' => 'Deepak'
            ]);
    }
}
