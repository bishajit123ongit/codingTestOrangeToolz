<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Plan;

class PlanController extends Controller
{

public function index()
{
    $plans = Plan::all();
    return view('plans.index', compact('plans'));
}

public function show(Plan $plan, Request $request)
{
     return view('plans.show', compact('plan'));
}

public function create(){
    return view('plans.create');
}

public function store(Request $request){
    $plan = Plan::create([
     'name'=>$request->name,
     'slug'=>$request->slug,
     'stripe_plan'=>$request->stripe_plan,
     'cost'=>$request->cost,
     'description'=>$request->description,
    ]);
    return redirect(route('plans.index'));
}

}
