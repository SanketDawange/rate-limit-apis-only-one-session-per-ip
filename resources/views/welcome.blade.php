<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div id="statusMessage" class="mt-4">Rate Limit APIs</div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $.ajax({
                type: 'GET',
                url: '/api/test',
                success: function(response) {
                    if (response.error !== undefined) {
                        $('#statusMessage').html('<div class="alert alert-success" role="alert">' +
                            response.error + '</div>').show();
                    } else if (response.message !== undefined) {
                        $('#statusMessage').html('<div class="alert alert-success" role="alert">' +
                            response.message + '</div>').show();
                    }
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    }
                    $('#statusMessage').html('<div class="alert alert-danger" role="alert">Error: ' +
                        errorMessage + '</div>').show();
                }
            });
        });
    </script>
</body>

</html>
