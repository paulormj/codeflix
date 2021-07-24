<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Route;
use Tests\TestCase;

class GenreControllerTest extends TestCase
{
    use DatabaseMigrations;


    
    public function testIndex()
    {
        $genre = factory(Genre::class)->create();
        $response = $this->get(Route('genres.index'));

        $response->assertStatus(200)
                  ->assertJson([$genre->toArray()]);
    }

    public function testShow()
    {
        $genre = factory(Genre::class)->create();
        $response = $this->get(Route('genres.show',['genre'=>$genre->id]));

        $response->assertStatus(200)
                  ->assertJson($genre->toArray());
    }

    public function testInvalidationData(){
        $response = $this->json('POST', route('genres.store'),[]);
        $this->assertInvalidationRequired($response);

        $response = $this->json('POST',route('genres.store'),
        ['name' => str_repeat('a',256),
         'is_active'=>'a']);
         $this->assertInvalidationMax($response);

         $genre = factory(Genre::class)->create();    
         $response = $this->json('PUT',route('genres.update'
                                             ,['genre'=>$genre->id]),[]);
        $this->assertInvalidationRequired($response);   

        $response = $this->json('PUT',route('genres.update'
        ,['genre'=>$genre->id]),[]);
        $this->assertInvalidationMax($response);   
        
    }

    public function testStore(){
        $response = $this->json('POST',route('genres.store'),['name'=>'teste']);
        $id = $response->json('id');
        $genre = Genre::find($id);

        $response->assertStatus(201)
                  ->assertJson($genre->toArray());
        $this->assertTrue($response->json('is_active'));
        $this->assertNotNull($response->json('name'));

        $response = $this->json('POST',route('genres.store'),[
            'name'=>'test_name',
            'is_active'=>false

        ]);
        $response->assertJsonFragment([
            'is_active'=>false,
            'name'=>'test_name']
        );
    }

    public function testUpdate(){

        $genre = factory(Genre::class)->create([
            'is_active'=>false
        ]);

        $response = $this->json('PUT',
                    route('genres.update',
                    ['genre'=>$genre->id]),
                    [
                        'name'=>'teste',
                        'description'=>'test_description',
                        'is_active'=>true
            
                    ]);
         $id = $response->json('id');
         $genre = Genre::find($id);
         $response->assertStatus(200)
                  ->assertJson($genre->toArray())
                  ->assertJsonFragment([
                      'name'=>'teste',
                      'is_active'=> true
                  ]);


        $response = $this->json('PUT',
        route('genres.update',
        ['genre'=>$genre->id]),
        [
            'name'=>'teste',
            'is_active'=>true

        ]);
        $response
        ->assertJsonFragment([
           'is_active'=> true
        ]);
        }

        public function testDelete(){
            $genre = factory(Genre::class)->create([
                'is_active'=>false
            ]);
    
            $response = $this->json('DELETE',
                        route('genres.destroy',
                        ['genre'=>$genre->id]),
                        []);

           $response->assertStatus(204);
           $this->assertNull(Genre::find($genre->id));
           $this->assertNotNull(Genre::withTrashed()->find($genre->id));
        }

    protected function assertInvalidationRequired(TestResponse $response){
        $response->assertStatus(422)
                 ->assertJsonMissingValidationErrors(['is_active'])
                 ->assertJsonValidationErrors(['name'])
                 ->assertJsonFragment([
                     \Lang::get('validation.required',
                     ['attribute'=>'name'])
                 ])  ;
    }

    protected function assertInvalidationMax(TestResponse $response){
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
        //->assertJsonFragment([
        //        \Lang::get('validation.max.string',
         //       ['attribute'=>'name', 'max'=>255])
          //  ])  ;
           // ->assertJsonFragment([
           //     \Lang::get('validation.boolean',
            //    ['attribute'=>'is active'])
            //])  ;
    }
    protected function assertInvalidationBoolean(TestResponse $response){
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['is_active'])
            ->assertJsonFragment([
                \Lang::get('validation.max.string',
               ['attribute'=>'name', 'max'=>255])
            ])  ;
            //->assertJsonFragment([
            //    \Lang::get('validation.boolean',
            //    ['attribute'=>'is active'])
           // ])  ;
    }
}
