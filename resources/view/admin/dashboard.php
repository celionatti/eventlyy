<?php

use PhpStrike\app\components\CrumbsComponent;

/**
 * Framework Title: PhpStrike Framework
 * Creator: Celio natti
 * version: 1.0.0
 * Year: 2023
 * 
 * 
 * This view page start name{style,script,content} 
 * can be edited, base on what they are called in the layout view
 */

$user = auth_user();

?>

<?php $this->start("header") ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
<style>
    .dashboard-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px;
    }
    .metric-card {
        background: #fff;
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        text-align: center;
        height: 100%;
    }
    .metric-value {
        font-size: 2rem;
        font-weight: bold;
        color: #1a5d1a;
        margin: 0.5rem 0;
    }
    .chart-card {
        background: #fff;
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        height: 500px;
    }
    .chart-card canvas {
        min-height: 400px;
    }
</style>
<?php $this->end() ?>

<?php $this->start('content') ?>
<?= renderComponent(CrumbsComponent::class, ['name' => 'Dashboard']); ?>
<section class="section">
    <div class="dashboard-container">
        <!-- Metric Cards Row -->
        <div class="row metric-cards">
            <div class="col-md-6 mb-4">
                <div class="metric-card">
                    <h3>Today's Sales</h3>
                    <div class="metric-value"><?= formatCurrency($metrics['todaySales'][0]['total'] ?? 0) ?></div>
                    <small>Updated: <?= date('g:i a') ?></small>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="metric-card">
                    <h3>Success Rate</h3>
                    <div class="metric-value"><?= $metrics['successRate'] ?>%</div>
                    <small>Completed transactions</small>
                </div>
            </div>
        </div>

        <?php if($user['role'] === "admin"): ?>
            <?php if($transactions): ?>
        <!-- Transactions Details -->
        <div class="card shadow-sm col-md-12 mb-4 table-responsive">
            <table class="table table-dark">
                <thead>
                    <th>Reference</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Amount</th>
                    <th>Status</th>
                </thead>
                <tbody>
                    <?php foreach($transactions as $transaction): ?>
                    <tr class="<?= $transaction['status'] == 'pending' ? 'table-warning' : 'table-success' ?>">
                        <td><?= $transaction['reference_id'] ?></td>
                        <td><?= $transaction['first_name'] . " " . $transaction['last_name'] ?></td>
                        <td><?= $transaction['email'] ?></td>
                        <td><?= $transaction['amount'] ?></td>
                        <td class="<?= $transaction['status'] == 'pending' ? 'bg-warning' : 'bg-success' ?> fw-medium text-end text-uppercase text-white"><?= $transaction['status'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <nav class="d-flex justify-content-center align-items-center">
                <?= $pagination ?>
            </nav>
        </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Charts Grid -->
        <div class="row chart-grid">
            <!-- First Column -->
            <div class="col-md-12 mb-4">
                <div class="chart-card">
                    <canvas id="dailyTransactionsChart"></canvas>
                </div>
            </div>

            <!-- Second Column -->
            <div class="col-md-7 mb-4">
                <div class="chart-card">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Third Column -->
            <div class="col-md-5 mb-4">
                <div class="chart-card">
                    <canvas id="transactionStatusChart"></canvas>
                </div>
            </div>

            <!-- Fourth Column -->
            <div class="col-md-12 mb-4">
                <div class="chart-card">
                    <canvas id="ticketPricesChart"></canvas>
                </div>
            </div>

        </div>
    </div>
</section>


<?php $this->end() ?>

<?php $this->start("script") ?>
<script>
    const chartData = <?= json_encode($chartData) ?>;
    const colors = {
        primary: '#1a5d1a',
        secondary: '#4a7856',
        accent: '#7dbb7d',
        text: '#2c3e50'
    };

    // Revenue by Ticket Type (Bar + Line Combo)
    new Chart(document.getElementById('revenueChart'), {
        type: 'bar',
        data: {
            labels: chartData.revenueByTicket.map(i => i.type),
            datasets: [{
                label: 'Revenue',
                data: chartData.revenueByTicket.map(i => i.total_revenue),
                backgroundColor: colors.primary,
                yAxisID: 'y'
            }, {
                label: 'Sales Count',
                data: chartData.revenueByTicket.map(i => i.sales_count),
                borderColor: colors.accent,
                type: 'line',
                yAxisID: 'y1'
            }]
        },
        options: chartOptions('Revenue & Sales by Ticket Type', true)
    });

    // Transaction Status (Doughnut)
    new Chart(document.getElementById('transactionStatusChart'), {
        type: 'doughnut',
        data: {
            labels: chartData.transactionStatus.map(i => i.status),
            datasets: [{
                data: chartData.transactionStatus.map(i => i.count),
                backgroundColor: [colors.primary, colors.secondary, colors.accent],
                hoverOffset: 10
            }]
        },
        options: chartOptions('Transaction Status Distribution')
    });

    // Ticket Prices (Horizontal Bar)
    new Chart(document.getElementById('ticketPricesChart'), {
        type: 'bar',
        data: {
            labels: chartData.ticketPrices.map(i => i.type),
            datasets: [{
                label: 'Price',
                data: chartData.ticketPrices.map(i => i.price),
                backgroundColor: colors.secondary
            }]
        },
        options: chartOptions('Ticket Price Distribution', false, true)
    });

    // Daily Transactions (Time Series)
    new Chart(document.getElementById('dailyTransactionsChart'), {
        type: 'line',
        data: {
            labels: chartData.dailyTransactions.map(i => i.date),
            datasets: [{
                label: 'Daily Revenue',
                data: chartData.dailyTransactions.map(i => i.daily_total),
                borderColor: colors.primary,
                tension: 0.2,
                fill: true,
                backgroundColor: `${colors.primary}20`
            }]
        },
        options: chartOptions('30-Day Revenue Trend', true, false, 'time')
    });

    function chartOptions(title, showDoubleY = false, horizontal = false, xType = 'category') {
        return {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: title,
                    color: colors.text,
                    font: { size: 16 }
                },
                legend: { position: 'bottom' }
            },
            scales: {
                x: {
                    type: xType,
                    grid: { display: false },
                    ticks: {

                        color: colors.text
                    }
                },
                y: {
                    display: !horizontal,
                    position: 'left',
                    grid: { color: '#f5f5f5' },
                    ticks: { color: colors.text },
                    title: {
                        display: showDoubleY,
                        text: 'Revenue',
                        color: colors.text
                    }
                },
                y1: {
                    display: showDoubleY,
                    position: 'right',
                    grid: { drawOnChartArea: false },
                    ticks: { color: colors.text },
                    title: {
                        display: showDoubleY,
                        text: 'Sales Count',
                        color: colors.text
                    }
                }
            }
        };
    }
</script>
<?php $this->end() ?>