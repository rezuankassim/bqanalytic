<?php

namespace RezuanKassim\BQAnalytic\Tests\Features;

use Illuminate\Foundation\Testing\RefreshDatabase;
use RezuanKassim\BQAnalytic\BQClient;
use RezuanKassim\BQAnalytic\Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use RezuanKassim\BQAnalytic\BQSubclient;

class SubclientTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_subclient_relationship_exists()
    {
        $client = factory(BQClient::class)->create();
        $subclient = factory(BQSubclient::class)->create([
            'client_id' => $client->id
        ]);

        $this->assertTrue($client->subclients->contains($subclient));
    }
}
