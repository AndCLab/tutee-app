<div class="flex flex-col items-center justify-center h-screen">
    <h1 class="text-2xl font-bold">Google Login Successful</h1>
    <p class="mt-4">You will be redirected shortly...</p>

    <script>
        // Close the modal and redirect the main window
        window.opener.location.href = "{{ route('stepper') }}";
        window.close(); // Close the current Google login window
    </script>
</div>
