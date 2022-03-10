<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            Users
            <a href="#" class="btn btn-primary btn-xs pull-right add_user"><i class="icon-fixed-width icon-plus"></i> Add New User</a>
        </div>
        <div class="panel-body">
            <table id="user_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                        <tr>
                        <th style="width: 7%;">
                            <input type="checkbox" id="checkAll"> 
                            <a href="#" id="bulk_delete" class="btn btn-xs btn-danger pull-right" style="display: none; margin-left: 20px">
                                <i class="icon-fixed-width icon-trash"></i>
                            </a>
                        </th>
                        <th>Username</th>
                        <th>Type</th>
                        <th>Created At</th>
                        <th>Modified At</th>
                        <th style="width: 15%;">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="user_modal" tabindex="-1" role="dialog" aria-labelledby="user_modal_title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="user_form">
                <div class="modal-header">
                    <h5 class="modal-title" id="user_modal_title"><span id="form_action"></span> User</h5>
                    <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close" style="margin-top: -20px;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="user_id" name="user_id">
                    <div class="form-group">
                        <label for="user_name">Username</label>
                        <input type="text" class="form-control" id="user_name" name="user_name" aria-describedby="user_name" placeholder="Enter username">
                    </div>
                    <div class="form-group">
                        <label for="user_password">Password</label>
                        <input type="password" class="form-control" id="user_password" name="user_password" aria-describedby="user_password" placeholder="Enter password">
                    </div>
                    <div class="form-group">
                        <label for="user_type">Type</label>
                        <select name="user_type" id="user_type" class="form-control">
                            <option value=""></option>
                            <option value="1">Super Admin</option>
                            <option value="2">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-xs"><i class="icon-fixed-width icon-save"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>