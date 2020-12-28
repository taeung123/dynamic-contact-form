
<form action="{{route('send')}}" method="POST" role="form">
    {!!$contact_form->renderContactForm()!!}
    <input type="text" hidden value="{{$contact_form}}">

    <button type="submit">Gửi</button>
</form>
