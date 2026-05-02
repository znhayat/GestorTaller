<!-- BEGIN: Theme CSS-->
<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap" rel="stylesheet" />

<!-- Fonts Icons -->
@vite(['resources/assets/vendor/fonts/iconify/iconify.css'])

<!-- BEGIN: Vendor CSS-->
@vite(['resources/assets/vendor/libs/node-waves/node-waves.scss'])


<!-- Core CSS -->
@vite(['resources/assets/vendor/scss/core.scss', 'resources/assets/css/demo.css'])

<!-- Vendor Styles -->
@vite('resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.scss')
@yield('vendor-style')

<!-- Page Styles -->
@yield('page-style')

<!-- app CSS -->
<style>
  @media (max-width: 768px) {
      input, select, textarea, .form-control { font-size: 16px !important; }
      .table-responsive { border: 0 !important; -webkit-overflow-scrolling: touch; }
      .table th, .table td { padding: 0.75rem 0.5rem !important; font-size: 0.85rem; }
  }
</style>
@vite(['resources/css/app.css'])
<!-- END: app CSS-->