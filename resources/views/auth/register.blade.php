<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - RICONA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .register-container {
            max-width: 450px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #dc3545;
        }
    </style>
</head>
<body>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="register-container">
                <div class="text-center mb-4">
                    <h2 class="fw-bold text-danger fst-italic">RICONA</h2>
                    <h5 class="mt-3">Create a new account</h5>
                </div>
                
                <div id="messageAlert" class="alert d-none" role="alert"></div>

                <form id="registerForm" method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                   
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-dark" id="registerBtn">Register</button>
                    </div>

                    <div class="text-center">
                        <p>
                            <a href="{{ route('login') }}" class="text-secondary text-decoration-none">Already have an account? Log in</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('registerForm');
    const messageAlert = document.getElementById('messageAlert');
    const registerBtn = document.getElementById('registerBtn');

    if (registerForm) {
        registerForm.addEventListener('submit', async function(event) {
            event.preventDefault(); // Prevents the default form submission (page reload)

            // Show a loading state
            registerBtn.disabled = true;
            registerBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Registering...';

            const formData = new FormData(this);
            const csrfToken = formData.get('_token');

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json' // Tell the server we expect a JSON response
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok) {
                    // Handle successful registration
                    messageAlert.classList.remove('d-none', 'alert-danger');
                    messageAlert.classList.add('alert-success');
                    messageAlert.textContent = 'Registration successful! Redirecting...';
                    
                    // You can add a slight delay before redirecting
                    setTimeout(() => {
                        window.location.href = data.redirect || '{{ route('login') }}'; // Redirect to login page
                    }, 2000);
                    
                } else {
                    // Handle validation or other errors
                    messageAlert.classList.remove('d-none', 'alert-success');
                    messageAlert.classList.add('alert-danger');
                    let errorMessage = 'Registration failed. Please try again.';

                    if (data.errors) {
                        // Display specific validation errors
                        errorMessage = Object.values(data.errors).flat().join('\n');
                    } else if (data.message) {
                        errorMessage = data.message;
                    }
                    messageAlert.textContent = errorMessage;
                }
            } catch (error) {
                console.error('Error:', error);
                messageAlert.classList.remove('d-none', 'alert-success');
                messageAlert.classList.add('alert-danger');
                messageAlert.textContent = 'An error occurred. Please try again.';
            } finally {
                // Reset button state
                registerBtn.disabled = false;
                registerBtn.textContent = 'Register';
            }
        });
    }
});
</script>

</body>