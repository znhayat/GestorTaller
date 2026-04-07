@php
$containerFooter = !empty($containerNav) ? $containerNav : 'container-fluid';
@endphp

<!-- Footer-->
<footer class="content-footer footer bg-footer-theme">
    <div class="{{ $containerFooter }}">
        <div class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
            <div class="text-body">
                &#169;
                <script>
                document.write(new Date().getFullYear())
                </script>, Gestor - Panel de Control
            </div>
            <div class="d-none d-lg-inline-block">
                <!-- Se han eliminado los enlaces del footer predeterminados -->
            </div>
        </div>
    </div>
</footer>
<!--/ Footer-->
