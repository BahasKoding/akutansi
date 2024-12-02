@if(session()->has('success') || session()->has('error'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        title: '{{ session()->has("success") ? "Berhasil!" : "Error!" }}',
        text: '{{ session("success") ?? session("error") }}',
        icon: '{{ session("type") ?? (session()->has("success") ? "success" : "error") }}',
        timer: 3000,
        timerProgressBar: true,
        showConfirmButton: false
    });
});
</script>
@endif 
