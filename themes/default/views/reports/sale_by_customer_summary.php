<?php

$v = "";
if ($this->input->post('product')) {
    $v .= "&product=" . $this->input->post('product');
}
if ($this->input->post('reference_no')) {
    $v .= "&reference_no=" . $this->input->post('reference_no');
}
if ($this->input->post('customer')) {
    $v .= "&customer=" . $this->input->post('customer');
}
if ($this->input->post('biller')) {
    $v .= "&biller=" . $this->input->post('biller');
}
if ($this->input->post('warehouse')) {
    $v .= "&warehouse=" . $this->input->post('warehouse');
}
if ($this->input->post('user')) {
    $v .= "&user=" . $this->input->post('user');
}
if ($this->input->post('serial')) {
    $v .= "&serial=" . $this->input->post('serial');
}

if (isset($biller_id)) {
    $v .= "&biller_id=" . $biller_id;
}
$pro_code1='';
if($_GET['p_c']){
    $pro_code1=$_GET['p_c'];
}
?>

<script type="text/javascript">
    $(document).ready(function () {
        $('#form').hide();
        <?php if ($this->input->post('customer')) { ?>
        $('#customer').val(<?= $this->input->post('customer') ?>).select2({
            minimumInputLength: 1,
            data: [],
            initSelection: function (element, callback) {
                $.ajax({
                    type: "get", async: false,
                    url: site.base_url + "customers/suggestions/" + $(element).val(),
                    dataType: "json",
                    success: function (data) {
                        callback(data.results[0]);
                    }
                });
            },
            ajax: {
                url: site.base_url + "customers/suggestions",
                dataType: 'json',
                quietMillis: 15,
                data: function (term, page) {
                    return {
                        term: term,
                        limit: 10
                    };
                },
                results: function (data, page) {
                    if (data.results != null) {
                        return {results: data.results};
                    } else {
                        return {results: [{id: '', text: 'No Match Found'}]};
                    }
                }
            };
        $('#customer').val(<?= $this->input->post('customer') ?>);
    })


        <?php } ?>

        $('#form').hide();
        $('.toggle_down').click(function () {
            $("#form").slideDown();
            return false;
        });
        $('.toggle_up').click(function () {
            $("#form").slideUp();
            return false;
        });
    });
</script>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sale by item Detail</title>

    <link href="<?php echo $assets ?>styles/bootstrap.min.css" rel="stylesheet">


    <style>

        .header tr{
            width: 100%;
            text-align: center;

        }

        .line_op{
            position: relative;
            text-align: right;
            font-weight: bold;
        }
        .line_op:after{
            position: absolute;
            content: '';
            width: 80%;
            border-top: 1px solid black ;
            top: 0px;
            right: 0px;
        }
        .lp{
            position: relative;
            text-align: right;
            font-weight: bold;
        }
        .lp:after{
            position: absolute;
            content: '';
            width: 80%;
            /*border-bottom: 3px double black ;*/
            /*buttom: -150px;*/
            border-bottom-style: double;
            right: 0px;
            height: 90%;
            /*background: red;*/
        }
        .lp:before{
            position: absolute;
            content: '';
            width: 80%;
            border-bottom: 1px solid black ;
            top:0%;
            right: 0px;
        }

    </style>
</head>
<body>

