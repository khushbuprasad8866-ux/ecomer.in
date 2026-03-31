<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FV FABLY VALOR - Premium Fashion Store</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        /* NAVBAR */
        nav {
            height: 70px;
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.3);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            background: linear-gradient(45deg, #ff6b6b, #feca57);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .menu span {
            margin: 0 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 8px 16px;
            border-radius: 20px;
        }

        .menu span:hover {
            background: rgba(255, 107, 107, 0.2);
            color: #ff6b6b;
            transform: translateY(-2px);
        }

        .menu span.active {
            background: rgba(255, 107, 107, 0.3);
            color: #ff6b6b;
        }

        /* MAIN CONTAINER */
        .main-container {
            background: white;
            margin: 20px;
            border-radius: 20px;
            min-height: calc(100vh - 110px);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        /* SECTIONS */
        .section {
            display: none;
            padding: 40px;
            animation: fadeIn 0.5s ease;
        }

        .section.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* HOME SECTION */
        .hero {
            text-align: center;
            padding: 60px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px;
            margin-bottom: 40px;
        }

        .hero h1 {
            font-size: 48px;
            margin-bottom: 20px;
            animation: slideDown 0.8s ease;
        }

        .hero p {
            font-size: 20px;
            opacity: 0.9;
            animation: slideUp 0.8s ease;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-30px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* PRODUCT GRID */
        .products {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            padding: 20px;
        }

        .card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }

        .card img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .card:hover img {
            transform: scale(1.1);
        }

        .card-content {
            padding: 20px;
        }

        .card-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .card-price {
            color: #27ae60;
            font-size: 24px;
            font-weight: bold;
        }

        /* PRODUCT VIEW MODAL */
        .product-view {
            position: fixed;
            bottom: -100%;
            left: 0;
            width: 100%;
            height: 85%;
            background: white;
            border-top-left-radius: 30px;
            border-top-right-radius: 30px;
            box-shadow: 0 -10px 40px rgba(0, 0, 0, 0.3);
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            padding: 30px;
            overflow-y: auto;
            z-index: 1000;
        }

        .product-view.active {
            bottom: 0;
        }

        .close {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 24px;
            cursor: pointer;
            background: #ff6b6b;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .close:hover {
            background: #ff5252;
            transform: rotate(90deg);
        }

        .product-header {
            display: flex;
            gap: 30px;
            margin-bottom: 30px;
        }

        .product-image {
            flex: 1;
            max-width: 400px;
        }

        .product-image img {
            width: 100%;
            border-radius: 15px;
        }

        .product-details {
            flex: 1;
        }

        .product-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
        }

        .product-price {
            color: #27ae60;
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        /* SIZE BUTTONS */
        .sizes {
            margin-bottom: 30px;
        }

        .sizes h3 {
            margin-bottom: 15px;
            color: #333;
        }

        .size-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .size-btn {
            padding: 12px 20px;
            border-radius: 25px;
            border: 2px solid #ddd;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .size-btn:hover {
            border-color: #ff6b6b;
            background: #ff6b6b;
            color: white;
            transform: translateY(-2px);
        }

        .size-btn.selected {
            background: #ff6b6b;
            color: white;
            border-color: #ff6b6b;
        }

        /* IMAGE GALLERY */
        .gallery {
            margin-bottom: 30px;
        }

        .gallery h3 {
            margin-bottom: 15px;
            color: #333;
        }

        .gallery-images {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding: 10px 0;
        }

        .gallery img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .gallery img:hover {
            border-color: #ff6b6b;
            transform: scale(1.1);
        }

        /* ORDER FORM */
        .order-form {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 15px;
            margin-top: 30px;
        }

        .order-form h3 {
            margin-bottom: 20px;
            color: #333;
            font-size: 24px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #ff6b6b;
            box-shadow: 0 0 0 3px rgba(255, 107, 107, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .order-btn {
            background: linear-gradient(135deg, #ff6b6b 0%, #ff5252 100%);
            color: white;
            border: none;
            padding: 15px 40px;
            border-radius: 30px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 20px;
        }

        .order-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(255, 107, 107, 0.3);
        }

        /* SUCCESS MESSAGE */
        .success-message {
            display: none;
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            margin-top: 20px;
        }

        .success-message.active {
            display: block;
            animation: bounceIn 0.6s ease;
        }

        @keyframes bounceIn {
            0% {
                transform: scale(0.3);
                opacity: 0;
            }

            50% {
                transform: scale(1.05);
            }

            70% {
                transform: scale(0.9);
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .whatsapp-btn {
            background: #25D366;
            color: white;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 25px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-top: 15px;
            transition: all 0.3s ease;
        }

        .whatsapp-btn:hover {
            background: #128C7E;
            transform: translateY(-2px);
        }

        /* LOADING SPINNER */
        .loading {
            display: none;
            text-align: center;
            padding: 20px;
        }

        .loading.active {
            display: block;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #ff6b6b;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* MULTI-STEP MODAL STYLES */
        .order-step {
            padding: 20px;
            animation: fadeIn 0.3s ease;
        }

        .step-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .step-indicator {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 15px;
        }

        .step-indicator span {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #666;
            transition: all 0.3s ease;
        }

        .step-indicator span.active {
            background: linear-gradient(135deg, #ff6b6b 0%, #ff5252 100%);
            color: white;
            transform: scale(1.1);
        }

        .step-indicator span.completed {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            color: white;
        }

        .product-display {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            align-items: start;
        }

        .product-image img {
            width: 100%;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .product-info h3 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #333;
        }

        .product-info .price {
            font-size: 28px;
            color: #27ae60;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .product-info p {
            color: #666;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .sizes,
        .quantity {
            margin-bottom: 20px;
        }

        .sizes h4,
        .quantity h4 {
            margin-bottom: 10px;
            color: #333;
        }

        .size-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .size-btn {
            padding: 10px 15px;
            border: 2px solid #ddd;
            background: white;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .size-btn:hover,
        .size-btn.selected {
            border-color: #ff6b6b;
            background: #ff6b6b;
            color: white;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: 500;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #ff6b6b;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .payment-section {
            max-width: 600px;
            margin: 0 auto;
        }

        .order-summary {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .order-summary h3 {
            margin-bottom: 15px;
            color: #333;
        }

        .total-amount {
            font-size: 20px;
            color: #ff6b6b;
            font-weight: bold;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #ddd;
        }

        .payment-methods h3 {
            margin-bottom: 20px;
            color: #333;
        }

        .payment-btn {
            transition: all 0.3s ease;
            text-align: center;
        }

        .payment-btn:hover,
        .payment-btn.selected {
            border-color: #ff6b6b;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.3);
        }

        .payment-btn h4 {
            margin: 10px 0 5px 0;
            color: #333;
        }

        .payment-btn p {
            margin: 0;
            color: #666;
            font-size: 14px;
        }

        .back-btn:hover {
            background: #5a6268 !important;
        }

        .next-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.3);
        }

        .confirmation-section {
            max-width: 600px;
            margin: 0 auto;
        }

        .order-details {
            text-align: left;
        }

        .order-details h3 {
            margin-bottom: 15px;
            color: #333;
        }

        @media (max-width: 768px) {
            .product-display {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .payment-methods>div {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .product-header {
                flex-direction: column;
            }

            .hero h1 {
                font-size: 32px;
            }

            nav {
                padding: 0 15px;
            }

            .menu span {
                margin: 0 10px;
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <nav>
        <div class="logo">FV FABLY VALOR</div>
        <div class="menu">
            <span onclick="showSection('home')" class="nav-item active">
                <i class="fas fa-home"></i> HOME
            </span>
            <span onclick="showSection('about')" class="nav-item">
                <i class="fas fa-info-circle"></i> ABOUT
            </span>
            <span onclick="showSection('product')" class="nav-item">
                <i class="fas fa-shopping-bag"></i> PRODUCTS
            </span>
            <span onclick="showSection('contact')" class="nav-item">
                <i class="fas fa-phone"></i> CONTACT
            </span>
            <span onclick="showAddProduct()" class="nav-item"
                style="background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%); border-radius: 20px; padding: 8px 16px;">
                <i class="fas fa-plus-circle"></i> ADD PRODUCT
            </span>
        </div>
    </nav>

    <div class="main-container">
        <!-- HOME SECTION -->
        <div id="home" class="section active">
            <div class="hero">
                <h1>Welcome to FV FABLY VALOR</h1>
                <p>Premium Fashion Collection at Unbeatable Prices</p>
            </div>

            <div style="text-align: center; padding: 40px;">
                <h2 style="color: #333; margin-bottom: 30px;">Why Choose Us?</h2>
                <div
                    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; margin-bottom: 40px;">
                    <div style="padding: 30px; background: #f8f9fa; border-radius: 15px;">
                        <i class="fas fa-truck" style="font-size: 40px; color: #ff6b6b; margin-bottom: 15px;"></i>
                        <h3>Fast Delivery</h3>
                        <p>Quick delivery across all major cities</p>
                    </div>
                    <div style="padding: 30px; background: #f8f9fa; border-radius: 15px;">
                        <i class="fas fa-shield-alt" style="font-size: 40px; color: #ff6b6b; margin-bottom: 15px;"></i>
                        <h3>Secure Payment</h3>
                        <p>100% secure payment options</p>
                    </div>
                    <div style="padding: 30px; background: #f8f9fa; border-radius: 15px;">
                        <i class="fas fa-undo" style="font-size: 40px; color: #ff6b6b; margin-bottom: 15px;"></i>
                        <h3>Easy Returns</h3>
                        <p>7-day return policy</p>
                    </div>
                </div>

                <!-- Featured Products Section -->
                <div style="margin-top: 60px;">
                    <h2 style="color: #333; margin-bottom: 30px; font-size: 32px;">Featured Products</h2>
                    <div id="homeProducts" class="products"
                        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px;">
                        <!-- Products will be loaded here dynamically -->
                    </div>
                </div>

                <div style="text-align: center; margin-top: 40px;">
                    <h2 style="color: #333; margin-bottom: 20px;">Ready to Order?</h2>
                    <p style="color: #666; margin-bottom: 30px;">Click below to place your order directly</p>
                    <button onclick="showOrderForm()"
                        style="background: linear-gradient(135deg, #ff6b6b 0%, #ff5252 100%); color: white; border: none; padding: 20px 50px; border-radius: 40px; font-size: 20px; font-weight: bold; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 10px 30px rgba(255, 107, 107, 0.3);">
                        <i class="fas fa-shopping-cart"></i> Buy Now
                    </button>
                </div>
            </div>
        </div>

        <!-- PRODUCT SECTION -->
        <div id="product" class="section">
            <h2 style="text-align: center; color: #333; margin-bottom: 40px; font-size: 36px;">Our Premium Collection
            </h2>

            <div id="productGrid" class="products"
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; padding: 20px;">
                <!-- Products will be loaded here dynamically -->
            </div>

            <div id="noProductsMessage" style="text-align: center; padding: 60px 20px; display: none;">
                <div
                    style="background: white; padding: 40px; border-radius: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); max-width: 500px; margin: 0 auto;">
                    <i class="fas fa-shopping-bag" style="font-size: 60px; color: #ff6b6b; margin-bottom: 20px;"></i>
                    <h3 style="color: #333; margin-bottom: 15px;">No Products Available</h3>
                    <p style="color: #666; margin-bottom: 25px;">Products are currently being updated. Please check back
                        soon or use the "Add Product" button to add new items.</p>
                    <button onclick="showAddProduct()"
                        style="background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%); color: white; border: none; padding: 15px 30px; border-radius: 30px; font-size: 16px; font-weight: bold; cursor: pointer;">
                        <i class="fas fa-plus-circle"></i> Add Product
                    </button>
                </div>
            </div>
        </div>

        <!-- ABOUT SECTION -->
        <div id="about" class="section">
            <div class="hero">
                <h1>About FV FABLY VALOR</h1>
                <p>Your Trusted Fashion Partner Since 2020</p>
            </div>

            <div style="padding: 40px;">
                <div
                    style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: center; margin-bottom: 60px;">
                    <div>
                        <h2 style="font-size: 36px; color: #333; margin-bottom: 20px;">Our Story</h2>
                        <p style="font-size: 18px; line-height: 1.8; color: #666; margin-bottom: 20px;">
                            Welcome to FV FABLY VALOR, where fashion meets affordability. We started our journey in 2020
                            with a simple mission: to provide high-quality, trendy fashion to every Indian woman at
                            prices that won't break the bank.
                        </p>
                        <p style="font-size: 18px; line-height: 1.8; color: #666; margin-bottom: 20px;">
                            Our curated collection features the latest in ethnic wear, contemporary fashion, and
                            timeless classics. Each piece is carefully selected to ensure quality, comfort, and style.
                        </p>
                        <div style="display: flex; gap: 30px; margin-top: 30px;">
                            <div style="text-align: center;">
                                <div style="font-size: 36px; font-weight: bold; color: #ff6b6b;">5000+</div>
                                <div style="color: #666;">Happy Customers</div>
                            </div>
                            <div style="text-align: center;">
                                <div style="font-size: 36px; font-weight: bold; color: #ff6b6b;">100+</div>
                                <div style="color: #666;">Unique Designs</div>
                            </div>
                            <div style="text-align: center;">
                                <div style="font-size: 36px; font-weight: bold; color: #ff6b6b;">4.8★</div>
                                <div style="color: #666;">Customer Rating</div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=500&h=400&fit=crop"
                            alt="Fashion Store"
                            style="width: 100%; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
                    </div>
                </div>

                <div
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px; border-radius: 20px; color: white; text-align: center;">
                    <h2 style="font-size: 32px; margin-bottom: 20px;">Our Values</h2>
                    <div
                        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 30px; margin-top: 30px;">
                        <div>
                            <i class="fas fa-gem" style="font-size: 40px; margin-bottom: 15px;"></i>
                            <h3>Quality First</h3>
                            <p>Premium fabrics and craftsmanship</p>
                        </div>
                        <div>
                            <i class="fas fa-heart" style="font-size: 40px; margin-bottom: 15px;"></i>
                            <h3>Customer Love</h3>
                            <p>Your satisfaction is our priority</p>
                        </div>
                        <div>
                            <i class="fas fa-tag" style="font-size: 40px; margin-bottom: 15px;"></i>
                            <h3>Affordable Prices</h3>
                            <p>Fashion that fits your budget</p>
                        </div>
                        <div>
                            <i class="fas fa-truck" style="font-size: 40px; margin-bottom: 15px;"></i>
                            <h3>Fast Delivery</h3>
                            <p>Quick delivery across India</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CONTACT SECTION -->
        <div id="contact" class="section">
            <div class="hero">
                <h1>Get In Touch</h1>
                <p>We're here to help you with all your fashion needs</p>
            </div>

            <div style="padding: 40px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
                    <div>
                        <h2 style="font-size: 32px; color: #333; margin-bottom: 30px;">Contact Information</h2>

                        <div
                            style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); margin-bottom: 20px;">
                            <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 25px;">
                                <div
                                    style="width: 60px; height: 60px; background: linear-gradient(135deg, #ff6b6b 0%, #ff5252 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-phone" style="font-size: 24px; color: white;"></i>
                                </div>
                                <div>
                                    <h3 style="margin-bottom: 5px; color: #333;">Phone</h3>
                                    <p style="font-size: 18px; color: #666; margin: 0;">+91 88662 04293</p>
                                    <p style="font-size: 14px; color: #999; margin: 0;">Available 9 AM - 9 PM</p>
                                </div>
                            </div>

                            <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 25px;">
                                <div
                                    style="width: 60px; height: 60px; background: linear-gradient(135deg, #ff6b6b 0%, #ff5252 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-envelope" style="font-size: 24px; color: white;"></i>
                                </div>
                                <div>
                                    <h3 style="margin-bottom: 5px; color: #333;">Email</h3>
                                    <p style="font-size: 18px; color: #666; margin: 0;">jesuslifemylife@gmail.com</p>
                                    <p style="font-size: 14px; color: #999; margin: 0;">24/7 Support</p>
                                </div>
                            </div>

                            <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 25px;">
                                <div
                                    style="width: 60px; height: 60px; background: linear-gradient(135deg, #ff6b6b 0%, #ff5252 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="fab fa-whatsapp" style="font-size: 24px; color: white;"></i>
                                </div>
                                <div>
                                    <h3 style="margin-bottom: 5px; color: #333;">WhatsApp</h3>
                                    <p style="font-size: 18px; color: #666; margin: 0;">+91 88662 04293</p>
                                    <p style="font-size: 14px; color: #999; margin: 0;">Quick Chat Support</p>
                                </div>
                            </div>
                        </div>

                        <div
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; border-radius: 15px; color: white;">
                            <h3 style="margin-bottom: 15px;">Business Hours</h3>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                <div>
                                    <strong>Monday - Friday:</strong><br>
                                    9:00 AM - 9:00 PM
                                </div>
                                <div>
                                    <strong>Saturday:</strong><br>
                                    10:00 AM - 8:00 PM
                                </div>
                                <div>
                                    <strong>Sunday:</strong><br>
                                    11:00 AM - 6:00 PM
                                </div>
                                <div>
                                    <strong>Emergency:</strong><br>
                                    Available 24/7
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h2 style="font-size: 32px; color: #333; margin-bottom: 30px;">Send Us a Message</h2>

                        <form id="contactForm"
                            style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
                            <div class="form-group">
                                <label for="contactName">Your Name *</label>
                                <input type="text" id="contactName" name="contactName" required
                                    style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 10px; font-size: 16px;">
                            </div>

                            <div class="form-group">
                                <label for="contactEmail">Your Email *</label>
                                <input type="email" id="contactEmail" name="contactEmail" required
                                    style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 10px; font-size: 16px;">
                            </div>

                            <div class="form-group">
                                <label for="contactPhone">Phone Number *</label>
                                <input type="tel" id="contactPhone" name="contactPhone" pattern="[0-9]{10}" required
                                    placeholder="10-digit mobile number"
                                    style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 10px; font-size: 16px;">
                            </div>

                            <div class="form-group">
                                <label for="contactSubject">Subject *</label>
                                <select id="contactSubject" name="contactSubject" required
                                    style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 10px; font-size: 16px;">
                                    <option value="">Select Subject</option>
                                    <option value="order">Order Inquiry</option>
                                    <option value="product">Product Information</option>
                                    <option value="return">Return & Refund</option>
                                    <option value="complaint">Complaint</option>
                                    <option value="suggestion">Suggestion</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="contactMessage">Message *</label>
                                <textarea id="contactMessage" name="contactMessage" rows="5" required
                                    placeholder="Tell us how we can help you..."
                                    style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 10px; font-size: 16px; resize: vertical;"></textarea>
                            </div>

                            <button type="submit" class="order-btn"
                                style="background: linear-gradient(135deg, #ff6b6b 0%, #ff5252 100%);">
                                <i class="fas fa-paper-plane"></i> Send Message
                            </button>
                        </form>

                        <div class="success-message" id="contactSuccess" style="margin-top: 20px;">
                            <h2><i class="fas fa-check-circle"></i> Message Sent Successfully!</h2>
                            <p>Thank you for contacting us. We'll get back to you within 24 hours.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MULTI-STEP PRODUCT ORDER MODAL -->
    <div class="product-view" id="view">
        <span class="close" onclick="closeOrderModal()">
            <i class="fas fa-times"></i>
        </span>

        <!-- Step 1: Product Details -->
        <div id="step1" class="order-step">
            <div class="step-header">
                <h2 style="font-size: 28px; margin-bottom: 10px;">Product Details</h2>
                <div class="step-indicator">
                    <span class="active">1</span>
                    <span>2</span>
                    <span>3</span>
                    <span>4</span>
                </div>
            </div>

            <div class="product-display">
                <div class="product-image">
                    <img id="modalProductImage" src="" alt="Product">
                </div>
                <div class="product-info">
                    <h3 id="modalProductTitle"></h3>
                    <div class="price" id="modalProductPrice"></div>
                    <p id="modalProductDescription"></p>

                    <div class="sizes">
                        <h4>Select Size</h4>
                        <div class="size-buttons">
                            <button class="size-btn" onclick="selectSize(this, 'XS')">XS</button>
                            <button class="size-btn" onclick="selectSize(this, 'S')">S</button>
                            <button class="size-btn" onclick="selectSize(this, 'M')">M</button>
                            <button class="size-btn" onclick="selectSize(this, 'L')">L</button>
                            <button class="size-btn" onclick="selectSize(this, 'XL')">XL</button>
                            <button class="size-btn" onclick="selectSize(this, 'XXL')">XXL</button>
                        </div>
                    </div>

                    <div class="quantity">
                        <h4>Quantity</h4>
                        <select id="modalQuantity"
                            style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 8px;">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>

                    <button onclick="goToStep(2)" class="next-btn"
                        style="background: linear-gradient(135deg, #ff6b6b 0%, #ff5252 100%); color: white; border: none; padding: 15px 30px; border-radius: 25px; font-size: 16px; font-weight: bold; cursor: pointer; margin-top: 20px;">
                        Next <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Step 2: Address Form -->
        <div id="step2" class="order-step" style="display: none;">
            <div class="step-header">
                <h2 style="font-size: 28px; margin-bottom: 10px;">Shipping Address</h2>
                <div class="step-indicator">
                    <span class="completed">1</span>
                    <span class="active">2</span>
                    <span>3</span>
                    <span>4</span>
                </div>
            </div>

            <form id="addressForm">
                <div class="form-row">
                    <div class="form-group">
                        <label>First Name *</label>
                        <input type="text" id="firstName" required>
                    </div>
                    <div class="form-group">
                        <label>Last Name *</label>
                        <input type="text" id="lastName" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Email Address *</label>
                    <input type="email" id="email" required>
                </div>

                <div class="form-group">
                    <label>Phone Number *</label>
                    <input type="tel" id="phone" pattern="[0-9]{10}" required placeholder="10-digit mobile number">
                </div>

                <div class="form-group">
                    <label>Delivery Address *</label>
                    <textarea id="address" rows="3" required
                        placeholder="Enter your complete delivery address"></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>City *</label>
                        <input type="text" id="city" required>
                    </div>
                    <div class="form-group">
                        <label>Pincode *</label>
                        <input type="text" id="pincode" pattern="[0-9]{6}" required placeholder="6-digit pincode">
                    </div>
                </div>

                <div class="form-group">
                    <label>State *</label>
                    <select id="state" required>
                        <option value="">Select State</option>
                        <option value="Andhra Pradesh">Andhra Pradesh</option>
                        <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                        <option value="Assam">Assam</option>
                        <option value="Bihar">Bihar</option>
                        <option value="Chhattisgarh">Chhattisgarh</option>
                        <option value="Goa">Goa</option>
                        <option value="Gujarat">Gujarat</option>
                        <option value="Haryana">Haryana</option>
                        <option value="Himachal Pradesh">Himachal Pradesh</option>
                        <option value="Jharkhand">Jharkhand</option>
                        <option value="Karnataka">Karnataka</option>
                        <option value="Kerala">Kerala</option>
                        <option value="Madhya Pradesh">Madhya Pradesh</option>
                        <option value="Maharashtra">Maharashtra</option>
                        <option value="Manipur">Manipur</option>
                        <option value="Meghalaya">Meghalaya</option>
                        <option value="Mizoram">Mizoram</option>
                        <option value="Nagaland">Nagaland</option>
                        <option value="Odisha">Odisha</option>
                        <option value="Punjab">Punjab</option>
                        <option value="Rajasthan">Rajasthan</option>
                        <option value="Sikkim">Sikkim</option>
                        <option value="Tamil Nadu">Tamil Nadu</option>
                        <option value="Telangana">Telangana</option>
                        <option value="Tripura">Tripura</option>
                        <option value="Uttar Pradesh">Uttar Pradesh</option>
                        <option value="Uttarakhand">Uttarakhand</option>
                        <option value="West Bengal">West Bengal</option>
                    </select>
                </div>

                <div style="display: flex; gap: 15px;">
                    <button type="button" onclick="goToStep(1)" class="back-btn"
                        style="background: #6c757d; color: white; border: none; padding: 15px 30px; border-radius: 25px; font-size: 16px; font-weight: bold; cursor: pointer;">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>
                    <button type="button" onclick="goToStep(3)" class="next-btn"
                        style="background: linear-gradient(135deg, #ff6b6b 0%, #ff5252 100%); color: white; border: none; padding: 15px 30px; border-radius: 25px; font-size: 16px; font-weight: bold; cursor: pointer;">
                        Next <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Step 3: Payment -->
        <div id="step3" class="order-step" style="display: none;">
            <div class="step-header">
                <h2 style="font-size: 28px; margin-bottom: 10px;">Payment</h2>
                <div class="step-indicator">
                    <span class="completed">1</span>
                    <span class="completed">2</span>
                    <span class="active">3</span>
                    <span>4</span>
                </div>
            </div>

            <div class="payment-section">
                <div class="order-summary">
                    <h3>Order Summary</h3>
                    <div id="orderSummary"></div>
                    <div class="total-amount">
                        <strong>Total Amount:</strong> <span id="totalAmount"></span>
                    </div>
                </div>

                <div class="payment-methods">
                    <h3>Select Payment Method</h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <button onclick="selectPayment('upi')" class="payment-btn"
                            style="background: white; border: 2px solid #ddd; padding: 20px; border-radius: 10px; cursor: pointer;">
                            <i class="fas fa-qrcode" style="font-size: 30px; color: #ff6b6b;"></i>
                            <h4>UPI Payment</h4>
                            <p>Pay via GPay, PhonePe, Paytm</p>
                        </button>
                        <button onclick="selectPayment('cod')" class="payment-btn"
                            style="background: white; border: 2px solid #ddd; padding: 20px; border-radius: 10px; cursor: pointer;">
                            <i class="fas fa-money-bill-wave" style="font-size: 30px; color: #27ae60;"></i>
                            <h4>Cash on Delivery</h4>
                            <p>Pay when you receive</p>
                        </button>
                    </div>
                </div>

                <div style="display: flex; gap: 15px;">
                    <button onclick="goToStep(2)" class="back-btn"
                        style="background: #6c757d; color: white; border: none; padding: 15px 30px; border-radius: 25px; font-size: 16px; font-weight: bold; cursor: pointer;">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>
                    <button onclick="processPayment()" class="next-btn"
                        style="background: linear-gradient(135deg, #ff6b6b 0%, #ff5252 100%); color: white; border: none; padding: 15px 30px; border-radius: 25px; font-size: 16px; font-weight: bold; cursor: pointer;">
                        Place Order <i class="fas fa-check"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Step 4: Order Confirmation -->
        <div id="step4" class="order-step" style="display: none;">
            <div class="step-header">
                <h2 style="font-size: 28px; margin-bottom: 10px;">Order Confirmation</h2>
                <div class="step-indicator">
                    <span class="completed">1</span>
                    <span class="completed">2</span>
                    <span class="completed">3</span>
                    <span class="active">4</span>
                </div>
            </div>

            <div class="confirmation-section" style="text-align: center;">
                <div
                    style="background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%); color: white; padding: 40px; border-radius: 20px; margin-bottom: 30px;">
                    <i class="fas fa-check-circle" style="font-size: 60px; margin-bottom: 20px;"></i>
                    <h2>Order Placed Successfully!</h2>
                    <p>Thank you for your order. We'll contact you soon.</p>
                </div>

                <div class="order-details"
                    style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); margin-bottom: 30px;">
                    <h3>Order Details</h3>
                    <div id="finalOrderDetails"></div>
                </div>

                <div style="display: flex; gap: 15px; justify-content: center;">
                    <a href="#" id="whatsappShareBtn" class="whatsapp-btn"
                        style="background: #25D366; color: white; text-decoration: none; padding: 15px 30px; border-radius: 25px; display: inline-flex; align-items: center; gap: 10px;">
                        <i class="fab fa-whatsapp"></i> Share on WhatsApp
                    </a>
                    <button onclick="closeOrderModal()" class="next-btn"
                        style="background: linear-gradient(135deg, #ff6b6b 0%, #ff5252 100%); color: white; border: none; padding: 15px 30px; border-radius: 25px; font-size: 16px; font-weight: bold; cursor: pointer;">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let selectedProduct = null;
        let selectedSize = null;
        let selectedPaymentMethod = null;
        let currentStep = 1;
        let orderData = {};

        function showSection(id) {
            // Update nav items
            document.querySelectorAll('.nav-item').forEach(item => {
                item.classList.remove('active');
            });
            event.target.classList.add('active');

            // Update sections
            document.querySelectorAll('.section').forEach(section => {
                section.classList.remove('active');
            });
            document.getElementById(id).classList.add('active');
        }

        function openProduct(title, price, image, description = '') {
            selectedProduct = { title, price, image, description };
            orderData = {};
            currentStep = 1;

            // Reset modal
            document.getElementById('view').classList.add('active');

            // Show step 1
            showStep(1);

            // Fill product details
            document.getElementById('modalProductTitle').innerText = title;
            document.getElementById('modalProductPrice').innerText = price;
            document.getElementById('modalProductImage').src = image;
            document.getElementById('modalProductDescription').innerText = description || 'Premium quality product with excellent craftsmanship and modern design.';

            // Reset selections
            document.querySelectorAll('.size-btn').forEach(btn => btn.classList.remove('selected'));
            document.getElementById('modalQuantity').value = '1';
            selectedSize = null;
            selectedPaymentMethod = null;
        }

        function closeOrderModal() {
            document.getElementById('view').classList.remove('active');
            currentStep = 1;
            orderData = {};
        }

        function selectSize(element, size) {
            document.querySelectorAll('.size-btn').forEach(btn => btn.classList.remove('selected'));
            element.classList.add('selected');
            selectedSize = size;
        }

        function showStep(step) {
            // Hide all steps
            document.querySelectorAll('.order-step').forEach(s => s.style.display = 'none');

            // Show current step
            document.getElementById(`step${step}`).style.display = 'block';

            // Update step indicators
            document.querySelectorAll('.step-indicator span').forEach((indicator, index) => {
                indicator.classList.remove('active', 'completed');
                if (index < step - 1) {
                    indicator.classList.add('completed');
                } else if (index === step - 1) {
                    indicator.classList.add('active');
                }
            });
        }

        function changeMainImage(img) {
            document.getElementById('mainImage').src = img.src;
        }

        // Multi-step functions
        function goToStep(step) {
            if (step === 2) {
                // Validate step 1
                if (!selectedSize) {
                    alert('Please select a size before proceeding.');
                    return;
                }
                // Store step 1 data
                orderData.product = selectedProduct;
                orderData.size = selectedSize;
                orderData.quantity = document.getElementById('modalQuantity').value;
            } else if (step === 3) {
                // Validate step 2
                const addressForm = document.getElementById('addressForm');
                if (!addressForm.checkValidity()) {
                    addressForm.reportValidity();
                    return;
                }
                // Store step 2 data
                orderData.address = {
                    firstName: document.getElementById('firstName').value,
                    lastName: document.getElementById('lastName').value,
                    email: document.getElementById('email').value,
                    phone: document.getElementById('phone').value,
                    address: document.getElementById('address').value,
                    city: document.getElementById('city').value,
                    pincode: document.getElementById('pincode').value,
                    state: document.getElementById('state').value
                };
                // Update order summary
                updateOrderSummary();
            }
            
            currentStep = step;
            showStep(step);
        }

        function updateOrderSummary() {
            const summaryHTML = `
                <div style="margin-bottom: 15px;">
                    <strong>Product:</strong> ${orderData.product.title}<br>
                    <strong>Price:</strong> ${orderData.product.price}<br>
                    <strong>Size:</strong> ${orderData.size}<br>
                    <strong>Quantity:</strong> ${orderData.quantity}<br>
                    <strong>Subtotal:</strong> ${orderData.product.price}
                </div>
                <div style="margin-bottom: 15px;">
                    <strong>Shipping Address:</strong><br>
                    ${orderData.address.firstName} ${orderData.address.lastName}<br>
                    ${orderData.address.address}<br>
                    ${orderData.address.city}, ${orderData.address.state} - ${orderData.address.pincode}<br>
                    <strong>Phone:</strong> ${orderData.address.phone}
                </div>
            `;
            
            document.getElementById('orderSummary').innerHTML = summaryHTML;
            
            // Calculate total
            const price = parseInt(orderData.product.price.replace('₹', ''));
            const quantity = parseInt(orderData.quantity);
            const total = price * quantity;
            document.getElementById('totalAmount').innerText = `₹${total}`;
        }

        function selectPayment(method) {
            selectedPaymentMethod = method;
            document.querySelectorAll('.payment-btn').forEach(btn => btn.classList.remove('selected'));
            event.target.classList.add('selected');
        }

        function processPayment() {
            if (!selectedPaymentMethod) {
                alert('Please select a payment method.');
                return;
            }
            
            // Store payment method
            orderData.paymentMethod = selectedPaymentMethod;
            orderData.orderId = 'ORD' + Date.now();
            orderData.timestamp = new Date().toISOString();
            
            // Save order to localStorage
            let orders = JSON.parse(localStorage.getItem('orders') || '[]');
            orders.push(orderData);
            localStorage.setItem('orders', JSON.stringify(orders));
            
            // Show confirmation
            showOrderConfirmation();
        }

        function showOrderConfirmation() {
            goToStep(4);
            
            const detailsHTML = `
                <div style="text-align: left;">
                    <p><strong>Order ID:</strong> ${orderData.orderId}</p>
                    <p><strong>Product:</strong> ${orderData.product.title}</p>
                    <p><strong>Price:</strong> ${orderData.product.price}</p>
                    <p><strong>Size:</strong> ${orderData.size}</p>
                    <p><strong>Quantity:</strong> ${orderData.quantity}</p>
                    <p><strong>Total Amount:</strong> ${document.getElementById('totalAmount').innerText}</p>
                    <p><strong>Payment Method:</strong> ${orderData.paymentMethod === 'upi' ? 'UPI Payment' : 'Cash on Delivery'}</p>
                    <p><strong>Shipping Address:</strong></p>
                    <p>${orderData.address.firstName} ${orderData.address.lastName}</p>
                    <p>${orderData.address.address}</p>
                    <p>${orderData.address.city}, ${orderData.address.state} - ${orderData.address.pincode}</p>
                    <p><strong>Phone:</strong> ${orderData.address.phone}</p>
                </div>
            `;
            
            document.getElementById('finalOrderDetails').innerHTML = detailsHTML;
            
            // Setup WhatsApp sharing
            const whatsappMessage = `🛍️ *New Order - FV FABLY VALOR* 🛍️
                
📋 Order ID: ${orderData.orderId}
👤 Customer: ${orderData.address.firstName} ${orderData.address.lastName}
📧 Email: ${orderData.address.email}
📱 Phone: ${orderData.address.phone}
🏠 Address: ${orderData.address.address}, ${orderData.address.city}, ${orderData.address.state} - ${orderData.address.pincode}

📦 Product Details:
• Product: ${orderData.product.title}
• Size: ${orderData.size}
• Quantity: ${orderData.quantity}
• Price: ${orderData.product.price}

💰 Total Amount: ${document.getElementById('totalAmount').innerText}
💳 Payment: ${orderData.paymentMethod === 'upi' ? 'UPI Payment' : 'Cash on Delivery'}

📞 Contact: +91 8866204293
📧 Email: jesuslifemylife@gmail.com

Thank you for your order! 🎉`;

            const whatsappBtn = document.getElementById('whatsappShareBtn');
            whatsappBtn.href = `https://wa.me/918866204293?text=${encodeURIComponent(whatsappMessage)}`;
        }

        // Handle contact form submission
        document.getElementById('contactForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const contactData = {
                name: formData.get('contactName'),
                email: formData.get('contactEmail'),
                phone: formData.get('contactPhone'),
                subject: formData.get('contactSubject'),
                message: formData.get('contactMessage'),
                timestamp: new Date().toISOString()
            };

            // Store contact message
            let messages = JSON.parse(localStorage.getItem('contactMessages') || '[]');
            messages.push(contactData);
            localStorage.setItem('contactMessages', JSON.stringify(messages));

            // Show success message
            document.getElementById('contactSuccess').classList.add('active');

            // Reset form
            this.reset();

            // Hide success message after 5 seconds
            setTimeout(() => {
                document.getElementById('contactSuccess').classList.remove('active');
            }, 5000);
        });

        // Load and Display Products
        function loadProducts() {
            const products = JSON.parse(localStorage.getItem('customProducts') || '[]');

            // Display products on home page
            const homeProductsContainer = document.getElementById('homeProducts');
            if (homeProductsContainer) {
                homeProductsContainer.innerHTML = '';

                // Show first 3 products on home page
                const homeProducts = products.slice(0, 3);
                if (homeProducts.length > 0) {
                    homeProducts.forEach(product => {
                        const productCard = createProductCard(product);
                        homeProductsContainer.appendChild(productCard);
                    });
                    homeProductsContainer.style.display = 'grid';
                } else {
                    homeProductsContainer.style.display = 'none';
                }
            }

            // Display all products on product page
            const productGrid = document.getElementById('productGrid');
            const noProductsMessage = document.getElementById('noProductsMessage');

            if (productGrid && noProductsMessage) {
                productGrid.innerHTML = '';

                if (products.length > 0) {
                    products.forEach(product => {
                        const productCard = createProductCard(product);
                        productGrid.appendChild(productCard);
                    });
                    productGrid.style.display = 'grid';
                    noProductsMessage.style.display = 'none';
                } else {
                    productGrid.style.display = 'none';
                    noProductsMessage.style.display = 'block';
                }
            }
        }

        // Create Product Card Element
        function createProductCard(product) {
            const card = document.createElement('div');
            card.className = 'card';
            card.onclick = () => openProduct(product.title, product.price, product.image, product.description || '');

            card.innerHTML = `
                <img src="${product.image}" alt="${product.title}" onerror="this.src='https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=500&h=400&fit=crop'">
                <div class="card-content">
                    <div class="card-title">${product.title}</div>
                    <div class="card-price">${product.price}</div>
                </div>
            `;

            return card;
        }

        // Load products when page loads
        document.addEventListener('DOMContentLoaded', function () {
            loadProducts();
        });

        // Show Order Form Directly
        function showOrderForm() {
            selectedProduct = {
                title: 'Custom Order',
                price: '₹799',
                image: 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=500&h=400&fit=crop',
                description: 'Premium quality product with excellent craftsmanship and modern design.'
            };
            orderData = {};
            currentStep = 1;

            // Reset modal
            document.getElementById('view').classList.add('active');

            // Show step 1
            showStep(1);

            // Fill product details
            document.getElementById('modalProductTitle').innerText = selectedProduct.title;
            document.getElementById('modalProductPrice').innerText = selectedProduct.price;
            document.getElementById('modalProductImage').src = selectedProduct.image;
            document.getElementById('modalProductDescription').innerText = selectedProduct.description;

            // Reset selections
            document.querySelectorAll('.size-btn').forEach(btn => btn.classList.remove('selected'));
            document.getElementById('modalQuantity').value = '1';
            selectedSize = null;
            selectedPaymentMethod = null;
        }

        // Add Product Functions
        function showAddProduct() {
            document.getElementById('addProductModal').classList.add('active');
        }

        function closeAddProduct() {
            document.getElementById('addProductModal').classList.remove('active');
        }

        // Handle Add Product Form
        document.getElementById('addProductForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const productData = {
                title: formData.get('productTitle'),
                price: formData.get('productPrice'),
                description: formData.get('productDescription'),
                image: formData.get('productImage'),
                timestamp: new Date().toISOString()
            };

            // Store product
            let products = JSON.parse(localStorage.getItem('customProducts') || '[]');
            products.push(productData);
            localStorage.setItem('customProducts', JSON.stringify(products));

            // Show success message
            document.getElementById('addProductSuccess').classList.add('active');

            // Reset form
            this.reset();

            // Hide success message after 3 seconds
            setTimeout(() => {
                document.getElementById('addProductSuccess').classList.remove('active');
                closeAddProduct();
                // Reload products to display the newly added product
                loadProducts();
            }, 3000);
        });

        // Close modal when clicking outside
        document.getElementById('view').addEventListener('click', function (e) {
            if (e.target === this) {
                closeOrderModal();
            }
        });

        document.getElementById('addProductModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeAddProduct();
            }
        });
    </script>

    <!-- ADD PRODUCT MODAL -->
    <div class="product-view" id="addProductModal">
        <span class="close" onclick="closeAddProduct()">
            <i class="fas fa-times"></i>
        </span>

        <div style="padding: 20px;">
            <h2 style="font-size: 28px; margin-bottom: 20px; color: #333;">
                <i class="fas fa-plus-circle"></i> Add New Product
            </h2>

            <form id="addProductForm">
                <div class="form-group">
                    <label for="productTitle">Product Title *</label>
                    <input type="text" id="productTitle" name="productTitle" required
                        style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 10px; font-size: 16px;">
                </div>

                <div class="form-group">
                    <label for="productPrice">Product Price *</label>
                    <input type="text" id="productPrice" name="productPrice" placeholder="₹799" required
                        style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 10px; font-size: 16px;">
                </div>

                <div class="form-group">
                    <label for="productDescription">Product Description *</label>
                    <textarea id="productDescription" name="productDescription" rows="3" required
                        placeholder="Enter product description..."
                        style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 10px; font-size: 16px; resize: vertical;"></textarea>
                </div>

                <div class="form-group">
                    <label for="productImage">Product Image *</label>
                    <input type="url" id="productImage" name="productImage" placeholder="Enter image URL" required
                        style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 10px; font-size: 16px;">
                </div>

                <button type="submit" class="order-btn"
                    style="background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);">
                    <i class="fas fa-plus-circle"></i> Add Product
                </button>
            </form>

            <div class="success-message" id="addProductSuccess">
                <h2><i class="fas fa-check-circle"></i> Product Added Successfully!</h2>
                <p>Your product has been added to the collection.</p>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer
        style="background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%); color: white; padding: 40px 0 20px; margin-top: 40px;">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
            <div
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px; margin-bottom: 30px;">
                <!-- Company Info -->
                <div>
                    <h3
                        style="font-size: 24px; margin-bottom: 15px; background: linear-gradient(45deg, #ff6b6b, #feca57); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                        FV FABLY VALOR</h3>
                    <p style="color: #ccc; line-height: 1.6; margin-bottom: 20px;">Your trusted fashion partner since
                        2020. Premium quality ethnic wear and contemporary fashion at unbeatable prices.</p>
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <i class="fas fa-phone" style="color: #ff6b6b;"></i>
                        <span>+91 88662 04293</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 15px; margin-top: 10px;">
                        <i class="fas fa-envelope" style="color: #ff6b6b;"></i>
                        <span>jesuslifemylife@gmail.com</span>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 style="font-size: 18px; margin-bottom: 15px; color: #ff6b6b;">Quick Links</h4>
                    <ul style="list-style: none; padding: 0;">
                        <li style="margin-bottom: 10px;"><a href="#" onclick="showSection('home')"
                                style="color: #ccc; text-decoration: none; transition: color 0.3s ease;">Home</a></li>
                        <li style="margin-bottom: 10px;"><a href="#" onclick="showSection('about')"
                                style="color: #ccc; text-decoration: none; transition: color 0.3s ease;">About Us</a>
                        </li>
                        <li style="margin-bottom: 10px;"><a href="#" onclick="showSection('product')"
                                style="color: #ccc; text-decoration: none; transition: color 0.3s ease;">Products</a>
                        </li>
                        <li style="margin-bottom: 10px;"><a href="#" onclick="showSection('contact')"
                                style="color: #ccc; text-decoration: none; transition: color 0.3s ease;">Contact</a>
                        </li>
                    </ul>
                </div>

                <!-- Services -->
                <div>
                    <h4 style="font-size: 18px; margin-bottom: 15px; color: #ff6b6b;">Services</h4>
                    <ul style="list-style: none; padding: 0;">
                        <li style="margin-bottom: 10px;"><span style="color: #ccc;">Fast Delivery</span></li>
                        <li style="margin-bottom: 10px;"><span style="color: #ccc;">Secure Payment</span></li>
                        <li style="margin-bottom: 10px;"><span style="color: #ccc;">Easy Returns</span></li>
                        <li style="margin-bottom: 10px;"><span style="color: #ccc;">24/7 Support</span></li>
                    </ul>
                </div>

                <!-- Social Media -->
                <div>
                    <h4 style="font-size: 18px; margin-bottom: 15px; color: #ff6b6b;">Follow Us</h4>
                    <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                        <a href="https://www.facebook.com" target="_blank"
                            style="width: 45px; height: 45px; background: #1877f2; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: transform 0.3s ease, box-shadow 0.3s ease;"
                            onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 5px 15px rgba(24, 119, 242, 0.4)';"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                            <i class="fab fa-facebook-f" style="font-size: 20px;"></i>
                        </a>

                        <a href="https://wa.me/918866204293" target="_blank"
                            style="width: 45px; height: 45px; background: #25D366; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: transform 0.3s ease, box-shadow 0.3s ease;"
                            onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 5px 15px rgba(37, 211, 102, 0.4)';"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                            <i class="fab fa-whatsapp" style="font-size: 20px;"></i>
                        </a>

                        <a href="https://www.flipkart.com" target="_blank"
                            style="width: 45px; height: 45px; background: #0078ff; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: transform 0.3s ease, box-shadow 0.3s ease;"
                            onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 5px 15px rgba(0, 120, 255, 0.4)';"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                            <i class="fab fa-flipkart" style="font-size: 20px;"></i>
                        </a>

                        <a href="https://www.instagram.com/fvfablyvalor?utm_source=qr&igsh=bmViczQ3MmQyZDZx"
                            target="_blank"
                            style="width: 45px; height: 45px; background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: transform 0.3s ease, box-shadow 0.3s ease;"
                            onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 5px 15px rgba(225, 48, 108, 0.4)';"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                            <i class="fab fa-instagram" style="font-size: 20px;"></i>
                        </a>

                        <a href="https://whatsapp.com/channel/0029VbCPc05FSAt5e2HBIT0u" target="_blank"
                            style="width: 45px; height: 45px; background: #128C7E; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: transform 0.3s ease, box-shadow 0.3s ease;"
                            onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 5px 15px rgba(18, 140, 126, 0.4)';"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';"
                            title="WhatsApp Channel">
                            <i class="fas fa-broadcast-tower" style="font-size: 20px;"></i>
                        </a>
                    </div>

                    <div style="margin-top: 20px;">
                        <p style="color: #ccc; font-size: 14px; margin-bottom: 10px;">Download Our App</p>
                        <div style="display: flex; gap: 10px;">
                            <a href="#"
                                style="background: #000; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none; font-size: 12px; display: flex; align-items: center; gap: 5px;">
                                <i class="fab fa-apple"></i> App Store
                            </a>
                            <a href="#"
                                style="background: #000; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none; font-size: 12px; display: flex; align-items: center; gap: 5px;">
                                <i class="fab fa-google-play"></i> Play Store
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Copyright -->
            <div style="border-top: 1px solid #444; padding-top: 20px; text-align: center;">
                <p style="color: #999; margin: 0;">&copy; 2024 FV FABLY VALOR. All rights reserved. | Designed with <i
                        class="fas fa-heart" style="color: #ff6b6b;"></i> for fashion lovers</p>
            </div>
        </div>
    </footer>
</body>

</html>

