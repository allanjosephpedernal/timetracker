var time_record_table;
var scanner;
jQuery(function(){
    // init
    initDatatables();

    // clear modal
    jQuery(document).on('hidden.bs.modal', '#qr_modal', function () {
        scanner.stop();
    })

    // show scan QR
    jQuery(document).on('click', '.scan_qr', function(){
        initScanner();
        jQuery('#qr_modal').modal({ keyboard: false, backdrop: 'static' });
    });
});

// init datatables
function initDatatables()
{
    // init datatbles
    time_record_table = jQuery('#time_record_table').DataTable({
        processing: true,
        serverSide: true,
        order: [],
        ajax: {
            url: "/timerecord/datatable",
            type: "POST"
        },
        columnDefs: [
            { 
                targets: [ 0 ],
                orderable: false, 
            },
        ]
    });
}

function initScanner()
{
    scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
    scanner.addListener('scan', function (content) {
      var employee_id = content;

      // get employee details
      jQuery.get('/employee/show/'+employee_id,function(res){
        if(res.success)
        {
            var employee = res.employee;
            var first_name = employee.first_name;
            var last_name = employee.last_name;
            var full_name = first_name +' '+ last_name;

            // capture time record employee
            jQuery.get('/timerecord/log/'+employee_id,function(res){
                if(res.success) {
                    var time_record = res.time_record;

                    var date_added = time_record.date_added;
                    var time_in = time_record.time_in;
                    var time_out = time_record.time_out;

                    // set message
                    var message = '<br /> Date Added: '+date_added+' <br /> Time-Out: ' + time_out;
                    if(time_out == null){ message = ' <br /> Date Added: '+date_added+' <br /> Time-In: ' + time_in; }

                    jQuery.toast({
                        heading: 'Success',
                        enableHtml: true,
                        text: "Hello "+full_name+" "+message+"!",
                        showHideTransition: 'plain',
                        icon: 'success'
                    });

                    time_record_table.ajax.reload( null, false );
                    jQuery('#qr_modal').modal('hide');
                }
                else { 
                    swal("Error!", 'Please contact your customer support!', "error"); 
                }
            })
            .fail(function(res) {
                swal("Error!", 'Invalid QR Code', "error");
            });
        }
        else { 
            swal("Error!", 'Please contact your customer support!', "error"); 
        }
      })
      .fail(function(res) {
        swal("Error!", 'Invalid QR Code', "error");
      });
    });
    Instascan.Camera.getCameras().then(function (cameras) {
      if (cameras.length > 0) {
        scanner.start(cameras[0]);
      } else {
        console.error('No cameras found.');
      }
    }).catch(function (e) {
      console.error(e);
    });
}
