<?php

namespace Tests\Feature\Http\Controllers\Api;

use Tests\Stubs\Controllers\CategoryControllerStub;
use Tests\Stubs\Models\CategoryStub;
use Tests\TestCase;

class BasicCrudControllerTest extends TestCase
{
    private $controller;
   protected function setUp(): void
   {
       parent::setUp();
       CategoryStub::dropTable();
       CategoryStub::createTable();
       $this->controller= new CategoryControllerStub();
   }

   protected function tearDown(): void
   {
       CategoryStub::dropTable();
       parent::tearDown();

   }
   /**@var CategoryStub $category */
   public function testIndex(){
       $category = CategoryStub::create(['name'=>'test_name','description'=>'test_description']);
       $result = $this->controller->index()->toArray();
       $this->assertEquals([$category->toArray()],$result);
   }
/**
 * @expectedException \Iluminate\Validation\ValidationException
 */
   public function testInvalidationDataInStore(){
      
      $request = \Mockery::mock(\Illuminate\Http\Request::class);
      dd($request);
    //   $request->shouldReceive('all')
    //           ->once()
    //           ->andReturn(['name'=>'']);
    //   $this->controller->store($request);
   }
}