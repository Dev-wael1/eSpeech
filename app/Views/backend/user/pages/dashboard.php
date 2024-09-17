<!-- Main Content -->

<div class="main-content">
    <section class="section">
        <div class="section-header">

            <h1><?= labels('dashboard', "Dashboard") ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('/user/Dashboard') ?>">User</a></div>
                <div class="breadcrumb-item">Dashboard</div>
            </div>


        </div>
        <?php if (isset($active_plan[0])) {
            $active_plan = $active_plan[0];
        ?>
            <div class="container-fluid card mb-2">
                <h2 class="section-title"><?= labels('active_plan', "Active Plan") ?></h2>

            </div>
            <div class="row mb-3">
                <div class="col-md">
                    <div class="d-style btn btn-brc-tp border-2 bgc-white btn-outline-blue btn-h-outline-blue btn-a-outline-blue w-100 my-2 py-3 shadow-sm bg-white">
                        <div class="row align-items-center">
                            <div class="col-12 col-md-3">
                                <h4 class="pt-3 text-170 text-600 text-primary-d1 letter-spacing"> <?= $active_plan['plan_title'] ?></h4>
                            </div>
                            <div class="col-12 col-md-3">
                                <h4 class="pt-3 text-170 text-600 text-primary-d1 letter-spacing"> <?= labels('started_from', "Started from") ?></h4>
                                <div class="text-secondary-d1 text-120"> <span class="ml-n15 align-text-bottom"></span><span class="text-180"><?= $active_plan['starts_from'] ?></span></div>
                            </div>
                            <div class="col-12 col-md-3">
                                <h4 class="pt-3 text-170 text-600 text-primary-d1 letter-spacing"> <?= labels('tenure', "Tenure") ?></h4>
                                <div class="text-secondary-d1 text-120"> <span class="ml-n15 align-text-bottom"></span><span class="text-180"><?= $active_plan['tenure'] ?></span><?= ($active_plan['tenure'] > 1) ? "Months" : "Month" ?></div>
                            </div>
                            <div class="col-12 col-md-3">
                                <h4 class="pt-3 text-170 text-600 text-primary-d1 letter-spacing"><?= labels('expires_on', "Expires on") ?></h4>
                                <div class="text-secondary-d1 text-120"> <span class="ml-n15 align-text-bottom"></span><span class="text-180"><?= $active_plan['expires_on'] ?></span></div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>



            <div class="row">
                <div class="col-md">
                    <div class="card card-statistic-1 shadow">
                        <div class="card-icon">
                            <span class="iconify" data-icon="carbon:character-patterns" data-width="30"></span>
                        </div>
                        <div class="card-wrap ">
                            <div class="card-header">
                                <h4><?= labels('total_characters', "Total Characters") ?></h4>
                            </div>
                            <div class="card-body">
                                <span class=""> <?= numbers_initials($active_plan['remaining_characters']) ?> </span>/ <span class=""><?= numbers_initials($active_plan['characters']) ?></span>

                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md">
                    <div class="card card-statistic-1 shadow">
                        <div class="card-icon">
                            <span class="iconify" data-icon="logos:google-cloud"></span>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Google <?= labels('characters', "Characters") ?></h4>
                            </div>
                            <div class="card-body">
                                <span class=""><?= numbers_initials($active_plan['remaining_google']) ?></span> / <?= numbers_initials($active_plan['google']) ?> </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="card card-statistic-1 shadow">
                        <div class="card-icon">
                            <span class="iconify" data-icon="logos:aws"></span>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Amazon <?= labels('characters', "Characters") ?></h4>
                            </div>
                            <div class="card-body">
                                <span class=""><?= numbers_initials($active_plan['remaining_aws']) ?></span> / <span class=""><?= numbers_initials($active_plan['aws']) ?> </span>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="card card-statistic-1 shadow">
                        <div class="card-icon">
                            <span class="iconify" data-icon="logos:ibm"></span>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>IBM <?= labels('characters', "Characters") ?></h4>
                            </div>
                            <div class="card-body">
                                <span class=""><?= numbers_initials($active_plan['remaining_ibm']) ?></span> / <span><?= numbers_initials($active_plan['ibm']) ?> </span>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="card card-statistic-1 shadow">
                        <div class="card-icon">
                            <span class="iconify" data-icon="logos:azure-icon"></span>
                        </div>
                        <div class="card-wrap ">
                            <div class="card-header">
                                <h4>Azure <?= labels('characters', "Characters") ?></h4>
                            </div>
                            <div class="card-body">
                                <span class=""><?= numbers_initials($active_plan['remaining_azure']) ?></span> / <span class=""><?= numbers_initials($active_plan['azure']) ?> </span>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

        <?php } else if ($free_data['isFreeTierAllows'] == "true" ) { ?>
            <div class="container-fluid card mb-2">
                <h2 class="section-title"><?= labels('no_active', "No active subscription found") ?></h2>

            </div>
            <div class="row mb-3">
                <div class="col-md">
                    <div class="d-style btn btn-brc-tp border-2 bgc-white btn-outline-blue btn-h-outline-blue btn-a-outline-blue w-100 my-2 py-3 shadow-sm bg-white">
                        <div class="row align-items-center">
                            <div class="col-12 col-md-3">
                                <h4 class="pt-3 text-170 text-600 text-primary-d1 letter-spacing"> <?= "Free Tire Plan" ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <div class="card card-statistic-1 shadow">
                        <div class="card-icon">
                            <span class="iconify" data-icon="carbon:character-patterns" data-width="30"></span>
                        </div>
                        <div class="card-wrap ">
                            <div class="card-header">
                                <h4><?= labels('total_characters', "Total Characters") ?></h4>
                            </div>
                            <div class="card-body">
                                <span class=""> <?= numbers_initials($free_data['freeTierCharacterLimit']) ?> </span>/ <span class=""><?= numbers_initials($free_data['freeTierCharacterLimit']) ?></span>

                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md">
                    <div class="card card-statistic-1 shadow">
                        <div class="card-icon">
                            <span class="iconify" data-icon="logos:google-cloud"></span>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Google <?= labels('characters', "Characters") ?></h4>
                            </div>
                            <div class="card-body">
                                <span class=""><?= numbers_initials($free_data['freeTierCharacterLimit']) ?></span> / <?= numbers_initials($free_data['freeTierCharacterLimit']) ?> </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="card card-statistic-1 shadow">
                        <div class="card-icon">
                            <span class="iconify" data-icon="logos:aws"></span>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Amazon <?= labels('characters', "Characters") ?></h4>
                            </div>
                            <div class="card-body">
                                <span class=""><?= numbers_initials($free_data['freeTierCharacterLimit']) ?></span> / <span class=""><?= numbers_initials($free_data['freeTierCharacterLimit']) ?> </span>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="card card-statistic-1 shadow">
                        <div class="card-icon">
                            <span class="iconify" data-icon="logos:ibm"></span>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>IBM <?= labels('characters', "Characters") ?></h4>
                            </div>
                            <div class="card-body">
                                <span class=""><?= numbers_initials($free_data['freeTierCharacterLimit']) ?></span> / <span><?= numbers_initials($free_data['freeTierCharacterLimit']) ?> </span>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="card card-statistic-1 shadow">
                        <div class="card-icon">
                            <span class="iconify" data-icon="logos:azure-icon"></span>
                        </div>
                        <div class="card-wrap ">
                            <div class="card-header">
                                <h4>Azure <?= labels('characters', "Characters") ?></h4>
                            </div>
                            <div class="card-body">
                                <span class=""><?= numbers_initials($free_data['freeTierCharacterLimit']) ?></span> / <span class=""><?= numbers_initials($free_data['freeTierCharacterLimit']) ?> </span>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        <?php } else { ?>


            <div class="row mb-3">
                <div class="col-md">
                    <div class="d-style btn btn-brc-tp border-2 bgc-white btn-outline-blue btn-h-outline-blue btn-a-outline-blue w-100 my-2 py-3 shadow-sm bg-white">
                        <div class="row align-items-center">
                            <div class="col-12 col-md-3">
                                <h4 class="pt-3 text-170 text-600 text-primary-d1 letter-spacing"> <?= labels('no_active', "No active subscription found") ?></h4>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php } ?>


        <div class="container-fluid card">

            <h2 class="section-title"><?= labels('assets_statistics', "Assets Statistics") ?></h2>

            <div class="row">
                <div class="col-md-6">
                    <div>

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
                            <h4><?= labels('usage_chart', "Usage Chart") ?></h4>
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
            <h2 class='section-title'><?= labels('subscription', "Subscriptions") ?></h2>
            <table class="table table-striped" id="subscription_table" data-detail-view="true" data-detail-formatter="detailFormatter" data-toggle="table" data-url="<?= base_url("user/subscriptions/table") ?>" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 25, 50, 100, 200, All]" data-search="true" data-show-columns="true" data-show-refresh="true" data-sort-name="id" data-sort-order="DESC" data-query-params="subscription_table_params">
                <thead>
                    <tr>
                        <th data-field="id" data-sortable="true" data-visible="false"><?= labels('id', "ID") ?></th>
                        <th data-field="plan_title"><?= labels('plan_name', "Plan Name") ?></th>
                        <th data-field="type"><?= labels('plan_type', "Plan Type") ?></th>
                        <th data-field="price" data-sortable="true"><?= labels('price', "Price") ?></th>
                        <th data-field="txn-id" data-visible="false"><?= labels('transaction_id', "Transaction ID") ?></th>
                        <th data-field="characters" data-sortable="true" data-visible="false"> <?= labels('total_characters', "Total Characters") ?></th>
                        <th data-field="remaining_characters" data-sortable="true" data-visible="false">Total <?= labels('remaining_characters', "Remaining Characters") ?></th>
                        <th data-field="google" data-sortable="true" data-visible="false">GCP <?= labels('total_characters', "Total Characters") ?></th>
                        <th data-field="remaining_google" data-sortable="true" data-visible="false">GCP <?= labels('remaining_characters', "Remaining Characters") ?></th>
                        <th data-field="aws" data-sortable="true" data-visible="false">Amazon Polly <?= labels('total_characters', "Total Characters") ?></th>
                        <th data-field="remaining_aws" data-sortable="true" data-visible="false">Amazon Polly <?= labels('remaining_characters', "Remaining Characters") ?></th>
                        <th data-field="ibm" data-sortable="true" data-visible="false">IBM Whatson <?= labels('total_characters', "Total Characters") ?></th>
                        <th data-field="remaining_ibm" data-sortable="true" data-visible="false">IBM Whatson <?= labels('remaining_characters', "Remaining Characters") ?></th>
                        <th data-field="azure" data-sortable="true" data-visible="false">MS Azure <?= labels('total_characters', "Total Characters") ?></th>
                        <th data-field="remaining_azure" data-sortable="true" data-visible="false">MS Azure <?= labels('remaining_characters', "Remaining Characters") ?></th>
                        <th data-field="starts_from"><?= labels('start_date', "Start Date") ?></th>
                        <th data-field="expires_on"><?= labels('end_date', "End Date") ?></th>
                        <th data-field="tenure" data-sortable="true"><?= labels('tenure', "Tenure") ?></th>
                        <th data-field="created_on" data-sortable="true"><?= labels('purchase_date', "Purchase Date") ?></th>
                    </tr>
                </thead>
            </table>
        </div>
    </section>
