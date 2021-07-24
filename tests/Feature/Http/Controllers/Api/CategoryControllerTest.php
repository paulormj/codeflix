<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use PhpParser\Node\Expr\FuncCall;
use Symfony\Component\CssSelector\Node\FunctionNode;
use Tests\TestCase;
use Tests\Traits\TestSaves;
use Tests\Traits\TestValidations;

class CategoryControllerTest extends TestCase
{
    use DatabaseMigrations,TestValidations,TestSaves;
   
    private $category;
    protected function setUp(): void
    {
        parent::setUp();
        $this->category = factory(Category::class)->create();
    }

    public function testIndex()
    {
                $response = $this->get(route('categories.index'));

        $response->assertStatus(200)
                  ->assertJson([$this->category->toArray()]);

                  
    }

    public function testShow()
    {
                $response = $this->get(route('categories.show', ['category'=> $this->category->id]));

        $response->assertStatus(200)
                  ->assertJson($this->category->toArray());

    }
    public function testInvalidationData(){

        $data = ['name'=>''];
        $this->assertInvalidationInStoreAction($data,'required');
        $this->assertInvalidationInUpdateAction($data,'required');

        $data = [
            'name' => str_repeat('a',256)
        ];
        $this->assertInvalidationInStoreAction($data,'max.string',['max'=>255]);
        $this->assertInvalidationInUpdateAction($data,'max.string',['max'=>255]);
        $data = [
            'is_active'=>'a'
        ];
        $this->assertInvalidationInStoreAction($data,'boolean');
        $this->assertInvalidationInUpdateAction($data,'boolean');
        
    }

    public function testStore(){

        $data = ['name'=>'test'];
        $response = $this->assertStore($data,$data + ['description'=>null,'is_active'=>true,'deleted_at'=>null]);
        $response->assertJsonStructure(['created_at','updated_at']);
        $data = ['name'=>'teste',
                 'description'=>'test_description',
                 'is_active'=>false];
        $response = $this->assertStore($data,$data + ['description'=>'test_description','is_active'=>false,'deleted_at'=>null]);                 
        

    }

    public function testUpdate(){

        $this->category = factory(Category::class)->create([
            'is_active'=>false,
            'description'=>'description'
        ]);
        $data = [
            'name'=>'teste',
            'description'=>'test_description',
            'is_active'=>true
        ];
        $response = $this->assertUpdate($data,$data +['deleted_at'=>null] );
        
        $data = [
            'name'=>'teste',
             'description'=>'',
             'is_active'=>true

        ];
        $this->assertUpdate($data, array_merge($data, ['description'=>null] ));


        $data = [
            'name'=>'teste',
             'description'=>'test',
             'is_active'=>true

        ];
        $this->assertUpdate($data, array_merge($data, ['description'=>'test']));

        $data = [
            'name'=>'teste',
             'description'=>null,
             'is_active'=>true

        ];
        $this->assertUpdate($data, array_merge($data, ['description'=>null]));
        
        }

        public function testDelete(){
            $category = factory(Category::class)->create([
                'is_active'=>false,
                'description'=>'description'
            ]);
    
            $response = $this->json('DELETE',
                        route('categories.destroy',
                        ['category'=>$category->id]),
                        []);

           $response->assertStatus(204);
           $this->assertNull(Category::find($category->id));
           $this->assertNotNull(Category::withTrashed()->find($category->id));

                 

        }

        protected function routeStore(){
            return route('categories.store');
        }
        protected function routeUpdate(){
            return route('categories.update',['category'=>$this->category->id]);
        }
        protected Function model(){
            return Category::class;
        }
}
