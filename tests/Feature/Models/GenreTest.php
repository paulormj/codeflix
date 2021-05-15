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
