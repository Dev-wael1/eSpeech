<!-- Main Content -->

<div class="main-content">
    <section class="section">
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1 shadow">
                    <div class="card-icon bg-warning">
                        <i class="far fa-user"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4><?= labels('total_users', 'Total Users') ?></h4>
                        </div>
                        <div class="card-body">
                            <span class="counter"><?= $total_users ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1 shadow">
                    <div class="card-icon bg-success">
                        <i class="far fa-newspaper"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4><?= labels('active_subscriptions', 'Active Subscriptions') ?></h4>
                        </div>
                        <div class="card-body">
                            <span class="counter"><?= $total_active ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1 shadow">
                    <div class="card-icon bg-danger">
                        <i class="far fa-file"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4><?= labels('expired_subscriptions', 'Expired Subscriptions') ?></h4>
                        </div>
                        <div class="card-body">
                            <span class="counter"><?= $total_expired ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1 shadow">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-circle"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4><?= labels('total_text_to_speech', 'Total Text to Speech') ?></h4>
                        </div>
                        <div class="card-body">
                            <span class="counter"><?= $total_tts ?></span>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid card">
            <h2 class="section-title"><?= labels('characters_used', 'Character Used') ?></h2>
            <div class="row">
                <?php
                $data = get_settings('tts_config', true);
                ?>
                <div class="col-md <?= $data['gcpStatus'] == 'enable' ? '' : 'd-none' ?>">
                    <div class="card card-statistic-1 shadow">
                        <div class="card-icon">
                            <span class="iconify" data-icon="logos:google-cloud"></span>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4><?= labels('characters', 'Characters') ?></h4>
                            </div>
                            <div class="card-body">
                                <span class="counter"><?= $total_google ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md <?= $data['amazonPollyStatus'] == 'enable' ? '' : 'd-none' ?>">
                    <div class="card card-statistic-1 shadow">
                        <div class="card-icon">
                            <span class="iconify" data-icon="logos:aws"></span>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4><?= labels('characters', 'Characters') ?></h4>
                            </div>
                            <div class="card-body">
                                <span class="counter"><?= $total_aws ?></span>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md <?= $data['ibmStatus'] == 'enable' ? '' : 'd-none' ?>">
                    <div class="card card-statistic-1 shadow">
                        <div class="card-icon">
                            <span class="iconify" data-icon="logos:ibm"></span>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4><?= labels('characters', 'Characters') ?></h4>
                            </div>
                            <div class="card-body">
                                <span class="counter"><?= $total_ibm ?></span>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md <?= $data['azureStatus'] == 'enable' ? '' : 'd-none' ?>">
                    <div class="card card-statistic-1 shadow">
                        <div class="card-icon">
                            <span class="iconify" data-icon="logos:azure-icon"></span>
                        </div>
                        <div class="card-wrap ">
                            <div class="card-header">
                                <h4><?= labels('characters', 'Characters') ?></h4>
                            </div>
                            <div class="card-body">
                                <span class="counter"><?= $total_azure ?></span>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <h2 class="section-title"><?= labels('assets_statistics', 'Assests Statistics') ?></h2>

            <div class="row">

                <div class="col-md-6">
                    <div>
                        <div class="card-header">
                            <h4><?= labels('usage_chart', 'Usage Chart') ?></h4>
                        </div>
                        <div>
                            <div class="height35 text-center">
                                <canvas id="usageChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div>
                        <div class="card-header">
                            <h4><?= labels('earning_chart', 'Earning Chart') ?></h4>
                        </div>
                        <div class="card-body">
                            <div>
                                <canvas id="earningChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="container-fluid card">
            <h2 class='section-title'><?= labels('subscription', 'Subscriptions') ?></h2>
            <table class="table table-striped" id="subscription_table" data-detail-view="true" data-detail-formatter="detailFormatter" data-toggle="table" data-url="<?= base_url("user/subscriptions/table") ?>" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 25, 50, 100, 200, All]" data-search="true" data-show-columns="true" data-show-refresh="true" data-sort-name="id" data-sort-order="DESC" data-query-params="queryParams">
                <thead>
                    <tr>
                        <th data-field="id" data-sortable="true" data-visible="false"><?= labels('id', 'ID') ?></th>
                        <th data-field="profile"><?= labels('users', 'Users') ?></th>
                        <th data-field="plan_title"><?= labels('plan_name', 'Plan Name') ?></th>
                        <th data-field="type"><?= labels('plan_type', 'Plan Type') ?></th>
                        <th data-field="price" data-sortable="true"><?= labels('price', 'Price') ?></th>
                        <th data-field="status"><?= labels('status', 'Status') ?></th>
                        <th data-field="txn-id" data-visible="false"><?= labels('transaction_id', 'Transaction ID') ?></th>
                        <th data-field="characters" data-sortable="true" data-visible="false"><?= labels('total_characters', 'Total Characters') ?></th>
                        <th data-field="remaining_characters" data-sortable="true" data-visible="false"><?= labels('remaining_characters', 'Total Remaining Characters') ?></th>
                        <th data-field="google" data-sortable="true" data-visible="false">GCP <?= labels('total_characters', 'Total Characters') ?></th>
                        <th data-field="remaining_google" data-sortable="true" data-visible="false">GCP <?= labels('remaining_characters', 'Remaining Characters') ?></th>
                        <th data-field="aws" data-sortable="true" data-visible="false">Amazon Polly <?= labels('total_characters', 'Total Characters') ?></th>
                        <th data-field="remaining_aws" data-sortable="true" data-visible="false">Amazon Polly <?= labels('remaining_characters', 'Remaining Characters') ?></th>
                        <th data-field="ibm" data-sortable="true" data-visible="false">IBM Whatson <?= labels('total_characters', 'Total Characters') ?></th>
                        <th data-field="remaining_ibm" data-sortable="true" data-visible="false">IBM Whatson <?= labels('remaining_characters', 'Remaining Characters') ?></th>
                        <th data-field="azure" data-sortable="true" data-visible="false">MS Azure <?= labels('total_characters', 'Total Characters') ?></th>
                        <th data-field="remaining_azure" data-sortable="true" data-visible="false">MS Azure <?= labels('remaining_characters', 'Remaining Characters') ?></th>
                        <th data-field="starts_from"><?= labels('start_date', ' Subscription Start Date') ?></th>
                        <th data-field="expires_on"><?= labels('end_date', ' Subscription End Date') ?></th>
                        <th data-field="tenure"><?= labels('tenure', ' Subscription tenure') ?></th>
                    </tr>
                </thead>
            </table>
        </div>
    </section>
