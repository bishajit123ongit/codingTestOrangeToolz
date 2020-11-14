@extends('layouts.app')

@section('content')
<div class="card card-default">
	<div class="card-header">
		  Create Plan
	</div>

    @if ($errors->any())
       <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
       </div>
     @endif

	<div class="card-body">
		<form action="{{route('plans.store')}}" method="POST">
        @csrf
			<div class="form-group">
				<label for="name">Name</label>
				<input type="text" name="name" class="form-control">
			</div>

            <div class="form-group">
				<label for="slug">Slug</label>
				<input type="text" name="slug" class="form-control">
			</div>

            <div class="form-group">
				<label for="stripe_plan">Stripe Plan</label>
				<input type="text" name="stripe_plan" class="form-control">
			</div>

            <div class="form-group">
				<label for="cost">Cost</label>
				<input type="number" name="cost" class="form-control">
			</div>

            <div class="form-group">
				<label for="description">Description</label>
                <textarea class="form-control" name="description" id="description" cols="10" rows="5"></textarea>
				
			</div>

			<div class="form-group">
				<button class="btn btn-success">
						Add Plan
			  </button>
			</div>

		</form>
	</div>
</div>

@endsection

