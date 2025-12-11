<!DOCTYPE html>
<html>
<head>
    <title>Your GlamBook Verification Code</title>
    <style>
        :root {
            --primary-rose: #e8b4b8;
            --deep-rose: #d8a1a6;
            --soft-cream: #f9f5f0;
            --warm-gold: #d4af37;
            --charcoal: #2c2c2c;
            --crisp-white: #ffffff;
        }
        
        body { 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif; 
            background-color: var(--soft-cream); 
            margin: 0; 
            padding: 20px;
            color: var(--charcoal);
        }
        
        .container { 
            max-width: 500px; 
            margin: 0 auto; 
            background: var(--crisp-white); 
            border-radius: 24px; 
            box-shadow: 0 10px 30px rgba(232, 180, 184, 0.15); 
            padding: 40px;
            border: 1px solid rgba(232, 180, 184, 0.2);
            overflow: hidden;
        }
        
        .container::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-rose), var(--warm-gold), var(--primary-rose));
        }
        
        .header { 
            text-align: center; 
            margin-bottom: 30px;
            position: relative;
        }
        
        .brand {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            font-weight: 700;
            color: var(--charcoal);
            margin-bottom: 10px;
        }
        
        .brand span {
            color: var(--deep-rose);
        }
        
        .code { 
            font-size: 48px; 
            font-weight: bold; 
            text-align: center; 
            color: var(--warm-gold); 
            margin: 30px 0; 
            letter-spacing: 8px;
            padding: 20px;
            background: linear-gradient(135deg, rgba(232, 180, 184, 0.1) 0%, rgba(247, 235, 235, 0.1) 100%);
            border-radius: 12px;
            border: 2px dashed rgba(232, 180, 184, 0.3);
        }
        
        .message { 
            color: #666; 
            line-height: 1.6;
            font-size: 16px;
        }
        
        .message strong {
            color: var(--deep-rose);
        }
        
        .security-info {
            background: linear-gradient(135deg, rgba(232, 180, 184, 0.05) 0%, rgba(247, 235, 235, 0.05) 100%);
            border-radius: 12px;
            padding: 20px;
            margin: 25px 0;
            border-left: 4px solid var(--deep-rose);
        }
        
        .security-info ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        
        .security-info li {
            margin-bottom: 8px;
            color: #666;
        }
        
        .expiry-notice {
            color: #dc3545;
            font-weight: 600;
            margin: 15px 0;
            text-align: center;
            font-size: 14px;
        }
        
        .footer { 
            margin-top: 30px; 
            text-align: center; 
            color: #999; 
            font-size: 12px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        
        @media (max-width: 600px) {
            .container {
                padding: 25px;
                margin: 10px;
            }
            
            .code {
                font-size: 36px;
                letter-spacing: 6px;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="brand">
                <span style="color: var(--warm-gold);">✦</span> Glam<span>Book</span>
            </div>
            <h3 style="color: var(--deep-rose); margin: 5px 0 0 0; font-weight: 600;">Email Verification Code</h3>
        </div>
        
        <div class="message">
            <p>Hello <strong>{{ $name }}</strong>,</p>
            <p>Thank you for registering with GlamBook. Please use the verification code below to complete your account setup:</p>
        </div>
        
        <div class="code">{{ $code }}</div>
        
        <div class="expiry-notice">
            ⏰ This code will expire in 15 minutes
        </div>
        
        <div class="security-info">
            <p><strong>For your security:</strong></p>
            <ul>
                <li>Enter this 6-digit code on the verification page</li>
                <li>Do not share this code with anyone</li>
                <li>If you didn't request this code, please ignore this email</li>
                <li>For security reasons, this code can only be used once</li>
            </ul>
        </div>
        
        <div class="message">
            <p>Need help? <a href="mailto:support@glambook.com" style="color: var(--deep-rose); text-decoration: none;">Contact our support team</a></p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} GlamBook. All rights reserved.</p>
            <p>Professional Makeup Artist Booking System</p>
            <p style="font-size: 11px; color: #aaa; margin-top: 10px;">
                This email was sent as part of your GlamBook registration process.<br>
                If you received this email by mistake, please delete it.
            </p>
        </div>
    </div>
</body>
</html>