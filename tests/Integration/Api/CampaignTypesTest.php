<?php

namespace App\Tests\Integration\Api;

use App\Testing\TestCase;
use App\Models\CampaignType;
use App\Testing\DatabaseTransactions;
use App\User;

class CampaignTypesTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    /**
     * Test if we can fetch the campaign types.
     *
     * @test
     */
    public function it_fetches_campaigns_types()
    {
        CampaignType::create([
            'title'     => 'In Content Gallery',
            'alias'     => 'incontentgallery',
            'available' => false,
            'single'    => false,
            'has_name'  => true,
        ]);

        $this->actingAs($this->user, 'api');

        $this->visit('/api/campaign-types')
            ->assertResponseOk()//200
            ->seeJson([
                'title'     => 'In Content Gallery',
                'alias'     => 'incontentgallery',
                'available' => false,
                'single'    => false,
                'has_name'  => true,
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
        $this->actingAs($this->user, 'api');

        $this->post('/api/campaign-types', [
            'title'     => 'some type',
            'alias'     => 'sometype',
            'available' => true,
            'single'    => true,
            'has_name'  => true,
        ], ['Accept' => 'application/json']);

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
        $this->actingAs($this->user, 'api');

        $this->post('/api/campaign-types', [
            'title'     => 'some type',
            'alias'     => 'sometype',
            'available' => 'sadf',
            'single'    => true,
            'has_name'  => true,
        ], ['Accept' => 'application/json']);

        $this->seeStatusCode(422);

        $this->seeJson(
            [
                'available' => [
                    'The available field must be true or false.',
                ],
            ]
        );
    }

    /**
     * Test updates an existing campaign type.
     *
     * @test
     */
    public function it_updates_an_existing_campaign_type()
    {
        $this->actingAs($this->user, 'api');

        $type = factory(CampaignType::class)->create();

        $this->put('/api/campaign-types/'.$type->id, [
            'title'     => $type->title,
            'alias'     => $type->alias,
            'available' => true,
            'single'    => true,
            'has_name'  => true,
        ], ['Accept' => 'application/json']);

        $this->assertResponseStatus(200);
        $this->seeJson(
            [
                'message' => 'Successfully updated the campaign type.',
            ]
        );
    }

    /**
     * Test deleting a campaign type.
     *
     * @test
     */
    public function it_deletes_a_campaign_type()
    {
        $user = User::create([
            'name'           => 'test user',
            'email'          => 'test@gmail.com',
            'password'       => '123456',
            'remember_token' => str_random(10),
            'verified_phone' => 1,
            'verified_email' => 1,
        ]);

        $this->actingAs($user, 'api');

        $type = factory(CampaignType::class)->create();

        $this->delete('/api/campaign-types/'.$type->id, [], ['Accept' => 'application/json']);

        $this->assertResponseStatus(200)
            ->dontSeeInDatabase('campaign_types', $type->toArray());
    }
}
