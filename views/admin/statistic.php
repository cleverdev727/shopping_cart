<?php
use yii\helpers\Html;
use yii\bootstrap4\LinkPager;
use yii\web\View;

$i = 1;

$this->title = 'Statistics';
echo '
    <script>
        var curDate = "'. (isset($_GET['date']) ? $_GET['date'] : '') .'";
    </script>
';
$this->registerJs(
    '
        $("#type").change(function() {
            var type = $(this).val();
            var url = "/admin/statistics?type=" + type;
            var d = new Date();
            if (type === "month") {
                var year = $("#year").val() === undefined ? d.getFullYear() : $("#year").val();
                console.log($("#year").val());
                url += "&year=" + year;
            } else if (type === "week") {
                var date = d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate();
                url += "&date=" + date;
            }
            window.location.href = url;
        });

        $("#year").change(function() {
            window.location.href = "/admin/statistics?type=month&year=" + $("#year").val();
        });

        $("#prev").click(function(){
            var oldDate = new Date(curDate);
            var newDate = removeDays(oldDate, 7);
            var date = newDate.getFullYear() + "-" + (newDate.getMonth() + 1) + "-" + newDate.getDate();
            window.location.href = "/admin/statistics?type=week&date=" + date;
        });

        $("#next").click(function(){
            var oldDate = new Date(curDate);
            var newDate = addDays(oldDate, 7);
            var date = newDate.getFullYear() + "-" + (newDate.getMonth() + 1) + "-" + newDate.getDate();
            window.location.href = "/admin/statistics?type=week&date=" + date;
        });

        function addDays(date, days) {
            date.setDate(date.getDate() + days);
            return date;
        }

        function removeDays(date, days) {
            date.setDate(date.getDate() - days);
            return date;
        }
    ',
    View::POS_READY,
);
$type = isset($_GET['type']) ? $_GET['type'] : '';
$year = isset($_GET['year']) ? $_GET['year'] : '';
?>
<div class="container-fluid">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="col-lg-12 mb-3">
                    <div class="row">
                        <div class="col-lg-4">
                            <select name="" id="type" class="form-control">
                                <option value="week" <?php echo $type === 'week' ? 'selected' : '' ?>>Weeks</option>
                                <option value="month" <?php echo $type === 'month' ? 'selected' : '' ?>>Months</option>
                                <option value="year" <?php echo $type === 'year' ? 'selected' : '' ?>>Years</option>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <?php
                                if ($type === 'month') {
                            ?>
                                <select name="" id="year" class="form-control">
                                    <?php
                                        for ($i = $startYear; $i <= $curYear; $i ++) {
                                            $status = $year == $i ? 'selected' : '';
                                            echo '<option value="'. $i .'" '. $status .'>'. $i .'</option>';
                                        }
                                    ?>
                                </select>
                            <?php
                                }
                            ?>
                            <?php
                                if ($type === 'week' || $type === '') {
                            ?>
                                <div id="week-btn">
                                    <button class="btn btn-primary mr-3" id="prev">Last Week</button>
                                    <button class="btn btn-info" id="next">Next Week</button>
                                </div>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12" style="overflow: auto;">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Day</th>
                                <th>Sales</th>
                                <th>Visits</th>
                                <th>Unique</th>
                                <th>Checkout</th>
                                <th>Amount</th>
                                <th>Profit</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($data as $row){ ?>
                            <tr>
                                <td><?= $row['day'] ?></td>
                                <td>
                                    <span class="text-warning"><?= isset($row['Uncompleted']) ? $row['Uncompleted'] : 0 ?></span> | 
                                    <span class="text-primary"><?= isset($row['WaitingApproval']) ? $row['WaitingApproval'] : 0 ?></span> | 
                                    <span class="text-success"><?= isset($row['Approved']) ? $row['Approved'] : 0 ?></span> | 
                                    <span class="text-info"><?= isset($row['Shipped']) ? $row['Shipped'] : 0 ?></span> | 
                                    <span class="text-danger"><?= isset($row['Disapproved']) ? $row['Disapproved'] : 0 ?></span>
                                </td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <div class="d-flex">
                        <span class="mr-3" style="display: flex; align-items:center">
                            <span class="bg-warning" style="width:16px; height:16px; display:flex"></span>
                            <span>&nbsp;-&nbsp;Uncompleted</span>
                        </span>
                        <span class="mr-3" style="display: flex; align-items:center">
                            <span class="bg-primary" style="width:16px; height:16px; display:flex"></span>
                            <span>&nbsp;-&nbsp;WaitingApproval</span>
                        </span>
                        <span class="mr-3" style="display: flex; align-items:center">
                            <span class="bg-success" style="width:16px; height:16px; display:flex"></span>
                            <span>&nbsp;-&nbsp;Approved</span>
                        </span>
                        <span class="mr-3" style="display: flex; align-items:center">
                            <span class="bg-info" style="width:16px; height:16px; display:flex"></span>
                            <span>&nbsp;-&nbsp;Shipped</span>
                        </span>
                        <span class="mr-3" style="display: flex; align-items:center">
                            <span class="bg-danger" style="width:16px; height:16px; display:flex"></span>
                            <span>&nbsp;-&nbsp;Disapproved</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</div>