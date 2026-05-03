@php
  $width = $width ?? '40';
  $height = $height ?? '40';
@endphp

<span style="display: inline-flex; align-items: center; justify-content: center; width: {{ $width }}px; height: {{ $height }}px; overflow: hidden; background: transparent;">
    <img src="{{ asset('assets/img/zana-logo.jpg') }}?v={{ time() }}" 
         onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=Z&background=ff4e00&color=fff&rounded=true';" 
         alt="Zana Logo" 
         style="width: 100%; height: 100%; object-fit: contain;">
</span>
