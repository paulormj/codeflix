<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

abstract class BasicCrudController extends Controller
{

    protected abstract function model();
    protected abstract function rulesStore();

    
    public function index()
    {
        return $this->model()::all();
    }

    public function store(Request $request)
    {
        $validetedData =$this->validate($request,$this->rulesStore());
        $obj = $this->model()::create($validetedData);
        $obj->refresh();
        return $obj;
    }

    protected function findOrFail($id){
        $model = $this->model();
        $keyName=(new $model)->getRouteKeyName();
        return $this->model()::where($keyName,$id)->firstOrFail();
    }

    public function update(Request $request)
    {
        $validetedData =$this->validate($request,$this->rulesStore());
        $obj = $this->model()::update($validetedData);
        $obj->refresh();
        return $obj;
    }

    // public function show(Category $category)
    // {
    //     return $category;
    // }

    

     public function destroy()
    {
        $obj = $this->model()::delete();
        $obj->refresh();
        return $obj;
    }
}
