<!-- BEGIN: Vendor JS-->

@vite(['resources/assets/vendor/libs/jquery/jquery.js', 'resources/assets/vendor/libs/popper/popper.js', 'resources/assets/vendor/js/bootstrap.js', 'resources/assets/vendor/libs/node-waves/node-waves.js'])

@vite(['resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js', 'resources/assets/vendor/js/menu.js'])

@yield('vendor-script')
<!-- END: Page Vendor JS-->
<!-- BEGIN: Theme JS-->
@vite(['resources/assets/js/main.js'])

<!-- END: Theme JS-->
<!-- Pricing Modal JS-->
@stack('pricing-script')
<!-- END: Pricing Modal JS-->
<!-- BEGIN: Page JS-->
@yield('page-script')
<!-- END: Page JS-->

<!-- app JS -->
@vite(['resources/js/app.js'])
<script src="{{ asset('assets/js/table-to-csv.js') }}"></script>
<!-- END: app JS-->
<!-- Global Toast Notification Logic -->
<script>
window.showToast = function(message, type = 'success') {
    const toastEl = document.getElementById('globalToast');
    const toastMessage = document.getElementById('globalToastMessage');
    if (!toastEl || !toastMessage) return;

    // Limpiar clases previas
    toastEl.classList.remove('bg-success', 'bg-danger', 'bg-warning', 'bg-info', 'bg-primary');
    
    // Añadir clase de color
    toastEl.classList.add('bg-' + type);
    
    // Inyectar mensaje
    toastMessage.textContent = message;
    
    // Mostrar Toast (Bootstrap 5)
    const toast = new bootstrap.Toast(toastEl, { delay: 3500 });
    toast.show();
}
</script>
<!-- END: Global Toast -->
