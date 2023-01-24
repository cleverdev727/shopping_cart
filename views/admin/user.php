<?php

use yii\bootstrap4\LinkPager;
use yii\web\View;
use yii\jui\DatePicker;

$this->title = 'Orders';
$this->registerJs(
    '
    function changeDateFormat(dataStr){
        return dataStr.split("/").reverse().join("-");
    }
    $(\'#per_page\').change(function(){
        var from = $("input[name=from]").val();
        var to = $("input[name=to]").val();
        from = changeDateFormat(from);
        to = changeDateFormat(to);
        window.location.href = "/admin/orders?page=1&per-page=" + $(\'#per_page\').val() + "&from=" + from + "&to=" + to;
    })
    $("#showAll").click(function(){
        window.location.href = "/admin/orders?page=1&per-page=" + $(\'#per_page\').val()+ "&from=" + "&to=";
    })
    $("#showByDate").click(function(){
        var from = $("input[name=from]").val();
        var to = $("input[name=to]").val();
        from = changeDateFormat(from);
        to = changeDateFormat(to);
        window.location.href = "/admin/orders?page=1&per-page=" + $(\'#per_page\').val() + "&from=" + from + "&to=" + to;
    })
    ',
    View::POS_READY,
);
$from = isset($_GET["from"]) ? $_GET["from"] : "";
$to = isset($_GET["to"]) ? $_GET["to"] : "";
$i = $j = $pagination->limit * $pagination->page + 1;
?>
<div class="container-fluid">
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group row">
                                        <label for="from" class="col-sm-4 col-form-label">From : </label>
                                        <div class="col-sm-8">
                                            <?php echo DatePicker::widget([
                                                    'name'  => 'from',
                                                    'value'  => $from,
                                                    'dateFormat' => 'dd/MM/yyyy',
                                                ]);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group row">
                                        <label for="to" class="col-sm-4 col-form-label">To : </label>
                                        <div class="col-sm-8">
                                            <?php echo DatePicker::widget([
                                                    'name'  => 'to',
                                                    'value'  => $to,
                                                    'dateFormat' => 'dd/MM/yyyy',
                                                ]);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <button type="button" id="showByDate" class="btn btn-block btn-danger">
                                        Show By Date
                                    </button>
                                </div>
                                <div class="col-2">
                                    <button type="button" id="showAll" class="btn btn-block btn-danger">
                                        All
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12" style="overflow: auto;">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <!-- <th>#</th> -->
                                <th>Date</th>
                                <th>ID</th>
                                <th>Order Status</th>
                                <th>Payment</th>
                                <th>Domain</th>
                                <th>Address</th>
                                <!-- <th>First Name</th>
                                <th>Last Name</th> -->
                                <th>Email</th>
                                <th>Phone</th>
                                <!-- <th>Street1</th>
                                <th>Street2</th>
                                <th>ZIP</th>
                                <th>City</th>
                                <th>Country</th> -->
                                <th>Language</th>
                                <!-- <th>IP</th> -->
                                <th>IP / UA</th>
                                <th>UOI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($models as $field){ ?>
                                <tr>
                                    <!-- <td><?= $i++ ?></td> -->
                                    <td><?= $field->date ?></td>
                                    <td><?= $field->id ?></td>
                                    <td><?= $field->order_status ?></td>
                                    <td><?= $field->payment_method ?></td>
                                    <td><?= $field->domain ?></td>
                                    <td><?= $field->first_name ?> <?= $field->last_name ?>  <?= $field->street1 ?> <?= $field->street2 ?> <?= $field->zip ?> <?= $field->city ?> <?= $field->country ?></td>
                                    <!-- <td><?= $field->first_name ?></td> -->
                                    <!-- <td><?= $field->last_name ?></td> -->
                                    <td><?= $field->email ?></td> 
                                    <td><?= $field->phone ?></td> 
                                    <!-- <td><?= $field->street1 ?></td>  -->
                                    <!-- <td><?= $field->street2 ?></td> 
                                    <td><?= $field->zip ?></td> 
                                    <td><?= $field->city ?></td> 
                                    <td><?= $field->country ?></td>  -->
                                    <td><?= strtoupper($field->lang) ?></td> 
                                    <!-- <td><?= $field->ip ?></td>  -->
                                    <td><?= $field->ip ?> <?= $field->ua ?></td> 
                                    <td><?= $field->uoi ?></td> 
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer clearfix">
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <select class="form-control" style="width: 100px" id="per_page">
                                <?php
                                    if(!empty($_GET['per-page'])){
                                        $pageSize = $_GET['per-page'];
                                    }else{
                                        $pageSize = $pageSizes[0];
                                    }
                                ?>
                                <?php foreach($pageSizes as $item){ ?>
                                    <option value="<?= $item ?>" 
                                    <?php if($item == $pageSize){ ?>
                                        selected
                                    <?php } ?>
                                    ><?= $item ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-3" style="margin-top: 7px;">
                        <p>Showing <?= $j ?> to <?= $i - 1 ?> of <?= $pagination->totalCount ?> entries</p>
                    </div>
                    <div class="col-5">
                        
                        <?=
                            LinkPager::widget([
                                'pagination' => $pagination,
                                'options' => ['class' => 'pagination pagination-md m-0 float-right'],
                                'maxButtonCount' => 6,
                                'firstPageLabel' => 'First',
                                'lastPageLabel' => 'Last',
                                
                            ]);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>