<?php
function output_message()
{
    if (isset($_GET['status'])) {
        $status = $_GET['status'];

        if ($status === 'register') {
            echo "
                <script>
                    Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'User Registered',
                    showConfirmButton: false,
                    timer: 1500
                    });
    
                    // Clear the URL after the success message
                    window.history.replaceState(null, null, window.location.pathname);
                </script>
                  ";
        } else if ($status === 'failed') {
            echo "
            <script>
                    Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Canva Premium Request Failed. Try again!',
                    showConfirmButton: false,
                    timer: 4000
    
                    // Clear the URL after the success message
                    window.history.replaceState(null, null, window.location.pathname);
                    });
                </script>
          ";
        }
    }
}

?>