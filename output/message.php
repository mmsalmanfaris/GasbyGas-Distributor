<?php
function message_success()
{
    if (isset($_GET['status'])) {
        $status = $_GET['status'];

        if ($status === 'datasuccess') {
            echo "
                <script>
                    Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Data Insert Successfull',
                    showConfirmButton: false,
                    timer: 2500
                    });
    
                    // Clear the URL after the success message
                    window.history.replaceState(null, null, window.location.pathname);
                </script>
            ";
        }

        if ($status === 'datadelete') {
            echo "
                <script>
                    Swal.fire({
                    icon: 'error',
                    title: 'Deleted',
                    text: 'Data Deleted Successfully',
                    showConfirmButton: false,
                    timer: 2500
                    });
    
                    // Clear the URL after the success message
                    window.history.replaceState(null, null, window.location.pathname);
                </script>
            ";
        }

    }
}

?>