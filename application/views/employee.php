<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            Employees
            <a href="#" class="btn btn-primary btn-xs pull-right add_employee"><i class="icon-fixed-width icon-plus"></i> Add New Employee</a>
        </div>
        <div class="panel-body">
            <table id="employee_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                        <tr>
                        <th style="width: 7%;">
                            <input type="checkbox" id="checkAll"> 
                            <a href="#" id="bulk_delete" class="btn btn-xs btn-danger pull-right" style="display: none; margin-left: 20px">
                                <i class="icon-fixed-width icon-trash"></i>
                            </a>
                        </th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Created By</th>
                        <th>Created At</th>
                        <th>Modified At</th>
                        <th style="width: 20%;">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="employee_modal" tabindex="-1" role="dialog" aria-labelledby="employee_modal_title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="employee_form">
                <div class="modal-header">
                    <h5 class="modal-title" id="employee_modal_title"><span id="form_action"></span> Employee</h5>
                    <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close" style="margin-top: -20px;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="employee_id" name="employee_id">
                    <div class="form-group">
                        <label for="employee_first_name">First Name</label>
                        <input type="text" class="form-control" id="employee_first_name" name="employee_first_name" aria-describedby="employee_first_name" placeholder="Enter first name">
                    </div>
                    <div class="form-group">
                        <label for="employee_last_name">Last Name</label>
                        <input type="text" class="form-control" id="employee_last_name" name="employee_last_name" aria-describedby="employee_last_name" placeholder="Enter last name">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-xs"><i class="icon-fixed-width icon-save"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- OR Modal -->
<div class="modal fade" id="qr_modal" tabindex="-1" role="dialog" aria-labelledby="qr_modal_title" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title" id="employee_modal_title">QR Code</h5>
                <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close" style="margin-top: -20px;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div id="qrcode"></div>
            </div>
        </div>
    </div>
</div>