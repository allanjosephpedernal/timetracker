        </div>
        <!-- CONTAINER /-->
        
        <!-- SCRIPT -->
        <script src="/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="/bower_components/jquery-ui/jquery-ui.min.js"></script>
        <script src="/bower_components/jquery-validation/dist/jquery.validate.min.js"></script>
        <script src="/bower_components/jquery-validation/dist/additional-methods.min.js"></script>
        <script src="/bower_components/jquery-loading/dist/jquery.loading.min.js"></script>
        <script src="/bower_components/jquery-toast-plugin/dist/jquery.toast.min.js"></script>
        <script src="/bower_components/bootstrap-sweetalert/dist/sweetalert.min.js"></script>
        <script src="/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
        <script src="/bower_components/jquery-qrcode/jquery.qrcode.min.js"></script>
        <?php if(isset($custom_scripts)&&is_array($custom_scripts)&&COUNT($custom_scripts)): ?>
            <?php foreach($custom_scripts AS $script): ?>
                <script src="<?php echo $script; ?>"></script>
            <?php endforeach; ?>
        <?php endif; ?>
    </body>
</html>