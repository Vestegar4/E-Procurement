{{-- 1. Container Data (Hidden) --}}
@if(session()->has('success') || session()->has('error'))
    <div id="interaction-toast-data" 
         data-type="{{ session()->has('success') ? 'success' : 'error' }}" 
         data-message="{{ session('success') ?? session('error') }}" 
         style="display:none;">
    </div>
@endif

{{-- 2. Logic Script (Hanya jalan jika data ada) --}}
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toastElement = document.getElementById('interaction-toast-data');
            
            if (toastElement) {
                const type = toastElement.getAttribute('data-type');
                const message = toastElement.getAttribute('data-message');

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: type, // 'success' atau 'error'
                    title: message,
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true,
                    showCloseButton: true
                });
            }
        });
    </script>
@endpush