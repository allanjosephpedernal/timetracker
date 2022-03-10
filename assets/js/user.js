var user_table;
var validator;
jQuery(function(){
    // init
    initValidate();
    initDatatables();

    // clear modal
    jQuery(document).on('hidden.bs.modal', '#user_modal', function () {
        clearUserForm();
    })

    // add new user
    jQuery(document).on('click', '.add_user', function(e){
        e.preventDefault();
        clearUserForm();
        jQuery('#form_action').text('Add');
        jQuery("#user_password").rules("add", { required: true, minlength: 10, pwcheck: true });
        jQuery('#user_modal').modal({ keyboard: false, backdrop: 'static' });
    });

    // add new user
    jQuery(document).on('click', '.edit_user', function(e){
        e.preventDefault();
        var user_id = jQuery(this).attr('rel');
        jQuery.get('/user/show/'+user_id,function(res){
            clearUserForm();
            jQuery('#form_action').text('Edit');
            jQuery("#user_password").rules("remove");

            // set user
            var user = res.user;
            jQuery('#user_id').val(user.id);
            jQuery("#user_name").val(user.user_name);
            jQuery("#user_type").val(user.user_type);

            // show modal
            jQuery('#user_modal').modal({ keyboard: false, backdrop: 'static' });
        })
        .fail(function(res) {
            var res = JSON.parse(res.responseText);
            if(! res.success) {
                swal("Error!", res.message, "error");
            }
        });
    });

    // delete
    jQuery(document).on('click', '.delete_user', function(e){
        e.preventDefault();
        var user_id = jQuery(this).attr('rel');
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this user!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes',
            cancelButtonText: "No",
            closeOnConfirm: true,
            closeOnCancel: true,
         },
         function(isConfirm){
            if (isConfirm){
                jQuery.get('/user/delete/'+user_id,function(res){
                    if(res.success){
                        jQuery.toast({
                            heading: 'Success',
                            text: "User has been deleted!",
                            showHideTransition: 'plain',
                            icon: 'success'
                        });
                        user_table.ajax.reload( null, false );
                    }
                    else { 
                        swal("Error!", 'Please contact your customer support!', "error"); 
                    }
                })
                .fail(function(res) {
                    var res = JSON.parse(res.responseText);
                    if(! res.success) {
                        swal("Error!", res.message, "error");
                    }
                });
                
            }
         });
    });

    jQuery(document).on('click', '#bulk_delete', function(e){
        e.preventDefault();

        var valid = true;
        var message = '';

        // validate checked
        var checked = jQuery('.chk:checked').length;
        if(checked == 0)
        {
            valid = false;
            message = 'Please select user!';
            return false;
        }

        // validate account
        var ids = [];
        $('input[name="chk[]"]:checked').each(function () {
            ids[ids.length] = (this.checked ? jQuery(this).val() : "");
            if(user_id === parseInt(jQuery(this).val()))
            {
                valid = false;
                message = 'You are not allowed to delete your account!';
                return false;
            }
        });

        if(valid)
        {
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this user"+(checked>1 ? 's': '')+"!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes',
                cancelButtonText: "No",
                closeOnConfirm: true,
                closeOnCancel: true,
            },
            function(isConfirm){
                if (isConfirm){
                    jQuery.post('/user/bulk_delete',{ids: ids},function(res){
                        if(res.success){
                            jQuery.toast({
                                heading: 'Success',
                                text: "User has been deleted!",
                                showHideTransition: 'plain',
                                icon: 'success'
                            });
                            user_table.ajax.reload( null, false );
                            $("#checkAll").prop("checked", false);
                        }
                        else { 
                            swal("Error!", 'Please contact your customer support!', "error"); 
                        }
                    })
                    .fail(function(res) {
                        var res = JSON.parse(res.responseText);
                        if(! res.success) {
                            swal("Error!", res.message, "error");
                        }
                    });
                }
            });
        }
        else
        {
            swal("Error!", message, "error");
        }
    });

    // submit form
    jQuery(document).on('submit', '#user_form', function(e){
        e.preventDefault();

        var user_id = jQuery('#user_id').val();
        var data = jQuery(this).serializeArray();
        var url = '/user/update/'+user_id;
        if(user_id == '' || user_id == undefined || user_id == null) { url = '/user/store'; }

        // ajax
        jQuery.post(url,data,function(res){
            if(res.success){
                jQuery.toast({
                    heading: 'Success',
                    text: 'New user has been '+(user_id ? 'updated' : 'added')+'!',
                    showHideTransition: 'plain',
                    icon: 'success'
                });
                jQuery('#user_modal').modal('hide');
                user_table.ajax.reload( null, false );
            }
            else { 
                swal("Error!", 'Please contact your customer support!', "error"); 
            }
        })
        .fail(function(res) {
            var res = JSON.parse(res.responseText);
            if(! res.success) {
                swal("Error!", res.message, "error"); 
            }
        });

    });

    // checkall
    jQuery(document).on('change', "#checkAll", function(e){
        var status = $(this).is(":checked") ? true : false;
        $(".chk").prop("checked",status);
    });

    // chk
    jQuery(document).on('change', ".chk", function(e){
        var total = jQuery('.chk').length;
        var checked = jQuery('.chk:checked').length;

        $("#checkAll").prop("checked", false);
        if(total == checked){ $("#checkAll").prop("checked", true); }
    });

    // check all and chk
    jQuery(document).on('change', "#checkAll, .chk", function(e){
        var checked = jQuery('.chk:checked').length;
        if(checked) { jQuery('#bulk_delete').show(); }
        else { jQuery('#bulk_delete').hide(); }
    });
});

// init validate
function initValidate()
{
    // set validator message pwcheck
    jQuery.validator.messages.pwcheck = 'Password must contain lowercase, uppercase, number, and special character.';

    // pwcheck
    jQuery.validator.addMethod("pwcheck", function(value) {
        return /^.*(?=.{8,})(?=.*[a-z])(?=.*[A-Z])(?=.*[\d])(?=.*[\W_]).*$/.test(value);
    });

    // set validator
    validator = jQuery("#user_form").validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        highlight: function (element, errorClass, validClass) {
            if (element.type === "radio") {
                this.findByName(element.name).addClass(errorClass).removeClass(validClass);
            } else {
                jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
            }
        },
        unhighlight: function (element, errorClass, validClass) {
            if (element.type === "radio") {
                this.findByName(element.name).removeClass(errorClass).addClass(validClass);
            } else {
                jQuery(element).closest('.form-group').removeClass('has-error').addClass('has-success');
            }
        }
    });

    // add validation
    jQuery("#user_name").rules("add", { required: true });
    jQuery("#user_type").rules("add", { required: true });
}

// init datatables
function initDatatables()
{
    // init datatbles
    user_table = jQuery('#user_table').DataTable({
        processing: true,
        serverSide: true,
        order: [],
        ajax: {
            url: "/user/datatable",
            type: "POST"
        },
        columnDefs: [
            { 
                targets: [ 0, -1 ],
                orderable: false, 
            },
        ]
    });
}

// clear user form
function clearUserForm()
{
    jQuery('#form_action').text('');
    jQuery('#user_id').val('');
    jQuery('#user_name').val('');
    jQuery('#user_password').val('');
    jQuery('#user_type').val('');

    validator.resetForm();
    jQuery('.form-group').removeClass('has-error');
    jQuery('.form-group').removeClass('has-success');
}
