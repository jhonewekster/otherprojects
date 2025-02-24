<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Texas Treasures - Contact-Us</title>
    <link rel="icon" type="image/svg+xml" href="./images/favicon.svg">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="style/contact.css">
</head>
<body class="bg-gray-50">
<?php include 'parts/navbar.php'; ?>




    <div class="contact-container">
        <div class="contact-header">
            <h1 class="contact-title">Contact Us</h1>
            <p class="contact-subtitle">We're here to help! Get in touch with our team for any questions, concerns, or feedback.</p>
        </div>

        <section id="contactInfo" class="contact-info-section">
            <div class="contact-methods">
                <div id="emailMethod" class="contact-method-item">
                    <div class="contact-method-icon">
                        <i data-lucide="mail" class="w-full h-full"></i>
                    </div>
                    <div class="contact-method-content">
                        <h3>Email Us</h3>
                        <p><a href="mailto:support@testbanky.plus">info@texastreasures.shop</a></p>
                    </div>
                </div>

                <div id="phoneMethod" class="contact-method-item">
                    <div class="contact-method-icon">
                        <i data-lucide="phone" class="w-full h-full"></i>
                    </div>
                    <div class="contact-method-content">
                        <h3>Call Us</h3>
                        <p><a href="tel:+19512397453">+1 (440) 964-9057</a></p>
                    </div>
                </div>

                <div id="addressMethod" class="contact-method-item">
                    <div class="contact-method-icon">
                        <i data-lucide="map-pin" class="w-full h-full"></i>
                    </div>
                    <div class="contact-method-content">
                        <h3>Visit Us</h3>
                        <p>6662, 13350 Dallas Pkwy #3610,<br>Dallas, TX 75240, United States</p>
                    </div>
                </div>

                <div id="chatMethod" class="contact-method-item">
                    <div class="contact-method-icon">
                        <i data-lucide="message-circle" class="w-full h-full"></i>
                    </div>
                    <div class="contact-method-content">
                        <h3>Live Chat</h3>
                        <p>Coming Soon<br>For real-time assistance</p>
                    </div>
                </div>
            </div>

            <div id="supportInfo" class="support-info" style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
                <h2 class="support-info-title">
                    <i data-lucide="info" class="w-5 h-5"></i>
                    Support Information
                </h2>
                <p style="margin-bottom: 16px; color: #4B5563;">For the fastest response, please include:</p>
                <ul class="support-info-list">
                    <li>Your order number (if applicable)</li>
                    <li>Detailed description of your inquiry</li>
                    <li>Any relevant screenshots or images</li>
                    <li>Best time to contact you</li>
                </ul>
            </div>
        </section>

        <section id="contactForm" class="message-form">
            <h2 class="form-title">Send Us a Message</h2>
            <form id="messageForm" action="/send-message" method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="name" class="form-label">Name *</label>
                        <input type="text" id="name" name="name" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" id="email" name="email" class="form-input" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="subject" class="form-label">Subject</label>
                    <input type="text" id="subject" name="subject" class="form-input">
                </div>

                <div class="form-group">
                    <label for="message" class="form-label">Message *</label>
                    <textarea id="message" name="message" class="form-textarea" required></textarea>
                </div>

                <button type="submit" class="submit-button">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    Send Message
                </button>
            </form>
        </section>
    </div>











    <?php require_once 'parts/Newsletter.php'; ?>
    <?php require_once 'parts/footer.php'; ?>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>