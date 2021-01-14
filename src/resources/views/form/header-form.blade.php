<form action="{{route('send')}}" method="POST" role="form" class="{{$contact_form->slug}}">
    <legend>{{$contact_form->name}}</legend>
    <input name="contact_form_id" value="{{$contact_form->id}}" hidden></input>
