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

        if ($status === 'dataupdate') {
            echo "
                <script>
                    Swal.fire({
                    icon: 'success',
                    title: 'Updated',
                    text: 'Data Updated Successfully',
                    showConfirmButton: false,
                    timer: 2500
                    });
    
                    // Clear the URL after the success message
                    window.history.replaceState(null, null, window.location.pathname);
                </script>
            ";
        }


        if ($status === 'dataerror') {
            echo "
                <script>
                    Swal.fire({
                    icon: 'error',
                    title: 'Submition Error',
                    text: 'Check the data you have entered',
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