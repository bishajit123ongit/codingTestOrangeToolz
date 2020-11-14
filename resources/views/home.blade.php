@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                        
                    @endif
                    <table class="table">
                        <tbody>
                        <tr>
                            <td>Name</td>
                            <td>{{Auth::user()->name}}</td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td>{{Auth::user()->email}}</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>{{Auth::user()->status==1?'Active':'Inactive'}}</td>
                        </tr>
                        </tbody>
                    </table>
                        @if(Auth::user()->status!=1)
                        
                            
                            <a href="{{route('plans.create')}}"style="color:white;" class="btn btn-success">Activate</a>
                            
                        @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
