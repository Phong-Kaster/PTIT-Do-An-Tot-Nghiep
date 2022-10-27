<!DOCTYPE html>
<html lang="<?= ACTIVE_LANG ?>">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
        <meta name="theme-color" content="#fff">

        <meta name="description" content="<?= site_settings("site_description") ?>">
        <meta name="keywords" content="<?= site_settings("site_keywords") ?>">

        <link rel="icon" href="<?= site_settings("logomark") ? site_settings("logomark") : APPURL."/assets/img/logomark.png" ?>" type="image/x-icon">
        <link rel="shortcut icon" href="<?= site_settings("logomark") ? site_settings("logomark") : APPURL."/assets/img/logomark.png" ?>" type="image/x-icon">

        <link rel="stylesheet" type="text/css" href="<?= APPURL."/assets/css/plugins.css?v=".VERSION ?>">
        <link rel="stylesheet" type="text/css" href="<?= APPURL."/assets/css/core.css?v=".VERSION ?>">

        <title><?= __("Statistics") ?></title>
    </head>

    <body>
        <?php 
            $Nav = new stdClass;
            $Nav->activeMenu = "statistics";
            require_once(APPPATH.'/views/fragments/navigation.fragment.php');
        ?>

        <?php 
            $TopBar = new stdClass;
            $TopBar->title = __("Statistics");
            $TopBar->btn = false;
            require_once(APPPATH.'/views/fragments/topbar.fragment.php'); 
        ?>

        <?php require_once(APPPATH.'/views/fragments/statistics.fragment.php'); ?>
        
        <script type="text/javascript" src="<?= APPURL."/assets/js/plugins.js?v=".VERSION ?>"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js" type="text/javascript"></script>
        <?php require_once(APPPATH.'/inc/js-locale.inc.php'); ?>
        <script type="text/javascript" src="<?= APPURL."/assets/js/core.js?v=".VERSION ?>"></script>
        <script type="text/javascript" charset="utf-8">
            $(function(){
                NextPost.Statistics();

                <?php if($ActiveAccount->isAvailable()): ?>
                    // Doughnut chart
                    var doughnutChart = new Chart($("#doughnut-chart"), {
                        type: "doughnut",

                        data: {
                            datasets: [{
                                data: [
                                    <?= readableNumber($PostSummary->published + $PostSummary->failed) ?>,
                                    <?= readableNumber($PostSummary->inprogress) ?>,
                                    <?= readableNumber($PostSummary->published) ?>,
                                    <?= readableNumber($PostSummary->failed) ?>,
                                ],

                                backgroundColor: [
                                    "#3B7CFF",
                                    "#5596FF",
                                    "#6EAFFF",
                                    "#88C9FF"
                                ],

                                hoverBackgroundColor: [
                                    "#212121",
                                    "#212121",
                                    "#212121",
                                    "#212121"
                                ]
                            }],

                            labels: [
                                "<?= __("Completed") ?>",
                                "<?= __("In Progress") ?>",
                                "<?= __("Published") ?>",
                                "<?= __("Failed") ?>"
                            ]
                        },

                        options: {
                            animation: {
                                animateScale: false,
                                animateRotate: true
                            },

                            legend: {
                                display: false
                            },

                            tooltips: {
                                titleFontFamily: "Fira Sans",
                            }
                        }
                    });


                    var barChart = new Chart($("#bar-chart"), {
                        type: "bar",

                        data: {
                            datasets: [
                                {
                                    label: '<?= __("In Progress") ?>',
                                    data: [
                                        <?php foreach ($PostsByMonths as $set): ?>
                                            <?= readableNumber($set->data->inprogress) ?>,
                                        <?php endforeach; ?>
                                    ],

                                    backgroundColor: "#5596FF",
                                    hoverBackgroundColor: "#3C7DE6"
                                },

                                {
                                    label: '<?= __("Published") ?>',
                                    data: [
                                        <?php foreach ($PostsByMonths as $set): ?>
                                            <?= readableNumber($set->data->published) ?>,
                                        <?php endforeach; ?>
                                    ],

                                    backgroundColor: "#6EAFFF",
                                    hoverBackgroundColor: "#5596E6"
                                },

                                {
                                    label: '<?= __("Failed") ?>',
                                    data: [
                                        <?php foreach ($PostsByMonths as $set): ?>
                                            <?= readableNumber($set->data->failed) ?>,
                                        <?php endforeach; ?>
                                    ],

                                    backgroundColor: "#88C9FF",
                                    hoverBackgroundColor: "#6FB0E6"
                                }
                            ],

                            labels: [
                                <?php foreach ($PostsByMonths as $set): ?>
                                    '<?= $set->month ?>',
                                <?php endforeach; ?>
                            ]
                        },

                        options: {
                            animation: {
                                animateScale: false,
                                animateRotate: true
                            },

                            legend: {
                                display: false
                            },

                            tooltips: {
                                titleFontFamily: "Fira Sans",
                            },

                            scales: {
                                xAxes: [{
                                    stacked: true,
                                    gridLines: {
                                        display: false,
                                        color: "#eeeeee",
                                        zeroLineColor: "#eeeeee",
                                    },
                                    ticks: {
                                        fontColor: "#9b9b9b",
                                        fontFamily: "Fira Sans"
                                    }
                                }],
                                yAxes: [{
                                    stacked: true,
                                    gridLines: {
                                        color: "#eeeeee",
                                        zeroLineColor: "#eeeeee",
                                    },
                                    ticks: {
                                        maxTicksLimit: 5,
                                        fontColor: "#9b9b9b",
                                        fontFamily: "Fira Sans",
                                    }
                                }],
                            },
                        }
                    });
                <?php endif; ?>
            })
        </script>

        <?php require_once(APPPATH.'/views/fragments/google-analytics.fragment.php'); ?>
    </body>
</html>