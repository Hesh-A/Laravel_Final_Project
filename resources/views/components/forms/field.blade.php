@props ([
    'label',
    'name',
    'type',
])



<div class="space-y-2">
    <label for="{{$name}}" class="label">{{$label}}</label>
    @if($type === 'textarea')
        <textarea  name="{{$name}}" id="{{$name}}"
                   class="textarea"{{ $attributes }}
        >{{old($name)}}</textarea>
    @else
    <input type="{{$type}}" class="input" id="{{$name}}" name="{{$name}}" value="{{old($name)}}" {{ $attributes }}>
    @endif

    <x-forms.error name="{{$name}}" />
</div>