</div>
<script>
    function dashboard_table(p) {
        return {
            user_id: userId,
            search: p.search,
            limit: p.limit,
            sort: p.sort,
            order: p.order,
            offset: p.offset,
        };
    }
    $(document).ready(() => {
        var labels = ['Amazon', 'Azure', 'Google', 'IBM'];
        var data = [<?= $total_aws ?>, <?= $total_azure ?>, <?= $total_google ?>, <?= $total_ibm ?>];
        var dates = ['2021-09-22', '2021-09-06', '2021-08-25', '2021-08-17', '2021-08-09', '2021-07-27', '2021-07-13'];
        var amounts = [0, 100, 0, 0, 0, 0, 240];
        var bg_colors = ['#63ed7a', '#fc544b', '#ff00ff', '#ffff00'];
        var bg_color = '#3333cc';
        currency_symbol = '₹';
        var ctx = document.getElementById("earningChart").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {

                datasets: [{
                    data: [<?= $total_aws ?>, <?= $total_azure ?>, <?= $total_google ?>, <?= $total_ibm ?>],
                    backgroundColor: [
                        '#63ed7a',
                        '#ffa426',
                        '#fc544b',
                        '#6777ef',
                    ],
                    label: 'Dataset 1'
                }],
                labels: [

                    'AWS',
                    'AZURE',
                    'GOOGLE',
                    'IBM'
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }

            }

        });

        var ctx = document.getElementById("usageChart").getContext('2d');
        var usageChart = new Chart(ctx, {
            type: 'doughnut',
            data: {

                datasets: [{
                    data: [<?= $total_aws ?>, <?= $total_azure ?>, <?= $total_google ?>, <?= $total_ibm ?>],
                    backgroundColor: [
                        '#63ed7a',
                        '#ffa426',
                        '#fc544b',
                        '#6777ef',
                    ],
                    label: 'Dataset 1'
                }],
                labels: [

                    'AWS',
                    'AZURE',
                    'GOOGLE',
                    'IBM'
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }

            }
        });

    });
</script>