<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Controllers\Api\BasicCrudController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
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

   public function testInvalidationDataInStore()
   {
       $this->expectException(ValidationException::class);
       
       $request = \Mockery::mock(Request::class);
       $request->shouldReceive('all')->once()->andReturn(['name' => '']);
       $this->controller->store($request);
   }

    public function testStore(){
        
       $request = \Mockery::mock(Request::class);
       $request->shouldReceive('all')
               ->once()
               ->andReturn(['name' => 'test_name', 'description'=>'test_description']);

       $obj = $this->controller->store($request) ;
       $this->assertEquals(CategoryStub::find(1)->toArray(),$obj->toArray());

    }   
    public function testIfFindOrFailFetchModel(){
        /**@var CategoryStub $category */
        $category = CategoryStub::create(['name'=>'test_name','description'=>'test_description']);

        $reflectionClass = new \ReflectionClass(BasicCrudController::class);
        $reflectionMethod = $reflectionClass->getMethod('findOrFail');
        $reflectionMethod->setAccessible(true);

        $result = $reflectionMethod->invokeArgs($this->controller,[$category->id]);
        //dd($result);
        $this->assertInstanceOf(CategoryStub::class,$result);
    }

    public function testIfFindOrFailThrowExceptionWhenIdInvalid(){
      
        $this->expectException(ModelNotFoundException::class);
        $reflectionClass = new \ReflectionClass(BasicCrudController::class);
        $reflectionMethod = $reflectionClass->getMethod('findOrFail');
        $reflectionMethod->setAccessible(true);

        $result = $reflectionMethod->invokeArgs($this->controller,[0]);
        //dd($result);
        $this->assertInstanceOf(CategoryStub::class,$result);
    }

    public function testUpdate(){
        
        $request = \Mockery::mock(Request::class);
        $request->shouldReceive('all')
                ->once()
                ->andReturn(['name' => 'test_name_update', 'description'=>'test_description_update']);
 
        $obj = $this->controller->update($request) ;
        $this->assertEquals(CategoryStub::find(1)->toArray(),$obj->toArray());
 
     }   

     public function testDelete(){
        
        $request = \Mockery::mock(Request::class);
        $request->shouldReceive('all')
                ->once()
                ->andReturn(['name' => 'test_name_update', 'description'=>'test_description_update']);
 
        $obj = $this->controller->destroy() ;
        $this->assertEquals(CategoryStub::find(1)->toArray(),$obj->toArray());
 
     } 
}