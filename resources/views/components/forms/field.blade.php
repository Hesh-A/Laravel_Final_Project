@props ([
    'label',
    'name',
    'type',
])



<div class="space-y-2">
         <label for="{{ $name }}" class="label"> {{  $label }} </label>
         <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}" class="input" value="{{ old($name) }}" /> 

         @error($name)
            <p class="error"> {{ $message }} </p>

         @enderror
</div>