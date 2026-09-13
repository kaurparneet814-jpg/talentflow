<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - TalentFlow</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">

        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">

                    <h3 class="text-center mb-4">TalentFlow Login</h3>

                    <div
                        id="errorMessage"
                        class="alert alert-danger d-none">
                    </div>

                    <form id="loginForm">

                        <div class="mb-3">
                            <label class="form-label">Email</label>

                            <input
                                type="email"
                                id="email"
                                class="form-control"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>

                            <input
                                type="password"
                                id="password"
                                class="form-control"
                                required
                            >
                        </div>

                        <button
                            type="submit"
                            class="btn btn-dark w-100">
                            Login
                        </button>

                    </form>

                </div>
            </div>
        </div>

    </div>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', async function (event) {
    event.preventDefault();

    const errorBox = document.getElementById('errorMessage');

    errorBox.classList.add('d-none');

    const response = await fetch('/api/login', {
        method: 'POST',

        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },

        body: JSON.stringify({
            email: document.getElementById('email').value,
            password: document.getElementById('password').value
        })
    });

    const data = await response.json();

    if (!response.ok) {
        errorBox.textContent = data.message ?? 'Login failed.';
        errorBox.classList.remove('d-none');
        return;
    }

    // Store Sanctum token for protected API requests.
    localStorage.setItem('auth_token', data.token);

    // Redirect authenticated user to dashboard.
    window.location.href = '/dashboard';
});
</script>

</body>
</html>