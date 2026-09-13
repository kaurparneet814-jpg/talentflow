@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<h2 class="mb-4">Recruitment Dashboard</h2>

<div class="row g-3">

    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Total Jobs</h6>
                <h3 id="totalJobs">0</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Total Applications</h6>
                <h3 id="totalApplications">0</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Average Score</h6>
                <h3 id="averageScore">0</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Overdue Tasks</h6>
                <h3 id="overdueTasks">0</h3>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', async function () {
        // Read the recruiter/admin token saved after login.
        const token = localStorage.getItem('auth_token');

        if (!token) {
            window.location.href = '/login-ui';
            return;
        }

        try {
            const response = await fetch('/api/dashboard', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token}`
                }
            });

            if (response.status === 401 || response.status === 403) {
                localStorage.removeItem('auth_token');
                window.location.href = '/login-ui';
                return;
            }

            const data = await response.json();
            const analytics = data.analytics;

            document.getElementById('totalJobs').textContent =
                analytics.total_jobs ?? 0;

            document.getElementById('totalApplications').textContent =
                analytics.total_applications ?? 0;

            document.getElementById('averageScore').textContent =
                analytics.average_resume_score ?? 0;

            document.getElementById('overdueTasks').textContent =
                analytics.overdue_technical_tasks ?? 0;

        } catch (error) {
            console.error('Dashboard error:', error);
        }
    });
</script>
@endpush