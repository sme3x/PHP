<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\TestModel;
use Tests\TestCase;

/**
 * This are unit tests for the TestModel/Controller
 */
class LocalCommunityControllerTest extends TestCase
{
    use RefreshDatabase;

    private $url = '/api/v1/local-communities/';

    public function testCreateLocalCommunity()
    {
        $testModelValue = TestModel::factory()->make()->toArray();

        $response = $this->postAuthorized($this->url, $testModelValue);

        $response->assertStatus(200);

        $this->assertDatabaseHas('local_communities', $testModelValue);
    }

    public function testGetLocalCommunities()
    {
        TestModel::factory()->create();

        $response = $this->getAuthorized($this->url);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'current_page',
                'data' => [
                    '*' => [
                        'title'
                    ]
                ],
                'from',
                'last_page',
                'per_page',
                'to',
                'total'
            ]);
    }

    public function testGetLocalCommunity()
    {
        $testModelValue = TestModel::factory()->create();

        $response = $this->getAuthorized($this->url . $testModelValue->id);

        $response
            ->assertStatus(200)
            ->assertJson($testModelValue->toArray());
    }

    public function testUpdateLocalCommunity()
    {
        $testModelValue = TestModel::factory()->create();
        $testModelValueUpdate = TestModel::factory()->make()->toArray();

        $response = $this->putAuthorized($this->url . $testModelValue->id, $testModelValueUpdate);

        $response
            ->assertStatus(200)
            ->assertJson($testModelValueUpdate);

        $this->assertDatabaseHas('local_communities', $testModelValueUpdate);
        $this->assertDatabaseMissing('local_communities', $testModelValue->toArray());
    }

    public function testDestroyLocalCommunity()
    {
        $testModelValue = LocalCommunity::factory()->create();

        $response = $this->deleteAuthorized($this->url . $testModelValue->id);

        $response->assertStatus(204);
        $this->assertSoftDeleted("local_communities", ["id" => $testModelValue->id]);
    }
}
