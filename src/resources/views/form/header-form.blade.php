<form action="{{route('send')}}" enctype="multipart/form-data" method="POST" role="form"
    class="row {{$contact_form->slug}}">
    <legend>{!!$contact_form->name!!}</legend>
    <input name="contact_form_id" value="{{$contact_form->id}}" hidden></input>
