var employee_table;
var validator;
jQuery(function(){
    // init
    initValidate();
    initDatatables();
    
    // clear modal
    jQuery(document).on('hidden.bs.modal', '#employee_modal', function () {
        clearUserForm();
    });

    jQuery(document).on('hidden.bs.modal', '#qr_modal', function () {
        jQuery('#qrcode').html('');
    });

    // add new employee
    jQuery(document).on('click', '.add_employee', function(e){
        e.preventDefault();
        clearUserForm();
        jQuery('#form_action').text('Add');
        jQuery('#employee_modal').modal({ keyboard: false, backdrop: 'static' });
    });

    // add new employee
    jQuery(document).on('click', '.edit_employee', function(e){
        e.preventDefault();
        var employee_id = jQuery(this).attr('rel');
        jQuery.get('/employee/show/'+employee_id,function(res){
            clearUserForm();
            jQuery('#form_action').text('Edit');

            // set employee
            var employee = res.employee;
            jQuery('#employee_id').val(employee.id);
            jQuery("#employee_first_name").val(employee.first_name);
            jQuery("#employee_last_name").val(employee.last_name);

            // show modal
            jQuery('#employee_modal').modal({ keyboard: false, backdrop: 'static' });
        })
        .fail(function(res) {
            var res = JSON.parse(res.responseText);
            if(! res.success) {
                swal("Error!", res.message, "error");
            }
        });
    });

    // delete
    jQuery(document).on('click', '.delete_employee', function(e){
        e.preventDefault();
        var employee_id = jQuery(this).attr('rel');
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this employee!",
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
                jQuery.get('/employee/delete/'+employee_id,function(res){
                    if(res.success){
                        jQuery.toast({
                            heading: 'Success',
                            text: "Employee has been deleted!",
                            showHideTransition: 'plain',
                            icon: 'success'
                        });
                        employee_table.ajax.reload( null, false );
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

    // bulk delete
    jQuery(document).on('click', '#bulk_delete', function(e){
        e.preventDefault();

        var valid = true;
        var message = '';

        // validate checked
        var checked = jQuery('.chk:checked').length;
        if(checked == 0)
        {
            valid = false;
            message = 'Please select employee!';
            return false;
        }

        // validate account
        var ids = [];
        $('input[name="chk[]"]:checked').each(function () {
            ids[ids.length] = (this.checked ? jQuery(this).val() : "");
            if(employee_id === parseInt(jQuery(this).val()))
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
                text: "You will not be able to recover this employee"+(checked>1 ? 's': '')+"!",
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
                    jQuery.post('/employee/bulk_delete',{ids: ids},function(res){
                        if(res.success){
                            jQuery.toast({
                                heading: 'Success',
                                text: "User has been deleted!",
                                showHideTransition: 'plain',
                                icon: 'success'
                            });
                            employee_table.ajax.reload( null, false );
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
    jQuery(document).on('submit', '#employee_form', function(e){
        e.preventDefault();

        var employee_id = jQuery('#employee_id').val();
        var data = jQuery(this).serializeArray();
        var url = '/employee/update/'+employee_id;
        if(employee_id == '' || employee_id == undefined || employee_id == null) { url = '/employee/store'; }

        // ajax
        jQuery.post(url,data,function(res){
            if(res.success){
                jQuery.toast({
                    heading: 'Success',
                    text: 'New employee has been '+(employee_id ? 'updated' : 'added')+'!',
                    showHideTransition: 'plain',
                    icon: 'success'
                });
                jQuery('#employee_modal').modal('hide');
                employee_table.ajax.reload( null, false );
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

    // show qr
    jQuery(document).on('click','.show_qr',function(){
        var employee_id = jQuery(this).attr('rel');
        jQuery('#qrcode').qrcode({width: 260, height: 260, text: employee_id});
        jQuery('#qr_modal').modal({ keyboard: false, backdrop: 'static' });
    });
});

// init validate
function initValidate()
{
    // set validator
    validator = jQuery("#employee_form").validate({
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
    jQuery("#employee_first_name").rules("add", { required: true });
    jQuery("#employee_last_name").rules("add", { required: true });
}

// init datatables
function initDatatables()
{
    // init datatbles
    employee_table = jQuery('#employee_table').DataTable({
        processing: true,
        serverSide: true,
        order: [],
        ajax: {
            url: "/employee/datatable",
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

// clear employee form
function clearUserForm()
{
    jQuery('#form_action').text('');
    jQuery('#employee_id').val('');
    jQuery('#employee_first_name').val('');
    jQuery('#employee_last_name').val('');

    validator.resetForm();
    jQuery('.form-group').removeClass('has-error');
    jQuery('.form-group').removeClass('has-success');
}
