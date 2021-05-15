<?php

namespace Tests\Unit\Models;

use App;
use App\Models\Category;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    
    private $category;

    //SetUp é executado antes de cada teste.
    protected function setUp(): void
    {
        parent::setUp();
        $this->category = new Category();
        
    }

    public function testFillableAttribute()
    {
        $fillable= ['name','description','is_active'];
        $this->assertEquals($fillable,$this->category->getFillable());
    }

    public function testIfUseTraits(){
        
        $traits = [
            "Illuminate\Database\Eloquent\SoftDeletes","App\Models\Traits\Uuid"
        ];
        $categoryTraits = array_keys(class_uses(Category::class));
        $this->assertEquals($traits,$categoryTraits);
        
    }

    public function testCasts(){
        $casts = ['id'=>'string','is_active' => 'boolean'];
        $this->assertEquals($casts,$this->category->getCasts());        
    }

    public function testIncrementing(){
        $this->assertFalse($this->category->getIncrementing());        
    }

    public function testDates(){
        $dates = ['deleted_at','updated_at','created_at'];
        foreach ($dates as $date){
            $this->assertContains($date, $this->category->getDates());
        } 
        $this->assertCount(count($dates),$this->category->getDates());
    }


}