</div>

<script>
    $(document).ready(() => {
        var labels = ['Amazon', 'Azure', 'Google', 'IBM'];
        var data = [<?= $total_aws ?>, <?= $total_azure ?>, <?= $total_google ?>, <?= $total_ibm ?>];
        var dates = ['2021-09-22', '2021-09-06', '2021-08-25', '2021-08-17', '2021-08-09', '2021-07-27', '2021-07-13'];
        var amounts = [0, 100, 0, 0, 0, 0, 240];
        var bg_colors = ['#19aade', '#FF3333', '#FF33D4', '#33FFDA'];
        var bg_color = '#3333cc';
        currency_symbol = '₹';
        var ctx = document.getElementById("earningChart").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: '',
                    data: data,
                    borderWidth: 2,
                    backgroundColor: bg_colors,
                    borderColor: bg_colors,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#ffffff',
                    pointRadius: 4
                }]
            },

        });

        var ctx = document.getElementById("usageChart").getContext('2d');

        var myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    label: '',
                    data: data,
                    borderWidth: 2,
                    backgroundColor: bg_colors,
                    borderColor: bg_colors,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#ffffff',
                    pointRadius: 4,


                }],


            },
            options: {
                legend: {
                    display: false
                },

                scales: {
                    yAxes: [{
                        gridLines: {
                            drawBorder: false,
                            color: '#f2f2f2',
                        },
                        ticks: {
                            beginAtZero: true,
                            stepSize: 3000
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            display: false
                        }
                    }]
                },
                maxHeight: "100px",

            }
        });

    });
</script>