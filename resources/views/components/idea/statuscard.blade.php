@props ([
    'status' => 'pending',
])


@php
    $classes = 'inline-block text-xs mt-2 border px-2 py-1 rounded-full';

    if($status === 'pending'){

        $classes .= ' bg-yellow-500/10 text-yellow-500 border-yellow-500/20';
    }
    elseif ($status === 'completed') {
        $classes .= ' bg-green-500/10 text-green-500 border-green-500/20';
    }
    elseif ($status === 'in_progress') {
        $classes .= ' bg-blue-500/10 text-blue-500 border-blue-500/20';
    }


@endphp          
           
           

<span {{ $attributes(['class' => $classes]) }}>
                     {{ $slot }}

 </span>