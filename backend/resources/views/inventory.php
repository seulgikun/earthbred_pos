<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Earthbred - Inventory Management</title>
    <meta name="description" content="Earthbred Coffee Studio Inventory Management System. Monitor stock levels, track consumption, and generate formal inventory reports.">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= asset('css/inventory.css') ?>">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <!-- html2pdf -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
</head>
<body>
<div class="inv-app">

    <!-- =========================================
         SIDEBAR
    ========================================= -->
    <aside class="inv-sidebar">
        <div class="inv-logo-section">
            <h1 class="inv-logo-main">earthbred</h1>
            <p class="inv-logo-sub">Coffee Studio</p>
        </div>

        <div class="inv-user-profile">
            <i class="fa-solid fa-circle-user inv-profile-icon"></i>
            <div class="inv-user-info">
                <p class="inv-user-name">Manager</p>
                <p class="inv-user-id">Inventory System</p>
            </div>
        </div>

        <nav class="inv-nav">
            <h3 class="inv-nav-heading">NAVIGATION</h3>
            <ul class="inv-nav-list">
                <li class="inv-nav-item" onclick="window.location.href='/Earthbred/backend/public/pos'">
                    <i class="fa-solid fa-cash-register inv-nav-icon"></i> POS Terminal
                </li>
                <li class="inv-nav-item active">
                    <i class="fa-solid fa-boxes-stacked inv-nav-icon"></i> Inventory
                </li>
            </ul>
        </nav>

        <div class="inv-sidebar-footer">
            <div class="inv-check-time" id="invCheckTime">
                <i class="fa-solid fa-clock"></i>
                <span id="currentTimeDisplay">--:--</span>
            </div>
            <div class="inv-shift-indicator" id="shiftIndicator">
                <span class="inv-shift-dot"></span>
                <span id="shiftLabel">Loading shift...</span>
            </div>
        </div>
    </aside>

    <!-- =========================================
         MAIN CONTENT
    ========================================= -->
    <main class="inv-main">

        <!-- Top Header -->
        <header class="inv-header">
            <div class="inv-header-left">
                <h2 class="inv-page-title">Inventory Management</h2>
                <p class="inv-page-subtitle" id="invDateLabel">Loading...</p>
            </div>
            <div class="inv-header-actions">
                <button class="inv-btn inv-btn-secondary" id="addItemBtn">
                    <i class="fa-solid fa-plus"></i> Add Item
                </button>
                <button class="inv-btn inv-btn-primary" id="exportPdfBtn">
                    <i class="fa-solid fa-file-pdf"></i> Export PDF
                </button>
            </div>
        </header>

        <!-- ---- KPI Cards ---- -->
        <section class="inv-kpi-row">
            <div class="inv-kpi-card">
                <div class="inv-kpi-icon" style="background:linear-gradient(135deg,#6a3a30,#9c5a4a);">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
                <div class="inv-kpi-info">
                    <p class="inv-kpi-label">Total Items</p>
                    <p class="inv-kpi-value" id="kpiTotal">—</p>
                </div>
            </div>
            <div class="inv-kpi-card">
                <div class="inv-kpi-icon" style="background:linear-gradient(135deg,#1a7a4a,#2da56a);">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div class="inv-kpi-info">
                    <p class="inv-kpi-label">In Stock</p>
                    <p class="inv-kpi-value" id="kpiInStock">—</p>
                </div>
            </div>
            <div class="inv-kpi-card">
                <div class="inv-kpi-icon" style="background:linear-gradient(135deg,#b06000,#e5a000);">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div class="inv-kpi-info">
                    <p class="inv-kpi-label">Low Stock</p>
                    <p class="inv-kpi-value" id="kpiLow">—</p>
                </div>
            </div>
            <div class="inv-kpi-card">
                <div class="inv-kpi-icon" style="background:linear-gradient(135deg,#c5221f,#e84040);">
                    <i class="fa-solid fa-circle-xmark"></i>
                </div>
                <div class="inv-kpi-info">
                    <p class="inv-kpi-label">Out of Stock</p>
                    <p class="inv-kpi-value" id="kpiOut">—</p>
                </div>
            </div>
        </section>

        <!-- ---- Main Grid: Table + Alerts ---- -->
        <section class="inv-content-grid">

            <!-- LEFT: Table -->
            <div class="inv-table-panel">
                <div class="inv-table-header">
                    <h3 class="inv-section-title"><i class="fa-solid fa-table-list"></i> Stock Register</h3>
                    <div class="inv-search-wrap">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="inventorySearch" placeholder="Search items..." class="inv-search-input">
                    </div>
                </div>
                <div class="inv-table-container">
                    <table class="inv-table" id="inventoryTable">
                        <thead>
                            <tr>
                                <th>ITEM</th>
                                <th>CATEGORY</th>
                                <th>QTY</th>
                                <th>STATUS</th>
                                <th>Consumption Log / Issue Type</th>
                                <th style="text-align:center;">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody id="inventoryTableBody">
                            <tr><td colspan="6" class="inv-loading-row"><i class="fa-solid fa-spinner fa-spin"></i> Loading inventory...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- RIGHT: Alerts + Charts -->
            <div class="inv-side-panel">

                <!-- Stock Alert Assistant -->
                <div class="inv-alert-card">
                    <div class="inv-alert-card-header">
                        <div class="inv-alert-title-group">
                            <i class="fa-solid fa-bell-concierge inv-alert-icon"></i>
                            <span class="inv-alert-title">Stock Alert Assistant</span>
                        </div>
                        <span class="inv-badge">Rule-Based</span>
                    </div>
                    <p class="inv-alert-intro">Real-time stock observations &amp; recommendations:</p>
                    <ul class="inv-alert-list" id="aiAlertsList">
                        <li class="inv-alert-item"><i class="fa-solid fa-spinner fa-spin"></i> Analyzing stock levels...</li>
                    </ul>
                </div>

                <!-- Low Stock Banners -->
                <div id="lowStockBanners" class="inv-banners-wrap"></div>

                <!-- Doughnut Chart -->
                <div class="inv-chart-card">
                    <h4 class="inv-chart-title"><i class="fa-solid fa-chart-pie"></i> Stock Status Overview</h4>
                    <div class="inv-chart-wrap">
                        <canvas id="stockDoughnutChart"></canvas>
                    </div>
                </div>

            </div>
        </section>



    </main>
