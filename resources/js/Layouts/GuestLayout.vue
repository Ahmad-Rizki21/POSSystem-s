<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="login-container">
        <!-- Background dengan gradient tema toko -->
        <div class="animated-background">
            <div class="gradient-orb orb-1"></div>
            <div class="gradient-orb orb-2"></div>
            <div class="gradient-orb orb-3"></div>
        </div>

        <!-- Grid pattern overlay -->
        <div class="grid-pattern"></div>

        <!-- Floating icons tema POS -->
        <div class="floating-icons">
            <div class="float-icon icon-1">💳</div>
            <div class="float-icon icon-2">🛒</div>
            <div class="float-icon icon-3">📦</div>
            <div class="float-icon icon-4">💰</div>
            <div class="float-icon icon-5">🏪</div>
            <div class="float-icon icon-6">📊</div>
        </div>

        <div class="content-wrapper">
            <!-- Left side - Form -->
            <div class="form-section">
                <div class="form-card">
                    <slot />
                </div>
            </div>

            <!-- Right side - 3D POS Illustration -->
            <div class="illustration-section">
                <div class="illustration-wrapper">
                    <!-- 3D Card Effect -->
                    <div class="card-3d">
                        <div class="card-3d-inner">
                            <!-- Ilustrasi 3D POS System -->
                            <div class="illustration-content">
                                
                                <!-- Counter/Meja Kasir -->
                                <div class="counter-desk">
                                    <div class="desk-top"></div>
                                    <div class="desk-front"></div>
                                    <div class="desk-shadow"></div>
                                </div>

                                <!-- Mesin Kasir/POS Terminal -->
                                <div class="pos-terminal">
                                    <div class="terminal-screen">
                                        <div class="screen-header">
                                            <div class="screen-dot"></div>
                                            <div class="screen-dot"></div>
                                            <div class="screen-dot"></div>
                                        </div>
                                        <div class="screen-content">
                                            <div class="receipt-line"></div>
                                            <div class="receipt-line"></div>
                                            <div class="receipt-line short"></div>
                                            <div class="receipt-total">
                                                <span class="currency">Rp</span>
                                                <span class="amount">450.000</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="terminal-keyboard">
                                        <div class="key"></div>
                                        <div class="key"></div>
                                        <div class="key"></div>
                                        <div class="key"></div>
                                        <div class="key"></div>
                                        <div class="key"></div>
                                    </div>
                                    <div class="terminal-base"></div>
                                </div>

                                <!-- Barcode Scanner -->
                                <div class="barcode-scanner">
                                    <div class="scanner-body"></div>
                                    <div class="scanner-glass"></div>
                                    <div class="scanner-light"></div>
                                </div>

                                <!-- Card Reader -->
                                <div class="card-reader">
                                    <div class="reader-body"></div>
                                    <div class="reader-slot"></div>
                                    <div class="reader-light active"></div>
                                </div>

                                <!-- Kasir Character -->
                                <div class="cashier-character">
                                    <div class="cashier-head">
                                        <div class="cashier-hair"></div>
                                        <div class="cashier-face"></div>
                                        <div class="cashier-smile"></div>
                                    </div>
                                    <div class="cashier-body">
                                        <div class="uniform-badge">POS</div>
                                    </div>
                                    <div class="cashier-arm-left"></div>
                                    <div class="cashier-arm-right">
                                        <div class="hand-card">💳</div>
                                    </div>
                                </div>

                                <!-- Produk di Counter -->
                                <div class="products-display">
                                    <div class="product product-1">
                                        <div class="product-box"></div>
                                        <div class="product-label"></div>
                                    </div>
                                    <div class="product product-2">
                                        <div class="product-box"></div>
                                        <div class="product-label"></div>
                                    </div>
                                </div>

                                <!-- Shopping Bag -->
                                <div class="shopping-bag">
                                    <div class="bag-body"></div>
                                    <div class="bag-handle"></div>
                                    <div class="bag-logo">🛍️</div>
                                </div>

                                <!-- Receipt Printer -->
                                <div class="receipt-printer">
                                    <div class="printer-body"></div>
                                    <div class="printer-paper">
                                        <div class="paper-line"></div>
                                        <div class="paper-line"></div>
                                        <div class="paper-line"></div>
                                    </div>
                                </div>

                                <!-- Floating Elements POS -->
                                <div class="floating-element pos-element-1">
                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="2" y="5" width="20" height="14" rx="2" stroke="currentColor" stroke-width="2"/>
                                        <path d="M2 10H22" stroke="currentColor" stroke-width="2"/>
                                        <rect x="6" y="14" width="4" height="2" rx="1" fill="currentColor"/>
                                    </svg>
                                </div>
                                
                                <div class="floating-element pos-element-2">
                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3 3H5L5.4 5M7 13H17L21 5H5.4M7 13L5.4 5M7 13L4.707 15.293C4.077 15.923 4.523 17 5.414 17H17M17 17C15.895 17 15 17.895 15 19C15 20.105 15.895 21 17 21C18.105 21 19 20.105 19 19C19 17.895 18.105 17 17 17ZM9 19C9 20.105 8.105 21 7 21C5.895 21 5 20.105 5 19C5 17.895 5.895 17 7 17C8.105 17 9 17.895 9 19Z" stroke="currentColor" stroke-width="2"/>
                                    </svg>
                                </div>

                                <div class="floating-element pos-element-3">
                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M20 7H4C2.89543 7 2 7.89543 2 9V19C2 20.1046 2.89543 21 4 21H20C21.1046 21 22 20.1046 22 19V9C22 7.89543 21.1046 7 20 7Z" stroke="currentColor" stroke-width="2"/>
                                        <path d="M16 7V5C16 3.89543 15.1046 3 14 3H10C8.89543 3 8 3.89543 8 5V7" stroke="currentColor" stroke-width="2"/>
                                        <circle cx="12" cy="14" r="2" fill="currentColor"/>
                                    </svg>
                                </div>

                                <div class="floating-element pos-element-4">
                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="3" y="6" width="18" height="13" rx="2" stroke="currentColor" stroke-width="2"/>
                                        <path d="M3 10H21" stroke="currentColor" stroke-width="2"/>
                                        <path d="M7 15H7.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M11 15H13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Text Content -->
                    <div class="welcome-text">
                        <h2>POS System Login</h2>
                        <p>Access your point of sale dashboard and manage your store</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Container utama */
