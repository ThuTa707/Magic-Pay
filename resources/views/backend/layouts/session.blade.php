
{{-- Toast --}}

@if (session('toast'))
    <script>

        let toast = @json(session('toast'));

        const Toast = Swal.mixin({
            toast: true,
            position: 'bottom-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })


        Toast.fire({
            icon: toast.icon,
            title: toast.title,
        })
    </script>
@endif

{{-- Toast --}}
