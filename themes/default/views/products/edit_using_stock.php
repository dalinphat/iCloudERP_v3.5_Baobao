<script type="text/javascript">
    var count = 1, usitems = {};
    $(document).ready(function () {
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
            }
        });

        $('#customer').val(<?= $this->input->post('customer') ?>);
        <?php } ?>

        if (__getItem('usitems')) {
            __removeItem('usitems');
        }
        if (__getItem('from_location')) {
            __removeItem('from_location');
        }
        if (__getItem('authorize_id')) {
            __removeItem('authorize_id');
        }
        if (__getItem('employee_id')) {
            __removeItem('employee_id');
        }
        if (__getItem('shop')) {
            __removeItem('shop');
        }
        if (__getItem('account')) {
            __removeItem('account');
        }

        $("#add_item").autocomplete({
            source: function (request, response) {
                $.ajax({
                    type: 'get',
                    url: '<?= site_url('products/suggestionsStock'); ?>',
                    dataType: "json",
                    data: {
                        term: request.term,
                        warehouse_id: $("#from_location").val(),
                        plan: $("#plan").val()
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            minLength: 1,
            autoFocus: false,
            delay: 200,
            response: function (event, ui) {
                if ($(this).val().length >= 16 && ui.content[0].id == 0) {
                    bootbox.alert('<?= lang('no_match_found') ?>', function () {
                        $('#add_item').focus();
                    });
                    $(this).val('');
                }
                else if (ui.content.length == 1 && ui.content[0].id != 0) {
                    ui.item = ui.content[0];
                    $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
                    $(this).autocomplete('close');
                    $(this).removeClass('ui-autocomplete-loading');
                }
                else if (ui.content.length == 1 && ui.content[0].id == 0) {
                    bootbox.alert('<?= lang('no_match_found') ?>', function () {
                        $('#add_item').focus();
                    });
                }
            },
            select: function (event, ui) {
                event.preventDefault();
                if (ui.item.id !== 0) {
                    var row = add_using_stock_item(ui.item);
                    if (row)
                        $(this).val('');
                } else {
                    bootbox.alert('<?= lang('no_match_found') ?>');
                }
            }
        });

        $('#add_item').bind('keypress', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                $(this).autocomplete("search");
            }
        });

        $("#date").datetimepicker({
            format: site.dateFormats.js_sdate,
            fontAwesome: true,
            todayBtn: 1,
            autoclose: 1,
            minView: 2
        }).datetimepicker('update', '<?= $using_stock->date; ?>');

        $("#address").select2("destroy").empty().attr("placeholder", "<?= lang('select_plan_to_load') ?>").select2({
            placeholder: "<?= lang('select_plan_to_load') ?>", data: [
                {id: '', text: '<?= lang('select_plan_to_load') ?>'}
            ]
        });

        $('#plan').change(function () {
            var v = $(this).val();
            $('#modal-loading').show();
            if (v) {
                $.ajax({
                    type: "get",
                    async: false,
                    url: "<?= site_url('products/getAddress') ?>/" + v,
                    dataType: "json",
                    success: function (scdata) {
                        if (scdata != null) {
                            $("#address").select2("destroy").empty().attr("placeholder", "<?= lang('select_subcategory') ?>").select2({
                                placeholder: "<?= lang('select_plan_to_load') ?>",
                                data: scdata
                            });
                        }else{
                            $("#address").select2("destroy").empty().attr("placeholder", "<?= lang('select_subcategory') ?>").select2({
                                placeholder: "<?= lang('select_plan_to_load') ?>",
                                data: 'not found'
                            });
                        }
                    },
                    error: function () {
                        bootbox.alert('<?= lang('ajax_error') ?>');
                        $('#modal-loading').hide();
                    }
                });
            } else {
                $("#address").select2("destroy").empty().attr("placeholder", "<?= lang('select_plan_to_load') ?>").select2({
                    placeholder: "<?= lang('select_plan_to_load') ?>",
                    data: [{id: '', text: '<?= lang('select_plan_to_load') ?>'}]
                });
            }
            $('#modal-loading').hide();
        });

        $('#plan').trigger('change');
    });
    <?php if ($items) {?>
    __setItem('usitems', JSON.stringify(<?=$items;?>));
    __setItem('from_location', '<?= $where; ?>');
    __setItem('authorize_id', '<?= $using_stock->authorize_id; ?>');
    __setItem('employee_id', '<?= $using_stock->employee_id; ?>');
    __setItem('customer', '<?= $using_stock->customer_id; ?>');
    __setItem('sale_order_id', '<?= $using_stock->sale_order_id; ?>');
    __setItem('sale_id', '<?= $using_stock->sale_id; ?>');
    __setItem('shop', '<?= $using_stock->shop; ?>');
    __setItem('account', '<?= $using_stock->account; ?>');
    __setItem('plan', '<?= $using_stock->plan_id; ?>');
    <?php } ?>
