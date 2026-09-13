@extends('layouts.app')

@section('title', 'Applications')

@section('content')

<h2 class="mb-4">Applications</h2>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Candidate</th>
                        <th>Job</th>
                        <th>Resume</th>
                        <th>Score</th>
                        <th>Status</th>
                        <th>Update Status</th>
                    </tr>
                </thead>

                <tbody id="applicationsTable">
                    <tr>
                        <td colspan="6" class="text-center">
                            Loading...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const token = localStorage.getItem('auth_token');

    if (!token) {
        window.location.href = '/login-ui';
        return;
    }

    // Load applications from the API.
    async function loadApplications() {

        const response = await fetch('/api/applications', {
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        });

        const data = await response.json();

        if (!response.ok) {
            alert(data.message ?? 'Unable to load applications.');
            return;
        }

        const applications = data.applications ?? [];

        let rows = '';

        applications.forEach(application => {

            rows += `
                <tr>
                    <td>${application.candidate_id ?? '-'}</td>

                    <td>${application.job_id ?? '-'}</td>

                    <td>${application.resume_id ?? '-'}</td>

                    <td>${application.score ?? '-'}</td>

                    <td>${application.status}</td>

                    <td>
                        <select
                            class="form-select status-select"
                            data-id="${application.id}">

                            <option value="applied"
                                ${application.status === 'applied' ? 'selected' : ''}>
                                Applied
                            </option>

                            <option value="screening"
                                ${application.status === 'screening' ? 'selected' : ''}>
                                Screening
                            </option>

                            <option value="shortlisted"
                                ${application.status === 'shortlisted' ? 'selected' : ''}>
                                Shortlisted
                            </option>

                            <option value="interview"
                                ${application.status === 'interview' ? 'selected' : ''}>
                                Interview
                            </option>

                            <option value="technical_task"
                                ${application.status === 'technical_task' ? 'selected' : ''}>
                                Technical Task
                            </option>

                            <option value="hired"
                                ${application.status === 'hired' ? 'selected' : ''}>
                                Hired
                            </option>

                            <option value="rejected"
                                ${application.status === 'rejected' ? 'selected' : ''}>
                                Rejected
                            </option>

                        </select>
                    </td>
                </tr>
            `;
        });

        document.getElementById('applicationsTable').innerHTML =
            rows || `
                <tr>
                    <td colspan="6" class="text-center">
                        No applications found.
                    </td>
                </tr>
            `;

        addStatusListeners();
    }

    // Add change events to status dropdowns.
    function addStatusListeners() {

        document
            .querySelectorAll('.status-select')
            .forEach(select => {

                select.addEventListener('change', async function () {

                    const applicationId = this.dataset.id;
                    const status = this.value;

                    const response = await fetch(
                        `/api/applications/${applicationId}/status`,
                        {
                            method: 'PUT',

                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'Authorization': `Bearer ${token}`
                            },

                            body: JSON.stringify({
                                status: status
                            })
                        }
                    );

                    const data = await response.json();

                    if (!response.ok) {
                        alert(data.message ?? 'Unable to update status.');
                        loadApplications();
                        return;
                    }

                    alert('Application status updated successfully.');
                });
            });
    }

    loadApplications();
});
</script>
@endpush