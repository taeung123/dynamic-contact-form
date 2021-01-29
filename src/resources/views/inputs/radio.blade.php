<div class="radio  col-12 col-md-{{$input->column}} {{$input->slug}}">
    <label>{!!$input->label!!}<label>

            @if ($input->contactFormInputItems->count() > 0)
            @php
            $first = true;
            @endphp
            @foreach ($input->contactFormInputItems as $item)
            @if ($first)
            <label>
                <input type="radio" name=" {{$name}}" id="{{$item->slug}}" value="{{$item->value}}" checked>
                {!!$item->label!!}
            </label>
            @else
            <label>
                <input type="radio" name="{{$name}}" id="{{$item->slug}}" value="{{$item->value}}">
                {!!$item->label!!}
            </label>
            @endif
            @endforeach
            @endif
</div>
<small id="{{$input->slug}}" class="form-text text-muted">
    {!!$input->note!!}
</small>