</script>
<?php echo form_open("products/update_using_stock"); ?>

<div class="box">
    <div class="box-header">
        <h2 class="blue">
            <i class="fa-fw fa fa-heart"></i><?= lang('edit_enter_using_stock'); ?>
        </h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <input type="hidden"  name="stock_id"  id="stock_id" value="<?=$using_stock->id?>" />
                <div class="clearfix"></div>

                <div class="row">
                    <div class="col-md-4">
                        <?php if ($Owner || $Admin || $Settings->allow_change_date == 1) { ?>
                            <div class="form-group">
                                <?= lang('date', 'date'); ?>
                                <?= form_input('date', $date, 'class="form-control tip date" required id="date"'); ?>
                            </div>
                        <?php } ?>
                        <div class="form-group">
                            <?= lang('reference_no', 'reference_no'); ?>
                            <?= form_input('reference_no', $refer, 'class="form-control tip"  required  id="reference_no" style="pointer-events:none;"'); ?>

                        </div>
                        <div class="form-group">
							<?= lang('customer', 'customer'); ?>
							<?php
                        echo form_input('customer', '', 'id="customer" data-placeholder="' . lang("select") . ' ' . lang("customer") . '" class="form-control input-tip" style="min-width:100%;"');
                        ?>
						</div>  
                        <div class="form-group">
                            <?= lang('sale_order_invoice', 'sale_order_invoice'); ?>
                            <?php
                            echo form_input('sale_order_invoice', '', 'id="sale_order_invoice" data-placeholder="' . lang("select") . ' ' . lang("sale_invoice") . '" class="form-control input-tip" style="min-width:100%;"');
                            ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <?= lang('authorize_by', 'authorize_by'); ?>
                            <?php

                            foreach ($AllUsers as $AU) {
                                $users[$AU->id] = $AU->username;
                            }

                            echo form_dropdown('authorize_id', $users, $using_stock->authorize_id, 'class="form-control"  required  id="authorize_id" placeholder="' . lang("select") . ' ' . lang("authorize_id") . '" style="width:100%"')
                            ?>
                        </div>
                        <div class="form-group">
                            <?= lang('employee', 'employee'); ?>
                            <?php

                            foreach ($employees as $epm) {
                                $em[$epm->id] = $epm->fullname;
                            }

                            echo form_dropdown('employee_id', $em, $using_stock->employee_id, 'class="form-control"    id="employee_id" placeholder="' . lang("select") . ' ' . lang("employee") . '" style="width:100%"')
                            ?>

                        </div>
                        <div class="form-group">
                            <?= lang('sale_invoice', 'sale_invoice'); ?>
                            <?php
                            echo form_input('sale_invoice', '', 'id="sale_invoice" data-placeholder="' . lang("select") . ' ' . lang("sale_invoice") . '" class="form-control input-tip" style="min-width:100%;"');
                            ?>
                        </div>


                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <?= lang('account', 'account'); ?>
                            <?php
                            $gl[""] = "";
                            foreach ($getGLChart as $GLChart) {
                                $gl[$GLChart->accountcode] = $GLChart->accountcode.' - '.$GLChart->accountname;
                            }

                            echo form_dropdown('account', $gl, $using_stock->account, 'class="form-control"    id="account" placeholder="' . lang("select") . ' ' . lang("account") . '" style="width:100%"')
                            ?>
                        </div>
                        <div class="form-group all">
                            <?= lang("from_location", "from_location") ?>
                            <?php
                            $wh[""]="";
                            foreach ($warehouses as $warehouse) {
                                $wh[$warehouse->id] = $warehouse->code .'-'. $warehouse->name;
                            }

                            echo form_dropdown('from_location', $wh, $where, 'class="form-control"   required  id="from_location" placeholder="' . lang("select") . ' ' . lang("location") . '" style="width:100%;pointer-events:none;"')
                            ?>
                        </div>
                        <div class="form-group" style="pointer-events:none;">
                            <?= get_dropdown_project('shop', 'shop', $using_stock->shop); ?>
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 pr_form" id="sticker">
                        <div class="well well-sm">
                            <div class="form-group" style="margin-bottom:0;">
                                <div class="input-group wide-tip">
                                    <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">
                                        <i class="fa fa-2x fa-barcode addIcon"></i></div>
                                    <?php echo form_input('add_item', '', 'class="form-control input-lg" id="add_item" placeholder="' . $this->lang->line("add_product_to_order") . '"'); ?>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 pr_form">
                        <div class="table-responsive">
                            <table id="UsData" class="table table-bordered table-hover table-striped table-condensed reports-table">
                                <thead>
                                <tr>
                                    <th style="width:33% !important;">
                                        <span><?= lang("item_code"); ?></span>
                                    </th>
                                    <?php
                                    if ($Settings->product_expiry) {
                                        echo '<th style="width:14% !important;">' . $this->lang->line("expiry_date") . '</th>';
                                    }
                                    ?>
                                    <th style="width:25% !important;"><?= lang("description"); ?></th>
                                    <th style="width:3% !important;"><?= lang("QOH"); ?></th>
                                    <th style="width:15% !important;"><?= lang("qty_use"); ?></th>
                                    <th style="width:10%"><?= lang("units"); ?></th>
                                    <th style="width:3% !important;"><i class="fa fa-trash-o" aria-hidden="true"></i></th>
                                </tr>
                                </thead>
                                <tbody class="tbody">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group all">
                            <?= lang("note", "note") ?>
                            <?= form_textarea('note',$using_stock->note, 'class="form-control" id="note"'); ?>
                        </div>
                    </div>
                </div>

                <!-- Button Submit -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="fprom-group">
                            <input type="hidden"  name="total_item_cost"  id="total_item_cost" class=" form-control total_item_cost" value="">
                            <input type="hidden" value="" name="store_del_pro_id" id="store_del_pro_id"/>

                            <?php echo form_submit('submit_report', $this->lang->line("submit"), 'class="btn btn-primary" style="display:none;" id="btn_submit"'); ?>

                            <button type="button" name="submit_report" class="btn btn-primary" id="btn_using"><?= lang('submit') ?></button>

                            <button type="button" name="convert_items" class="btn btn-danger" id="reset"><?= lang('reset') ?></button>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>

            </div>
        </div>
    </div>
    <?php
    $units[""] = "";
    foreach ($all_unit as $getunits) {
        $units[$getunits->id] = $getunits->name;
    }
    $dropdown= form_dropdown("purchase_type", $units, '', 'id="purchase_type"  class="form-control input-tip select" style="width:100%;"');
    ?>
</div>

<?php
$unit_option='';
foreach($all_unit as $getunits){
    $unit_option.= '<option value='.$getunits->id.'>'.$getunits->name.'</option>';
}
?>








