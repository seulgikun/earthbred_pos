<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <title>Earthbred - Order Queuing</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= asset('css/pos.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/queue.css') ?>">
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo-section">
                <h1 class="logo-main">earthbred</h1>
                <p class="logo-sub">Coffee Studio</p>
            </div>
            
            <div class="user-profile">
                <i class="fa-solid fa-circle-user profile-icon"></i>
                <div class="user-info">
                    <p class="user-name">Aries Marolina</p>
                    <p class="user-id">Staff 001</p>
                </div>
            </div>

            <nav class="menu-section">
                <h3 class="menu-heading">NAVIGATION</h3>
                <ul class="menu-list">
                    <li class="menu-item" onclick="window.location.href='/Earthbred/backend/public/pos'">
                        <span class="menu-icon">🍽️</span> POS View
                    </li>
                    <li class="menu-item active" style="border-top: 1px solid #e5d9c5; margin-top: 0.5rem; padding-top: 1rem;">
                        <span class="menu-icon">📋</span> Order Queuing
                    </li>
                </ul>
            </nav>

            <div class="clock-out" onclick="window.location.href='/Earthbred/backend/public/login'">
                <i class="fa-solid fa-power-off"></i> Clock Out
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <header class="top-header queue-header">
                <h2>Order Queue (Today)</h2>
                <div class="daily-total">
                    <span>TOTAL SALES:</span>
                    <strong>₱ <?= number_format($totalSales, 2) ?></strong>
                </div>
            </header>

            <div class="queue-container">
                <?php if (count($orders) === 0): ?>
                    <div class="empty-queue">
                        <i class="fa-solid fa-clipboard-check"></i>
                        <p>No orders placed today.</p>
                    </div>
                <?php else: ?>
                    <div class="orders-grid">
                        <?php foreach($orders as $order): ?>
                            <div class="order-card status-<?= $order->status ?>" id="order-card-<?= $order->id ?>">
                                <div class="order-header">
                                    <div>
                                        <h3>Order #<?= $order->id ?></h3>
                                        <span class="order-time"><?= $order->created_at->format('h:i A') ?></span>
                                    </div>
                                    <span class="badge badge-<?= $order->status ?>"><?= ucfirst($order->status) ?></span>
                                </div>
                                
                                <div class="order-items">
                                    <?php foreach($order->items as $item): ?>
                                        <div class="order-item">
                                            <span class="qty"><?= $item->quantity ?>x</span>
                                            <div class="item-details">
                                                <span class="name"><?= $item->product_name ?></span>
                                                <?php if($item->customer_name): ?>
                                                    <span class="customer"><i class="fa-solid fa-user"></i> <?= $item->customer_name ?></span>
                                                <?php endif; ?>
                                                <?php if(!empty($item->addons)): ?>
                                                    <span class="addons">+ <?= implode(', ', $item->addons) ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <span class="price">₱ <?= number_format($item->item_total, 2) ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <div class="order-footer">
                                    <div class="order-total-info">
                                        <span>Total:</span>
                                        <strong>₱ <?= number_format($order->total, 2) ?></strong>
                                    </div>
                                    <div class="order-actions">
                                        <?php if($order->status === 'pending'): ?>
                                            <button class="btn-void" onclick="updateOrderStatus(<?= $order->id ?>, 'void')"><i class="fa-solid fa-ban"></i> Void</button>
                                            <button class="btn-complete" onclick="updateOrderStatus(<?= $order->id ?>, 'completed')"><i class="fa-solid fa-check"></i> Complete</button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script>
        async function updateOrderStatus(orderId, status) {
            if (status === 'void' && !confirm('Are you sure you want to VOID this order? This cannot be undone.')) {
                return;
            }

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const response = await fetch(`/Earthbred/backend/public/orders/${orderId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ status: status })
                });

                const result = await response.json();
                if (result.success) {
                    window.location.reload();
                } else {
                    alert('Error updating status');
                }
            } catch (e) {
                console.error(e);
                alert('Connection error');
            }
        }
    </script>
</body>
</html>