.login-container {
    min-height: 100vh;
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Animated Background */
.animated-background {
    position: absolute;
    width: 100%;
    height: 100%;
    overflow: hidden;
}

.gradient-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    opacity: 0.5;
    animation: float 20s infinite ease-in-out;
}

.orb-1 {
    width: 500px;
    height: 500px;
    background: linear-gradient(45deg, #667eea, #764ba2);
    top: -10%;
    left: -10%;
    animation-delay: 0s;
}

.orb-2 {
    width: 400px;
    height: 400px;
    background: linear-gradient(45deg, #f093fb, #f5576c);
    bottom: -10%;
    right: -10%;
    animation-delay: 7s;
}

.orb-3 {
    width: 300px;
    height: 300px;
    background: linear-gradient(45deg, #4facfe, #00f2fe);
    top: 50%;
    left: 50%;
    animation-delay: 14s;
}

@keyframes float {
    0%, 100% {
        transform: translate(0, 0) scale(1);
    }
    33% {
        transform: translate(30px, -50px) scale(1.1);
    }
    66% {
        transform: translate(-20px, 20px) scale(0.9);
    }
}

/* Grid Pattern */
.grid-pattern {
    position: absolute;
    width: 100%;
    height: 100%;
    background-image: 
        linear-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
    background-size: 50px 50px;
    animation: gridMove 20s linear infinite;
}

@keyframes gridMove {
    0% {
        transform: translate(0, 0);
    }
    100% {
        transform: translate(50px, 50px);
    }
}

/* Floating Icons */
.floating-icons {
    position: absolute;
    width: 100%;
    height: 100%;
    pointer-events: none;
}

.float-icon {
    position: absolute;
    font-size: 2.5rem;
    opacity: 0.15;
    animation: floatIcon 15s infinite ease-in-out;
}

.icon-1 {
    top: 10%;
    left: 5%;
    animation-delay: 0s;
}

.icon-2 {
    top: 20%;
    right: 10%;
    animation-delay: 2s;
}

.icon-3 {
    bottom: 30%;
    left: 8%;
    animation-delay: 4s;
}

.icon-4 {
    bottom: 15%;
    right: 15%;
    animation-delay: 6s;
}

.icon-5 {
    top: 50%;
    left: 3%;
    animation-delay: 8s;
}

.icon-6 {
    top: 60%;
    right: 5%;
    animation-delay: 10s;
}

@keyframes floatIcon {
    0%, 100% {
        transform: translateY(0px) rotate(0deg);
    }
    50% {
        transform: translateY(-30px) rotate(10deg);
    }
}

/* Content Wrapper */
.content-wrapper {
    position: relative;
    z-index: 1;
    display: grid;
    grid-template-columns: 1fr 1fr;
    min-height: 100vh;
    padding: 2rem;
    gap: 4rem;
    align-items: center;
    max-width: 1400px;
    margin: 0 auto;
}

/* Form Section */
.form-section {
    display: flex;
    justify-content: center;
    align-items: center;
}

.form-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    padding: 3rem;
    width: 100%;
    max-width: 480px;
    box-shadow: 
        0 20px 60px rgba(0, 0, 0, 0.3),
        0 0 0 1px rgba(255, 255, 255, 0.1) inset;
    transform-style: preserve-3d;
    transition: transform 0.3s ease;
}

.form-card:hover {
    transform: translateY(-5px);
}

/* Illustration Section */
.illustration-section {
    display: flex;
    justify-content: center;
    align-items: center;
    perspective: 1500px;
}

.illustration-wrapper {
    width: 100%;
    max-width: 650px;
}

/* 3D Card */
.card-3d {
    position: relative;
    transform-style: preserve-3d;
    transition: transform 0.6s ease;
}

.card-3d:hover {
    transform: rotateY(5deg) rotateX(5deg);
}

.card-3d-inner {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border-radius: 30px;
    padding: 3rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 
        0 30px 80px rgba(0, 0, 0, 0.3),
        0 0 0 1px rgba(255, 255, 255, 0.1) inset;
}

/* Illustration Content */
.illustration-content {
    position: relative;
    height: 450px;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Counter Desk */
.counter-desk {
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1;
}

.desk-top {
    width: 400px;
    height: 20px;
    background: linear-gradient(to bottom, #8b7355, #6b5644);
    border-radius: 10px 10px 0 0;
    box-shadow: 0 -5px 20px rgba(0, 0, 0, 0.2);
}

.desk-front {
    width: 400px;
    height: 80px;
    background: linear-gradient(to bottom, #6b5644, #5a4634);
    border-radius: 0 0 5px 5px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

.desk-shadow {
    position: absolute;
    bottom: -15px;
    left: 50%;
    transform: translateX(-50%);
    width: 420px;
    height: 20px;
    background: radial-gradient(ellipse, rgba(0, 0, 0, 0.4), transparent);
    filter: blur(15px);
}

/* POS Terminal */
.pos-terminal {
    position: absolute;
    left: 50%;
    bottom: 100px;
    transform: translateX(-50%);
    z-index: 3;
    animation: terminalFloat 4s ease-in-out infinite;
}

@keyframes terminalFloat {
    0%, 100% {
        transform: translateX(-50%) translateY(0px);
    }
    50% {
        transform: translateX(-50%) translateY(-10px);
    }
}

.terminal-screen {
    width: 200px;
    height: 280px;
    background: linear-gradient(135deg, #2d3748 0%, #1a202c 100%);
    border-radius: 15px;
    border: 6px solid #4a5568;
    box-shadow: 
        0 20px 40px rgba(0, 0, 0, 0.4),
        inset 0 0 20px rgba(255, 255, 255, 0.05);
    overflow: hidden;
}

.screen-header {
    display: flex;
    gap: 5px;
    padding: 10px;
    background: rgba(0, 0, 0, 0.3);
}

.screen-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
}

.screen-content {
    padding: 20px 15px;
}

.receipt-line {
    height: 6px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 3px;
    margin-bottom: 8px;
    animation: pulse 2s ease-in-out infinite;
}

.receipt-line.short {
    width: 60%;
}

@keyframes pulse {
    0%, 100% {
        opacity: 0.2;
    }
    50% {
        opacity: 0.4;
    }
}

.receipt-total {
    margin-top: 20px;
    padding: 15px;
    background: rgba(102, 126, 234, 0.3);
    border-radius: 8px;
    text-align: center;
    font-family: 'Courier New', monospace;
}

.currency {
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.9rem;
}

.amount {
    color: #fff;
    font-size: 1.3rem;
    font-weight: bold;
    margin-left: 5px;
}

.terminal-keyboard {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 8px;
    padding: 15px;
    background: #2d3748;
    border-radius: 0 0 10px 10px;
}

.key {
    width: 100%;
    height: 30px;
    background: linear-gradient(to bottom, #4a5568, #2d3748);
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
}

.terminal-base {
    width: 220px;
    height: 15px;
    background: linear-gradient(to bottom, #4a5568, #2d3748);
    border-radius: 0 0 10px 10px;
    margin-top: -5px;
    margin-left: -10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
}

/* Barcode Scanner */
.barcode-scanner {
    position: absolute;
    left: 20%;
    bottom: 120px;
    z-index: 2;
    animation: scannerPulse 3s ease-in-out infinite;
}

@keyframes scannerPulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

.scanner-body {
    width: 80px;
    height: 50px;
    background: linear-gradient(135deg, #e53e3e, #c53030);
    border-radius: 10px;
    box-shadow: 0 10px 20px rgba(229, 62, 62, 0.4);
}

.scanner-glass {
    position: absolute;
    top: 10px;
    left: 10px;
    width: 60px;
    height: 30px;
    background: rgba(0, 0, 0, 0.6);
    border-radius: 5px;
}

.scanner-light {
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, transparent, #e53e3e, transparent);
    animation: scanLight 2s ease-in-out infinite;
}

@keyframes scanLight {
    0%, 100% {
        opacity: 0.3;
    }
    50% {
        opacity: 1;
    }
}

/* Card Reader */
.card-reader {
    position: absolute;
    right: 25%;
    bottom: 120px;
    z-index: 2;
}

.reader-body {
    width: 70px;
    height: 90px;
    background: linear-gradient(135deg, #2d3748, #1a202c);
    border-radius: 8px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
}

.reader-slot {
    position: absolute;
    top: 20px;
    left: 10px;
    width: 50px;
    height: 8px;
    background: rgba(0, 0, 0, 0.6);
    border-radius: 2px;
}

.reader-light {
    position: absolute;
    top: 40px;
    left: 50%;
    transform: translateX(-50%);
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #48bb78;
    box-shadow: 0 0 10px #48bb78;
}

.reader-light.active {
    animation: blink 1.5s ease-in-out infinite;
}

@keyframes blink {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.3;
    }
}

/* Cashier Character */
.cashier-character {
    position: absolute;
    right: 10%;
    bottom: 100px;
    z-index: 4;
    animation: cashierFloat 3.5s ease-in-out infinite;
}

@keyframes cashierFloat {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-15px);
    }
}

.cashier-head {
    position: relative;
    width: 70px;
    height: 70px;
    margin: 0 auto;
}

.cashier-hair {
    position: absolute;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 65px;
    height: 40px;
    background: #2d3748;
    border-radius: 50% 50% 0 0;
}

.cashier-face {
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 60px;
    background: #ffd1a3;
    border-radius: 50%;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.cashier-smile {
    position: absolute;
    bottom: 15px;
    left: 50%;
    transform: translateX(-50%);
    width: 30px;
    height: 15px;
    border: 3px solid #2d3748;
    border-top: none;
    border-radius: 0 0 15px 15px;
}

.cashier-body {
    position: relative;
    width: 80px;
    height: 100px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 0 0 40px 40px;
    margin: 0 auto;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
}

.uniform-badge {
    position: absolute;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    padding: 5px 12px;
    background: white;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: bold;
    color: #667eea;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.cashier-arm-left,
.cashier-arm-right {
    position: absolute;
    width: 28px;
    height: 70px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    top: 80px;
}

.cashier-arm-left {
    left: -8px;
    transform: rotate(-25deg);
    animation: armMove 2.5s ease-in-out infinite;
}

.cashier-arm-right {
    right: -8px;
    transform: rotate(25deg);
}

@keyframes armMove {
    0%, 100% {
        transform: rotate(-25deg);
    }
    50% {
        transform: rotate(-35deg);
    }
}

.hand-card {
    position: absolute;
    bottom: -20px;
    right: -10px;
    font-size: 1.5rem;
    animation: cardWave 2s ease-in-out infinite;
}

@keyframes cardWave {
    0%, 100% {
        transform: rotate(0deg);
    }
    50% {
        transform: rotate(-10deg);
    }
}

/* Products Display */
.products-display {
    position: absolute;
    left: 10%;
    bottom: 100px;
    z-index: 2;
}

.product {
    margin-bottom: 10px;
    animation: productBounce 3s ease-in-out infinite;
}

.product-1 {
    animation-delay: 0s;
}

.product-2 {
    animation-delay: 1.5s;
}

@keyframes productBounce {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-8px);
    }
}

.product-box {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(240, 147, 251, 0.4);
}

.product-label {
    width: 40px;
    height: 5px;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 3px;
    margin: 5px auto 0;
}

/* Shopping Bag */
.shopping-bag {
    position: absolute;
    left: 15%;
    bottom: 100px;
    z-index: 2;
    animation: bagSwing 4s ease-in-out infinite;
}

@keyframes bagSwing {
    0%, 100% {
        transform: rotate(-5deg);
    }
    50% {
        transform: rotate(5deg);
    }
}

.bag-body {
    width: 60px;
    height: 70px;
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    border-radius: 0 0 10px 10px;
    box-shadow: 0 10px 20px rgba(79, 172, 254, 0.4);
}

.bag-handle {
    width: 40px;
    height: 20px;
    border: 4px solid #4facfe;
    border-bottom: none;
    border-radius: 20px 20px 0 0;
    margin: 0 auto -4px;
}

.bag-logo {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 1.5rem;
}

/* Receipt Printer */
.receipt-printer {
    position: absolute;
    right: 15%;
    bottom: 100px;
    z-index: 2;
}

.printer-body {
    width: 80px;
    height: 60px;
    background: linear-gradient(135deg, #718096, #4a5568);
    border-radius: 8px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
}

.printer-paper {
    width: 60px;
    height: 40px;
    background: white;
    margin: -20px auto 0;
    border-radius: 5px 5px 0 0;
    padding: 10px 8px;
    box-shadow: 0 -5px 10px rgba(0, 0, 0, 0.2);
    animation: printPaper 3s ease-in-out infinite;
}

@keyframes printPaper {
    0%, 100% {
        height: 40px;
    }
    50% {
        height: 60px;
    }
}

.paper-line {
    width: 100%;
    height: 3px;
    background: #e2e8f0;
    border-radius: 2px;
    margin-bottom: 5px;
}

/* Floating Elements POS */
.floating-element {
    position: absolute;
    width: 50px;
    height: 50px;
    color: rgba(255, 255, 255, 0.6);
    animation: floatElement 6s ease-in-out infinite;
}

.pos-element-1 {
    top: 5%;
    left: 5%;
    animation-delay: 0s;
}

.pos-element-2 {
    top: 15%;
    right: 5%;
    animation-delay: 1.5s;
}

.pos-element-3 {
    bottom: 35%;
    left: 2%;
    animation-delay: 3s;
}

.pos-element-4 {
    top: 40%;
    right: 8%;
    animation-delay: 4.5s;
}

@keyframes floatElement {
    0%, 100% {
        transform: translateY(0px) rotate(0deg);
    }
    50% {
        transform: translateY(-30px) rotate(180deg);
    }
}

/* Welcome Text */
.welcome-text {
    margin-top: 3rem;
    text-align: center;
    color: white;
}

.welcome-text h2 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    animation: fadeInUp 1s ease;
}

.welcome-text p {
    font-size: 1.1rem;
    opacity: 0.9;
    text-shadow: 0 1px 5px rgba(0, 0, 0, 0.2);
    animation: fadeInUp 1s ease 0.2s backwards;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* ========================================
   RESPONSIVE DESIGN - PERBAIKAN MOBILE
   ======================================== */

/* Tablet & Mobile - Sembunyikan Ilustrasi */
@media (max-width: 1024px) {
    .content-wrapper {
        grid-template-columns: 1fr;
        gap: 0;
        padding: 0;
        min-height: 100vh;
    }

    /* Sembunyikan ilustrasi di tablet dan mobile */
    .illustration-section {
        display: none;
    }

    .form-section {
        min-height: 100vh;
        padding: 2rem 1.5rem;
    }

    .form-card {
        max-width: 100%;
    }
}

/* Tablet Specific */
@media (max-width: 768px) {
    /* Optimasi untuk mobile */
    .login-container {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .content-wrapper {
        padding: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .form-section {
        width: 100%;
        padding: 1rem;
        min-height: auto;
    }

    .form-card {
        padding: 2rem 1.5rem;
        box-shadow: 
            0 10px 40px rgba(0, 0, 0, 0.2),
            0 0 0 1px rgba(255, 255, 255, 0.1) inset;
    }

    /* Kurangi opacity elemen dekoratif di mobile */
    .floating-icons {
        opacity: 0.3;
    }

    .grid-pattern {
        opacity: 0.3;
    }

    .float-icon {
        font-size: 1.5rem;
    }

    .floating-element {
        display: none;
    }
}

/* Mobile Phone Specific */
@media (max-width: 480px) {
    .login-container {
        padding: 1rem;
    }

    .content-wrapper {
        padding: 0.5rem;
    }

    .form-section {
        padding: 0.5rem;
    }

    .form-card {
        padding: 1.5rem 1.25rem;
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    }

    /* Sembunyikan semua elemen dekoratif untuk performa */
    .illustration-section,
    .floating-icons,
    .grid-pattern {
        display: none;
    }

    /* Optimalkan gradient background untuk performa */
    .animated-background {
        display: none;
    }

    .login-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
}

/* Landscape mode di mobile */
@media (max-width: 768px) and (orientation: landscape) {
    .form-section {
        min-height: auto;
        padding: 1rem;
    }

    .form-card {
        max-width: 500px;
        margin: 0 auto;
    }

    .login-container {
        align-items: flex-start;
        padding-top: 2rem;
    }
}

/* Smooth scrolling untuk mobile */
@media (max-width: 768px) {
    .login-container {
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
        scroll-behavior: smooth;
    }
}

/* Very Small Devices */
@media (max-width: 360px) {
    .form-card {
        padding: 1.25rem 1rem;
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .form-card {
        background: rgba(26, 32, 44, 0.95);
        color: white;
    }
}
</style>