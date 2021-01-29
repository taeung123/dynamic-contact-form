<div class="form-group col-12 col-md-{{$input->column}} {{$input->slug}}">
    <label for="{{$input->slug}}">{{$input->label}}:</label>
    <input type="text" class="form-control" name='{{$input->slug}}' id="{{$input->slug}}"
        placeholder="{{$input->placeholder}}">
    <small id="{{$input->slug}}" class="form-text text-muted">
        {{$input->note}}
    </small>
</div>
