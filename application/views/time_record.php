<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            Time Record
            <a href="#" class="btn btn-primary btn-xs pull-right scan_qr"><i class="icon-fixed-width icon-search"></i> Scan QR</a>
        </div>
        <div class="panel-body">
            <table id="time_record_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Employee</th>
                        <th>User</th>
                        <th>Date Added</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- OR Reader Modal -->
<div class="modal fade" id="qr_modal" tabindex="-1" role="dialog" aria-labelledby="qr_modal_title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title" id="employee_modal_title">Scan QR Code</h5>
                <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close" style="margin-top: -20px;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <video id="preview" style="width: 100%;"></video>
            </div>
        </div>
    </div>
</div>