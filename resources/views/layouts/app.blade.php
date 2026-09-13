<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'TalentFlow')</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark px-4">
        <a class="navbar-brand" href="#">TalentFlow</a>

        <div>
            <a href="/dashboard" class="text-white me-3 text-decoration-none">
                Dashboard
            </a>

            <a href="/jobs-ui" class="text-white me-3 text-decoration-none">
                Jobs
            </a>

            <a href="/applications-ui" class="text-white me-3 text-decoration-none">
                Applications
            </a>

            <a href="/interviews-ui" class="text-white me-3 text-decoration-none">
                Interviews
            </a>

            <a href="/technical-tasks-ui" class="text-white text-decoration-none">
                Tasks
            </a>

            <button
                id="logoutBtn"
                class="btn btn-outline-light btn-sm ms-3">
                Logout
            </button>
        </div>
    </nav>

    <div class="container py-4">
        @yield('content')
    </div>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

    @stack('scripts')
</body>

<script>
document.getElementById('logoutBtn')?.addEventListener('click', async function () {

    const token = localStorage.getItem('auth_token');

    try {
        if (token) {
            await fetch('/api/logout', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token}`
                }
            });
        }
    } catch (error) {
        console.error('Logout error:', error);
    }

    // Remove token from browser.
    localStorage.removeItem('auth_token');

    // Return to login page.
    window.location.href = '/login-ui';
});
</script>


</html>