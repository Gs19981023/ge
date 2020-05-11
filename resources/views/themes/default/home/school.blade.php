@extends('theme::layout.public')


@section('content')



@foreach( $colleges as $college )
             <div>
                 <div class="college_name">
                     {{$college->name}}
                 </div>
                 <div class="college_name">
                     {{$college->desc}}
                 </div>
             </div>
  @endforeach
@endsection


<style>
    .college_name{
        color: red;
    }
</style>
