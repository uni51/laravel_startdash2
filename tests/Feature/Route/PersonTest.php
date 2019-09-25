<?php

namespace Tests\Feature\Route;

use Mockery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PersonTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->personMock =  Mockery::mock('App\Models\Person');
        $this->personMock
            ->shouldReceive('getAttribute')
            ->with('id')
            ->andReturn(1);
        $this->personMock
            ->shouldReceive('getAttribute')
            ->with('name')
            ->andReturn('Jessie');
        $this->personMock
            ->shouldReceive('getAttribute')
            ->with('height')
            ->andReturn(1.73);
        $this->personMock
            ->shouldReceive('getAttribute')
            ->with('weight')
            ->andReturn(59.0);
        $this->personMock
            ->shouldReceive('getAttribute')
            ->andReturn(null);        
    }

    public function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }    

    public function testPersonList()
    {
        $response = $this->json('GET','api/person');
        $response->assertStatus(200);
    }
    
    public function testPersonStore()
    {
        $this->personMock
            ->shouldReceive('create')
            ->once()
            ->andReturn($this->personMock);
        $this->app->instance('App\Models\Person', $this->personMock);        
        $response = $this->json('POST','api/person',
            ['name'=>'Johnny', 'weight'=>60, 'height'=>'1.72']);
        $response->assertStatus(200);
    }
    
    public function testPersonView()
    {
        $this->personMock
            ->shouldReceive('resolveRouteBinding')
            ->once()
            ->andReturn($this->personMock);
        $this->app->instance('App\Models\Person', $this->personMock);        
        $response = $this->json('GET','api/person/1');
        $response->assertStatus(200);
    }
    
    public function testPersonUpdate()
    {
        $this->personMock
            ->shouldReceive('resolveRouteBinding')
            ->once()
            ->andReturn($this->personMock);
        $this->personMock
            ->shouldReceive('save')
            ->once()
            ->andReturn($this->personMock);
        $this->personMock->shouldIgnoreMissing();
        $this->app->instance('App\Models\Person', $this->personMock);        
        $response = $this->json('PUT','api/person/1', ['name' => 'Johnson']);
        $response->assertStatus(200);
    }
    
    public function testPersonDelete()
    {
        $this->personMock
            ->shouldReceive('resolveRouteBinding')
            ->once()
            ->andReturn($this->personMock);
        $this->personMock
            ->shouldReceive('delete')
            ->once()
            ->andReturn(1);
        $this->app->instance('App\Models\Person', $this->personMock);        
        $response = $this->json('DELETE','api/person/1');
        $response->assertStatus(200);
    }    
}
