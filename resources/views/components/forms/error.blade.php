@props(['name'])

@error($attributes->get('name')) 
    <p class="error"> {{ $message }} </p>
@enderror