<div class="flex flex-col items-center justify-center h-screen">
    <h1 class="text-2xl font-bold">Facebook Login Successful</h1>
    <p class="mt-4">You will be redirected shortly...</p>

    <script>
        //window.opener.location.reload();  // Refresh the parent window
        window.opener.location.href = "{{ route('stepper') }}";
        window.close();  // Close the popup window
    </script>
    
</div>
