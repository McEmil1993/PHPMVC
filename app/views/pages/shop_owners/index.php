<?php ob_start(); ?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= $data['title']  ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><?= $data['title']  ?></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <button type="button" class="btn btn-primary btn-sm new_owner" >New shop owner</button>

                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Full name</th>
                                    <th>Shop name</th>
                                    <th>Contact</th>
                                    <th>Status</th>
                                    <th style="text-align: center;" width="15%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Other browsers</td>
                                    <td>All others</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td style="text-align: center;">
                                        <!-- Apply text-align:center to center the buttons -->
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-info">Edit</button>
                                            <button type="button" class="btn btn-sm  btn-danger">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Full name</th>
                                    <th>Shop name</th>
                                    <th>Contact</th>
                                    <th>Status</th>
                                    <th style="text-align: center;">Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>

<div id="default_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Default Modal</h4>
            </div>
            <div class="modal-body">
                <p>One fine body&hellip;</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script>
$('#shops').addClass('active');
$('.new_owner').click(function () {
    $('#default_modal').modal('show');
});
</script>

<?php
 $content = ob_get_clean(); 
 $title = $data['title'];
 ?>
<?php require_once '../app/views/layouts/main.php'; ?>