<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('Sale_By_Customer_Summary'); ?></h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown"><a href="#" class="toggle_up tip" title="<?= lang('hide_form') ?>"><i
                                class="icon fa fa-toggle-up"></i></a></li>
                <li class="dropdown"><a href="#" class="toggle_down tip" title="<?= lang('show_form') ?>"><i
                                class="icon fa fa-toggle-down"></i></a></li>
                <?php if ($Owner || $Admin || $GP['sales-export']) { ?>
                    <li class="dropdown"><a href="#" id="pdf" data-action="export_pdf"  class="tip" title="<?= lang('download_pdf') ?>"><i
                                    class="icon fa fa-file-pdf-o"></i></a></li>
                    <li class="dropdown"><a href="#" id="excel" data-action="export_excel"  class="tip" title="<?= lang('download_xls') ?>"><i
                                    class="icon fa fa-file-excel-o"></i></a></li>
                    <li class="dropdown"><a href="#" id="image" class="tip" title="<?= lang('save_image') ?>"><i
                                    class="icon fa fa-file-picture-o"></i></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
        <div class="box-content">
            <div class="row">
                <div class="col-lg-12">
                    <p class="introtext"><?= lang('list_results'); ?></p>
                    <div id="form">

                    <?php echo form_open("account/sale_customer_summary"); ?>
                    <div class="row">
                        <!--<div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="product_id"><?= lang("product"); ?></label>
                                <?php
                                $p_c='';
                                $pr[0] = $this->lang->line("all");;
                                foreach ($products as $product) {
                                    $pr[$product->id] = $product->name . " | " .$p_c= $product->code ;
                                   // $pr1[$product->id]=$product->code;
                                }
                                echo form_dropdown('products', $pr, (isset($_POST['product']) ? $_POST['product'] : ""), 'class="form-control" id="product" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("product") . '"');

                                ?>
                            </div>
                        </div>-->

<!--                        <div class="col-sm-4">-->
<!--                            <div class="form-group">-->
<!--                                <label class="control-label" for="type">--><?//= lang("type"); ?><!--</label>-->
<!--                                <select name="type" id="" class="form-control">-->
<!--                                    <option value="0">All</option>-->
<!--                                    <option value="Invoice">Invoice</option>-->
<!--                                    <option value="Return">Return</option>-->
<!--                                </select>-->
<!---->
<!--                            </div>-->
<!---->
<!--                        </div>-->
                        <!--<div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="category"><?= lang("category"); ?></label>
                                <?php
                                $cate["0"] = lang('all');
                                foreach ($categories as $cat) {
                                    $cate[$cat->id] =  $cat->name;
                                }
                                echo form_dropdown('category', $cate, (isset($_POST['category']) ? $_POST['category'] : ""), 'class="form-control" id="category" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("category") . '"');
                                ?>
                            </div>

                        </div>-->
                        <!--<div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="warehouse"><?= lang("warehouse"); ?></label>
                                <?php
                                $ware["0"] = lang('all');
                                foreach ($warefull as $wh) {
                                    $ware[$wh->id] =  $wh->name;


                                }
                                echo form_dropdown('warehouse', $ware, (isset($_POST['warehouse']) ? $_POST['warehouse'] : ""), 'class="form-control" id="warehouse" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("warehouse") . '"');
                                ?>
                            </div>

                        </div>-->

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="biller"><?= lang("customer"); ?></label>
                                <?php
                                $cus["0"] = lang('all');
                                foreach ($customers as $customer) {
                                    $cus[$customer->id] =  $customer->name;


                                }
                                echo form_dropdown('customers', $cus, (isset($_POST['customer']) ? $_POST['customer'] : ""), 'class="form-control" id="biller" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("customer") . '"');
                                ?>
                            </div>

                        </div>


                        <!--<div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="reference_no"><?= lang("reference_no"); ?></label>
                                <?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : ""), 'class="form-control tip" id="reference_no"'); ?>

                            </div>
                        </div>-->
                        <!--<div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="biller"><?= lang("biller"); ?></label>
                                <?php
                                $bl["0"] = lang('all');
                                foreach ($billers as $biller) {
                                    $bl[$biller->id] = $biller->company != '-' ? $biller->company : $biller->name;
                                }
                                echo form_dropdown('biller', $bl, (isset($_POST['biller']) ? $_POST['biller'] : ""), 'class="form-control" id="biller" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("biller") . '"');
                                ?>
                            </div>
                        </div>-->
                        <?php if($this->Settings->product_serial) { ?>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <?= lang('serial_no', 'serial'); ?>
                                    <?= form_input('serial', '', 'class="form-control tip" id="serial"'); ?>
                                </div>
                            </div>
                        <?php } ?>
                        <!--<div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("start_date", "start_date"); ?>
                                <?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control datetime" id="start_date"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("end_date", "end_date"); ?>
                                <?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ""), 'class="form-control datetime" id="end_date"'); ?>
                            </div>
                        </div>-->
                    </div>
                    <div class="form-group">
                        <div
                                class="controls"> <?php echo form_submit('submit_sale_report', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
                    </div>
                    <?php echo form_close(); ?>

                </div>
                </div>

                <br>
                <?php
                ?>
                <table class="table table-bordered table-striped table-hover"  >
                    <thead>
                    <tr>
                        <td>Customer</td>
                        <td>Amount</td>
                    </tr>
                    </thead>

                    <tbody>
                    <?php
                        $product_detail = $this->reports_model->getSaleByCustomerSummary($customer_id);

                        $total_amt_footer = 0;
                        foreach ($product_detail as $aa) {

                            ?>
                                    <tr <?= $type ?> style="cursor: pointer">
                                        <td><?php echo $aa->customer;  ;?></td>
                                        <td style="text-align: right"><?php echo number_format($aa->GrandTotal,2);?></td>
                                    </tr>
                                    <?php $total_amt_footer += $aa->GrandTotal;?>
                                    <?php }

                                    //$this->erp->print_arrays($total_amt_footer);
                                    ?>
                                <?php
                                    if($total_amt_footer != 0){
                                        ?>
                                        <tr class="total-item2">
                                        <td colspan="1"><b>Total</b></td>
                                        <td class="line_op1 lp"><?php echo number_format($total_amt_footer,2); ?></td>
                                        </tr>
                                    <?php  } ?>
                                </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
</body>
</html>