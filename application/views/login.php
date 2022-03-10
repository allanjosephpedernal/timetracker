<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- META DATA -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- TITLE -->
        <title>Time Tracker</title>

        <!-- STYLES -->
        <link href="/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
        <link href="/bower_components/bootstrap-sweetalert/dist/sweetalert.css" rel="stylesheet">
        <link href="/assets/css/style.css" rel="stylesheet">
    </head>
    <body>
        <!-- CONTAINER -->
        <div class="container">

            <!-- LOGIN FORM -->
            <div class="row">
                <div class="col-lg-4 col-lg-offset-4">
                    <div id="login_panel" class="panel panel-primary">
                        <div class="panel-heading">Login</div>
                        <div class="panel-body">
                            <form id="login_form">
                                <div class="form-group">
                                    <label for="user_name">Username</label>
                                    <input type="user_name" class="form-control" id="user_name" name="user_name" placeholder="Enter username">
                                </div>
                                <div class="form-group">
                                    <label for="user_password">Password</label>
                                    <input type="password" class="form-control" id="user_password" name="user_password" placeholder="Enter password">
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox"> Remember Me
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                    <p class="text-center">Username: super_admin | Password: password</p>
                </div>
            </div>
            <!-- LOGIN FORM /-->

        </div>
        <!-- CONTAINER /-->
        
        <!-- SCRIPT -->
        <script src="/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="/bower_components/bootstrap-sweetalert/dist/sweetalert.min.js"></script>
        <script type="text/javascript">
            jQuery(function(){
                jQuery(document).on('submit', '#login_form', function(e){
                    e.preventDefault();
                    var data = jQuery('#login_form').serializeArray();
                    jQuery.post('/authenticate/login',data,function(res){
                        if(res.success){ window.location.href = '/dashboard'; }
                        else { 
                            swal('Please contact your customer support!');
                        }
                    })
                    .fail(function(res) {
                        var res = JSON.parse(res.responseText);
                        if(! res.success) {
                            swal("Error!", res.message, "error");
                        }
                    });
                });
            });
        </script>
    </body>
</html>