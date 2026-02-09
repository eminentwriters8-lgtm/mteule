<?php 
// Start session at the VERY beginning
session_start();

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

require_once 'config.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - MTEULE Ventures</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        
        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(124, 58, 237, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(245, 158, 11, 0.1) 0%, transparent 50%);
            z-index: 0;
        }
        
        .login-box {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 400px;
            padding: 40px;
            position: relative;
            z-index: 1;
        }
        
        .login-logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-logo .logo {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 24px;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }
        
        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #7c3aed, #f59e0b);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 1.25rem;
            color: white;
        }
        
        .login-title {
            text-align: center;
            margin-bottom: 30px;
            color: white;
            font-size: 1.75rem;
            font-weight: 600;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #cbd5e1;
        }
        
        .form-group input {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s;
            background: rgba(255, 255, 255, 0.05);
            color: white;
        }
        
        .form-group input:focus {
            border-color: #7c3aed;
            outline: none;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.2);
        }
        
        .form-group input::placeholder {
            color: #94a3b8;
        }
        
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #7c3aed, #5b21b6);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(124, 58, 237, 0.4);
        }
        
        .alert {
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: none;
            animation: slideDown 0.3s ease;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        
        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            color: #4ade80;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }
        
        .back-home {
            text-align: center;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .back-home a {
            color: #cbd5e1;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: color 0.3s;
        }
        
        .back-home a:hover {
            color: white;
        }
        
        .forgot-password {
            text-align: center;
            margin-top: 15px;
        }
        
        .forgot-password a {
            color: #94a3b8;
            text-decoration: none;
            font-size: 14px;
        }
        
        .forgot-password a:hover {
            color: #cbd5e1;
        }
        
        /* Show any PHP error messages */
        <?php if (isset($_SESSION['login_error'])): ?>
        #errorMessage {
            display: block;
            background: rgba(239, 68, 68, 0.1);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        <?php 
            unset($_SESSION['login_error']);
        endif; ?>
        
        <?php if (isset($_SESSION['logout_message'])): ?>
        #successMessage {
            display: block;
            background: rgba(34, 197, 94, 0.1);
            color: #4ade80;
            border: 1px solid rgba(34, 197, 94, 0.3);
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        <?php 
            unset($_SESSION['logout_message']);
        endif; ?>
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="login-logo">
                <a href="index.html" class="logo">
                    <div class="logo-icon">⚡</div>
                    <span>MTEULE <span style="color: #f59e0b;">Ventures</span></span>
                </a>
            </div>
            
            <h2 class="login-title">Admin Login</h2>
            
            <!-- Success message (for logout, password reset, etc) -->
            <div id="successMessage" class="alert alert-success" style="display: none;"></div>
            
            <!-- Error message container -->
            <div id="errorMessage" class="alert alert-error" style="display: none;"></div>
            
            <!-- Login form -->
            <form id="loginForm" method="POST" action="auth.php">
                <div class="form-group">
                    <label for="username"><i class="fas fa-user"></i> Username</label>
                    <input type="text" id="username" name="username" required 
                           placeholder="Enter your username"
                           value="<?php echo isset($_SESSION['login_username']) ? htmlspecialchars($_SESSION['login_username']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Password</label>
                    <input type="password" id="password" name="password" required 
                           placeholder="Enter your password">
                </div>
                
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Login to Dashboard
                </button>
            </form>
            
            <div class="forgot-password">
                <a href="#" onclick="alert('Contact system administrator to reset your password.')">
                    <i class="fas fa-question-circle"></i> Forgot Password?
                </a>
            </div>
            
            <div class="back-home">
                <a href="index.html">
                    <i class="fas fa-arrow-left"></i> Back to Homepage
                </a>
            </div>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const loginForm = document.getElementById('loginForm');
        const errorDiv = document.getElementById('errorMessage');
        const successDiv = document.getElementById('successMessage');
        
        // Check if there are any URL parameters with messages
        const urlParams = new URLSearchParams(window.location.search);
        const message = urlParams.get('message');
        const error = urlParams.get('error');
        
        if (message) {
            successDiv.textContent = decodeURIComponent(message);
            successDiv.style.display = 'block';
        }
        
        if (error) {
            errorDiv.textContent = decodeURIComponent(error);
            errorDiv.style.display = 'block';
        }
        
        // Auto-hide messages after 5 seconds
        setTimeout(() => {
            if (errorDiv.style.display === 'block') {
                errorDiv.style.display = 'none';
            }
            if (successDiv.style.display === 'block') {
                successDiv.style.display = 'none';
            }
        }, 5000);
        
        // Form submission handler
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const username = formData.get('username');
            const password = formData.get('password');
            
            // Clear previous messages
            errorDiv.style.display = 'none';
            successDiv.style.display = 'none';
            
            // Client-side validation
            if (!username || !password) {
                errorDiv.textContent = 'Please fill in all fields';
                errorDiv.style.display = 'block';
                return;
            }
            
            // Show loading state
            const submitBtn = loginForm.querySelector('.btn-login');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging in...';
            submitBtn.disabled = true;
            
            // Submit via AJAX
            fetch('auth.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Show success message briefly before redirecting
                    successDiv.textContent = data.message || 'Login successful! Redirecting...';
                    successDiv.style.display = 'block';
                    
                    // Store login data in localStorage for dashboard use
                    if (data.user) {
                        localStorage.setItem('userData', JSON.stringify(data.user));
                    }
                    
                    // Redirect after 1 second
                    setTimeout(() => {
                        window.location.href = data.redirect || 'dashboard.php';
                    }, 1000);
                } else {
                    errorDiv.textContent = data.message || 'Login failed. Please try again.';
                    errorDiv.style.display = 'block';
                    
                    // Shake animation for error
                    loginBox.classList.add('shake');
                    setTimeout(() => loginBox.classList.remove('shake'), 500);
                    
                    // Reset button
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Login error:', error);
                errorDiv.textContent = 'Network error. Please check your connection and try again.';
                errorDiv.style.display = 'block';
                
                // Reset button
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
        
        // Add shake animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
                20%, 40%, 60%, 80% { transform: translateX(5px); }
            }
            .shake {
                animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
            }
        `;
        document.head.appendChild(style);
        
        // Auto-focus username field
        document.getElementById('username').focus();
        
        // Enter key to submit form
        document.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && (e.target.id === 'username' || e.target.id === 'password')) {
                loginForm.requestSubmit();
            }
        });
    });
    </script>
</body>
</html>
<?php 
// Clear any session data used for form repopulation
unset($_SESSION['login_username']);
?>