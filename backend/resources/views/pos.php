<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Earthbred - POS</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= asset('css/pos.css') ?>">
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
                <h3 class="menu-heading">MENU</h3>
                <ul class="menu-list">
                    <li class="menu-item active" data-filter="all">
                        <span class="menu-icon">🍽️</span> All Items
                    </li>
                    <li class="menu-item" data-filter="coffee">
                        <span class="menu-icon">☕</span> Coffee
                    </li>
                    <li class="menu-item" data-filter="non-coffee">
                        <span class="menu-icon">🍵</span> Non-Coffee
                    </li>
                    <li class="menu-item" data-filter="lemonade">
                        <span class="menu-icon">🍹</span> Lemonade
                    </li>
                    <li class="menu-item" data-filter="rice-bowls">
                        <span class="menu-icon">🍲</span> Rice Bowls
                    </li>
                    <li class="menu-item" data-filter="chicken-tenders">
                        <span class="menu-icon">🍗</span> Chicken Tenders
                    </li>
                    <li class="menu-item" data-filter="shift-notes">
                        <span class="menu-icon">📝</span> Shift Notes
                    </li>
                    <li class="menu-item" onclick="window.location.href='/Earthbred/backend/public/queue'" style="border-top: 1px solid #e5d9c5; margin-top: 0.5rem; padding-top: 1rem;">
                        <span class="menu-icon">📋</span> Order Queuing
                    </li>
                </ul>
            </nav>

            <div class="clock-out">
                <i class="fa-solid fa-power-off"></i> Clock Out
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <!-- Header -->
            <header class="top-header">
                <div class="header-spacer"></div>
                <button class="order-btn">
                    <i class="fa-solid fa-cart-shopping"></i> Order
                </button>
            </header>

            <!-- Product Grid -->
            <div class="product-grid">
                
                <!-- Product Card 1 -->
                <div class="product-card" data-category="coffee">
                    <button class="add-btn"><i class="fa-solid fa-plus"></i></button>
                    <img src="<?= asset('images/americano.png') ?>" alt="Americano" class="product-image">
                    <h4 class="product-name">Americano</h4>
                    <p class="product-price">₱ 95</p>
                </div>

                <!-- Product Card 2 -->
                <div class="product-card" data-category="coffee">
                    <button class="add-btn"><i class="fa-solid fa-plus"></i></button>
                    <img src="<?= asset('images/americano.png') ?>" alt="Cafe Latte" class="product-image">
                    <h4 class="product-name">Cafe Latte</h4>
                    <p class="product-price">₱ 95</p>
                </div>

                <!-- Product Card 3 -->
                <div class="product-card" data-category="coffee">
                    <button class="add-btn"><i class="fa-solid fa-plus"></i></button>
                    <img src="<?= asset('images/americano.png') ?>" alt="Cafe Mocha" class="product-image">
                    <h4 class="product-name">Cafe Mocha</h4>
                    <p class="product-price">₱ 89</p>
                </div>

                <!-- Product Card 4 -->
                <div class="product-card" data-category="non-coffee">
                    <button class="add-btn"><i class="fa-solid fa-plus"></i></button>
                    <img src="<?= asset('images/strawberry.png') ?>" alt="Matcha Drink" class="product-image" style="filter: hue-rotate(120deg);">
                    <h4 class="product-name">Matcha Drink</h4>
                    <p class="product-price">₱ 99</p>
                </div>

                <!-- Product Card 5 -->
                <div class="product-card" data-category="lemonade">
                    <button class="add-btn"><i class="fa-solid fa-plus"></i></button>
                    <img src="<?= asset('images/strawberry.png') ?>" alt="Sweetened Lemonade" class="product-image" style="filter: hue-rotate(60deg);">
                    <h4 class="product-name">Sweetened</h4>
                    <p class="product-price">₱ 95</p>
                </div>

                <!-- Product Card 6 -->
                <div class="product-card" data-category="lemonade">
                    <button class="add-btn"><i class="fa-solid fa-plus"></i></button>
                    <img src="<?= asset('images/strawberry.png') ?>" alt="Strawberry Drink" class="product-image">
                    <h4 class="product-name">Strawberry Drink</h4>
                    <p class="product-price">₱ 95</p>
                </div>

                <!-- Product Card 7 -->
                <div class="product-card" data-category="lemonade">
                    <button class="add-btn"><i class="fa-solid fa-plus"></i></button>
                    <img src="<?= asset('images/strawberry.png') ?>" alt="Strawberry Lemonade" class="product-image" style="filter: saturate(1.5) hue-rotate(-10deg);">
                    <h4 class="product-name">Strawberry</h4>
                    <p class="product-price">₱ 109</p>
                </div>

                <!-- Product Card 8 -->
                <div class="product-card" data-category="rice-bowls">
                    <button class="add-btn"><i class="fa-solid fa-plus"></i></button>
                    <img src="<?= asset('images/rice_bowl.png') ?>" alt="Chicken Ala King" class="product-image">
                    <h4 class="product-name">Chicken Ala King</h4>
                    <p class="product-price">₱ 109</p>
                </div>

                <!-- Product Card 9 -->
                <div class="product-card" data-category="rice-bowls">
                    <button class="add-btn"><i class="fa-solid fa-plus"></i></button>
                    <img src="<?= asset('images/rice_bowl.png') ?>" alt="Sweet Garlic Longganisa" class="product-image">
                    <h4 class="product-name">Sweet Garlic<br>Longganisa</h4>
                    <p class="product-price">₱ 95</p>
                </div>

                <!-- Product Card 10 -->
                <div class="product-card" data-category="rice-bowls">
                    <button class="add-btn"><i class="fa-solid fa-plus"></i></button>
                    <img src="<?= asset('images/rice_bowl.png') ?>" alt="Chicken Fried Rice" class="product-image">
                    <h4 class="product-name">Chicken Fried Rice</h4>
                    <p class="product-price">₱ 130</p>
                </div>

                <!-- Product Card 11 -->
                <div class="product-card" data-category="rice-bowls">
                    <button class="add-btn"><i class="fa-solid fa-plus"></i></button>
                    <img src="<?= asset('images/rice_bowl.png') ?>" alt="Cheezy Bacon" class="product-image">
                    <h4 class="product-name" style="color: #6a3a30;">Cheezy Bacon</h4>
                    <p class="product-price" style="color: #6a3a30;">₱ 130</p>
                </div>

                <!-- Product Card 12 -->
                <div class="product-card" data-category="rice-bowls">
                    <button class="add-btn"><i class="fa-solid fa-plus"></i></button>
                    <img src="<?= asset('images/rice_bowl.png') ?>" alt="Beef Tapa" class="product-image">
                    <h4 class="product-name">Beef Tapa</h4>
                    <p class="product-price">₱ 135</p>
                </div>

            </div>
        </main>
    </div>

    <!-- Product Modal -->
    <div class="modal-overlay" id="productModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalProductName">Product Name</h3>
                <button class="close-modal-btn" id="closeModalBtn"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <div class="customer-section">
                    <h4>Customer Name (Optional)</h4>
                    <input type="text" id="customerNameInput" class="customer-input" placeholder="e.g., John Doe">
                </div>

                <div class="quantity-section">
                    <h4>Quantity</h4>
                    <div class="quantity-controls">
                        <button class="qty-btn" id="qtyMinus"><i class="fa-solid fa-minus"></i></button>
                        <input type="number" class="qty-input" id="qtyInput" value="1" min="1" readonly>
                        <button class="qty-btn" id="qtyPlus"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>

                <div class="addons-section">
                    <h4>Add-ons</h4>
                    <div class="addon-item">
                        <label class="addon-label">
                            <input type="checkbox" class="addon-checkbox" data-price="30" value="Extra Espresso Shot">
                            <span class="custom-checkbox"></span>
                            Extra Espresso Shot (+₱ 30)
                        </label>
                    </div>
                    <div class="addon-item">
                        <label class="addon-label">
                            <input type="checkbox" class="addon-checkbox" data-price="40" value="Oat Milk">
                            <span class="custom-checkbox"></span>
                            Oat Milk (+₱ 40)
                        </label>
                    </div>
                    <div class="addon-item">
                        <label class="addon-label">
                            <input type="checkbox" class="addon-checkbox" data-price="0" value="Less Ice">
                            <span class="custom-checkbox"></span>
                            Less Ice (Free)
                        </label>
                    </div>
                    <div class="addon-item">
                        <label class="addon-label">
                            <input type="checkbox" class="addon-checkbox" data-price="20" value="Extra Sweet">
                            <span class="custom-checkbox"></span>
                            Extra Sweet (+₱ 20)
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="add-to-order-btn" id="addToOrderBtn">
                    Add to Order <span id="modalTotalPrice">₱ 0</span>
                </button>
            </div>
        </div>
    </div>
    
    <script src="<?= asset('js/pos.js') ?>"></script>
</body>
</html>
