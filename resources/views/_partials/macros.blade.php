@php
  $width = $width ?? '40';
  $height = $height ?? '40';
@endphp

<span style="display: inline-flex; align-items: center; justify-content: center; width: {{ $width }}px; height: {{ $height }}px; border-radius: 50%; overflow: hidden; border: 2px solid #ff4e00; background: #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
    <!-- Reemplaza la imagen 'zana-logo.png' alojandola en tu carpeta public/assets/img/ -->
    <img src="{{ asset('assets/img/zana-logo.png') }}?v=2" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=Z&background=ff4e00&color=fff&rounded=true';" alt="Zana Logo" style="width: 100%; height: 100%; object-fit: cover;">
</span>
