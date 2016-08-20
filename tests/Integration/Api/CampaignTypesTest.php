<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use VideoAd\Models\CampaignType;
use VideoAd\User;

class CampaignTypesTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test if we can fetch the campaign types.
     *
     * @test
     */
    public function it_fetchs_campaigns_types()
    {
        $user = User::create([
            'name' => 'test user',
            'email' => 'test@gmail.com',
            'password' => bcrypt('123456'),
            'remember_token' => str_random(10),
            'verified_phone' => 1,
            'verified_email' => 1
        ]);

        CampaignType::create([
            'title' => 'In Content Gallery',
            'alias' => 'incontentgallery',
            'available' => false,
            'single' => false,
            'has_name' => true,
        ]);

        $this->actingAs($user, 'api');

        $this->visit('/api/campaign-types')
            ->assertResponseOk()//200
            ->seeJson([
                'title' => 'In Content Gallery',
                'alias' => 'incontentgallery',
                'available' => false,
                'single' => false,
                'has_name' => true,
            ]);
    }

    /**
     * Test that any request to the api without the token
     * will result with a status code of 401. unauthenticated.
     *
     * @test
     */
    public function it_cant_fetch_data_if_not_authenticated()
    {
        $this->get('/api/campaign-types', ['Accept' => 'application/json']);
        $this->assertResponseStatus(401);
    }

    /**
     * Test Adding a new campaign type.
     *
     * @test
     */
    public function it_adds_a_campaign_type()
    {
        $user = User::create([
            'name' => 'test user',
            'email' => 'test@gmail.com',
            'password' => bcrypt('123456'),
            'remember_token' => str_random(10),
            'verified_phone' => 1,
            'verified_email' => 1
        ]);

        $this->actingAs($user, 'api');

        $this->post('/api/campaign-types', [
            'title' => 'some type',
            'alias' => 'sometype',
            'available' => true,
            'single' => true,
            'has_name' => true
        ]);

        $this->assertResponseStatus(201)
            ->seeInDatabase('campaign_types', ['title' => 'some type']);
    }

    /**
     * Test validating adding a campaign type.
     *
     * @test
     */
    public function it_fails_to_add_a_campaign_type_due_to_failed_validation()
    {
        $user = User::create([
            'name' => 'test user',
            'email' => 'test@gmail.com',
            'password' => bcrypt('123456'),
            'remember_token' => str_random(10),
            'verified_phone' => 1,
            'verified_email' => 1
        ]);

        $this->actingAs($user, 'api');

        $this->post('/api/campaign-types', [
            'title' => 'some type',
            'alias' => 'sometype',
            'available' => 'sadf',
            'single' => true,
            'has_name' => true
        ], ['Accept' => 'application/json']);

        $this->seeStatusCode(422);

        $this->seeJson(
            [
              "available" => [
                    "The available field must be true or false."
                ]
            ]
        );
    }
}