</div>

<!-- =========================================
     MODAL: ADD STOCK
========================================= -->
<div class="inv-modal-overlay" id="addStockModal">
    <div class="inv-modal">
        <div class="inv-modal-header">
            <h3><i class="fa-solid fa-plus-circle"></i> Add Stock</h3>
            <button class="inv-modal-close" id="closeAddStockModal"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="addStockForm" class="inv-modal-body">
            <input type="hidden" id="addStockId">
            <div class="inv-form-group">
                <label>Item Name</label>
                <input type="text" id="addStockItemName" class="inv-input" readonly>
            </div>
            <div class="inv-form-group">
                <label>Quantity to Add</label>
                <input type="number" id="addStockQty" class="inv-input" min="1" required placeholder="e.g. 10">
            </div>
            <button type="submit" class="inv-submit-btn">
                <i class="fa-solid fa-check"></i> Save Stock
            </button>
        </form>
    </div>
</div>

<!-- =========================================
     MODAL: EDIT STOCK
========================================= -->
<div class="inv-modal-overlay" id="editStockModal">
    <div class="inv-modal">
        <div class="inv-modal-header">
            <h3><i class="fa-solid fa-pen-to-square"></i> Edit Stock</h3>
            <button class="inv-modal-close" id="closeEditStockModal"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="editStockForm" class="inv-modal-body">
            <input type="hidden" id="editStockId">
            <div class="inv-form-group">
                <label>Item Name</label>
                <input type="text" id="editStockItemName" class="inv-input" readonly>
            </div>
            <div class="inv-form-group">
                <label>Corrected Quantity</label>
                <input type="number" id="editStockQty" class="inv-input" min="0" required placeholder="Enter correct count">
            </div>
            <div class="inv-form-group">
                <label>Consumption Log / Issue Type</label>
                <select id="editStockIssueType" class="inv-input inv-select" required>
                    <option value="Morning Check">☀️ Morning Check</option>
                    <option value="Evening Check">🌙 Evening Check</option>
                    <option value="Restocked">📦 Restocked</option>
                    <option value="Spillage">💧 Spillage</option>
                    <option value="Expired">⚠️ Expired</option>
                    <option value="Incorrect Entry">✏️ Incorrect Entry</option>
                </select>
            </div>
            <div class="inv-form-group">
                <label>Notes / Explanation <span style="color:#aaa;font-weight:400;">(Optional)</span></label>
                <input type="text" id="editStockNotes" class="inv-input" placeholder="e.g. Adjusted from morning audit">
            </div>
            <button type="submit" class="inv-submit-btn inv-submit-btn-edit">
                <i class="fa-solid fa-check"></i> Update Stock
            </button>
        </form>
    </div>
</div>

<!-- =========================================
     MODAL: ADD NEW ITEM
