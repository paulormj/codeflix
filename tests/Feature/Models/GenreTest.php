<?php

namespace Tests\Feature\Models;


use App\Models\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GenreTest extends TestCase
{
  use DatabaseMigrations;
  
  public function testList(){

    factory(Genre::class,1)->create();
    $genre = Genre::all();
    $this->assertCount(1,$genre);

    $genreKey = array_keys($genre->first()->getAttributes());
        $this->assertEqualsCanonicalizing(
            [
                'id', 'name', 'is_active', 'created_at', 'updated_at', 'deleted_at'
            ],
            $genreKey
        );
  }

  public function testCreate(){
      $genre = Genre::create([
          'name'=>'documentario'
      ]);
      $genre->refresh();
      $this->assertNotNull($genre->id);
      $this->assertEquals($genre->name, 'documentario');
      $this->assertTrue($genre->is_active);


      $genre = Genre::create([
        'name'=>'documentario',
        'is_active' => false
    ]);
      $this->assertFalse($genre->is_active);

      $this->assertEquals(36, strlen($genre->id)); 
      
      $genre = Genre::create([
        'name' => 'test1', 'is_active' => true
    ]);
    $this->assertTrue($genre->is_active);
  }

  public function testUpdate(){
    /**@var Genre $genre  */
    $genre = factory(Genre::class)->create([
        'name'=>'teste',
        'is_active'=>false
    ]);
    $data = ['name'=>'test_name_updated' , 'is_active'=>true];
    $genre->update($data);

    $this->assertEquals($genre->name,'test_name_updated');
    $this->assertTrue($genre->is_active);


  }
  public function testDelete(){
    /**@var Genre $genre  */
    $genre = factory(Genre::class)->create([
        'name'=>'teste',
        'is_active'=>false
    ]);
    $data = ['name'=>'test_name_updated' , 'is_active'=>true];
    $genre->delete($data);
    $this->assertNotNull($genre->deleted_at);
    


  }
    
}
