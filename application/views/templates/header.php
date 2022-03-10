<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- META DATA -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo $this->security->get_csrf_hash(); ?>">

        <!-- TITLE -->
        <title>Time Tracker</title>

        <!-- STYLES -->
        <link href="/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
        <link href="/bower_components/bootstrap-sweetalert/dist/sweetalert.css" rel="stylesheet">
        <link href="/bower_components/jquery-loading/dist/jquery.loading.min.css" rel="stylesheet">
        <link href="/bower_components/jquery-toast-plugin/dist/jquery.toast.min.css" rel="stylesheet">
        <link href="/bower_components/Font-Awesome/css/font-awesome.min.css" rel="stylesheet">
        <link href="/assets/css/style.css" rel="stylesheet">

        <script type="text/javascript">
            var user_id = <?php echo $this->session->user_id; ?>;
        </script>
    </head>
    <body>

        <!-- HEADER -->
        <?php include('navigation.php'); ?>

        <!-- CONTAINER -->
        <div class="container-fluid">