========================================= -->
<div class="inv-modal-overlay" id="addItemModal">
    <div class="inv-modal">
        <div class="inv-modal-header">
            <h3><i class="fa-solid fa-cubes"></i> Add New Inventory Item</h3>
            <button class="inv-modal-close" id="closeAddItemModal"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="addItemForm" class="inv-modal-body">
            <div class="inv-form-group">
                <label>Item Name</label>
                <input type="text" id="newItemName" class="inv-input" required placeholder="e.g. Oat Milk">
            </div>
            <div class="inv-form-group">
                <label>Category</label>
                <select id="newItemCategory" class="inv-input inv-select" required>
                    <option value="">— Select Category —</option>
                    <option value="Milk">Milk</option>
                    <option value="Syrup">Syrup</option>
                    <option value="Coffee Beans">Coffee Beans</option>
                    <option value="Cups & Packaging">Cups &amp; Packaging</option>
                    <option value="Ingredients">Ingredients</option>
                    <option value="Supplies">Supplies</option>
                    <option value="Food">Food</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="inv-form-group">
                <label>Initial Quantity</label>
                <input type="number" id="newItemQty" class="inv-input" min="0" required placeholder="e.g. 50">
            </div>
            <div class="inv-form-group">
                <label>Low Stock Threshold <span style="color:#aaa;font-weight:400;">(Alert when below)</span></label>
                <input type="number" id="newItemThreshold" class="inv-input" min="1" required placeholder="e.g. 10">
            </div>
            <button type="submit" class="inv-submit-btn">
                <i class="fa-solid fa-plus"></i> Add to Inventory
            </button>
        </form>
    </div>
</div>

<!-- =========================================
     PDF REPORT TEMPLATE (hidden)
========================================= -->
<div id="pdfReportTemplate" style="display:none;">
    <div id="pdfContent" class="pdf-report">
        <!-- Header -->
        <div class="pdf-header">
            <div class="pdf-brand">
                <h1 class="pdf-brand-name">earthbred</h1>
                <p class="pdf-brand-sub">Coffee Studio</p>
            </div>
            <div class="pdf-report-meta">
                <h2 class="pdf-report-title">INVENTORY REPORT</h2>
                <p class="pdf-report-date" id="pdfReportDate"></p>
            </div>
        </div>
        <div class="pdf-divider"></div>

        <!-- Summary KPIs -->
        <div class="pdf-summary-row">
            <div class="pdf-summary-box">
                <p class="pdf-summary-label">Total Items</p>
                <p class="pdf-summary-val" id="pdfKpiTotal">0</p>
            </div>
            <div class="pdf-summary-box pdf-summary-green">
                <p class="pdf-summary-label">In Stock</p>
                <p class="pdf-summary-val" id="pdfKpiIn">0</p>
            </div>
            <div class="pdf-summary-box pdf-summary-yellow">
                <p class="pdf-summary-label">Low Stock</p>
                <p class="pdf-summary-val" id="pdfKpiLow">0</p>
            </div>
            <div class="pdf-summary-box pdf-summary-red">
                <p class="pdf-summary-label">Out of Stock</p>
                <p class="pdf-summary-val" id="pdfKpiOut">0</p>
            </div>
        </div>

        <!-- Charts row -->
        <div class="pdf-charts-row">
            <div class="pdf-chart-box" style="width: 100%; max-width: 350px; margin: 0 auto;">
                <h3 class="pdf-chart-label">Stock Status Overview</h3>
                <img id="pdfDoughnutImg" class="pdf-chart-img" src="" alt="Doughnut Chart">
            </div>
        </div>

        <!-- Inventory Table -->
        <h3 class="pdf-table-heading">Stock Register</h3>
        <table class="pdf-table" id="pdfInventoryTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>ITEM NAME</th>
                    <th>CATEGORY</th>
                    <th>QTY</th>
                    <th>STATUS</th>
                    <th>LAST ISSUE TYPE</th>
                </tr>
            </thead>
            <tbody id="pdfTableBody"></tbody>
        </table>

        <!-- Low Stock Section -->
        <div id="pdfAlertsSection" style="margin-top:20px;">
            <h3 class="pdf-table-heading">⚠️ Low Stock Alerts</h3>
            <ul id="pdfAlertsList" class="pdf-alerts-list"></ul>
        </div>

        <!-- Footer -->
        <div class="pdf-footer">
            <p>Generated by Earthbred POS System &bull; <span id="pdfFooterDate"></span></p>
            <p class="pdf-footer-note">This report is for internal use only. Please review and act on low-stock alerts promptly.</p>
        </div>
    </div>
</div>

<script src="<?= asset('js/inventory.js') ?>?v=<?= time() ?>"></script>
</body>
</html>
