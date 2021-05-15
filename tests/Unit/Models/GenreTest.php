<?php

namespace Tests\Unit\Models;

use App\Models\Genre;
use PHPUnit\Framework\TestCase;

class GenreTest extends TestCase
{
    private $genre;

    protected function setUp(): void
    {
        parent::setUp();
        $this->genre= new Genre();
    }

    public function testFillableAttribute(){
        $fillable = ['name','is_active'];
        $this->assertEquals($fillable, $this->genre->getFillable());
    }

    public function testIfUseTraits(){
        
        $traits = [
            "Illuminate\Database\Eloquent\SoftDeletes","App\Models\Traits\Uuid"
        ];
        $genreTraits = array_keys(class_uses(Genre::class));
        $this->assertEquals($traits,$genreTraits);
        
    }
    public function testCasts(){
        $casts = ['id'=>'string','is_active' => 'boolean'];
        $this->assertEquals($casts,$this->genre->getCasts());        
    }

    public function testIncrementing(){
        $this->assertFalse($this->genre->getIncrementing());        
    }

    public function testDates(){
        $dates = ['deleted_at','updated_at','created_at'];
        foreach ($dates as $date){
            $this->assertContains($date, $this->genre->getDates());
        } 
        $this->assertCount(count($dates),$this->genre->getDates());
    }

}

    
