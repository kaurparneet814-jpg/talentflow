@extends('layouts.app')

@section('title', 'Jobs')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Jobs</h2>

    <button
        class="btn btn-dark"
        data-bs-toggle="modal"
        data-bs-target="#createJobModal">
        Add Job
    </button>
</div>

<div class="card shadow-sm">
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Department</th>
                        <th>Experience</th>
                        <th>Status</th>
                        <th>Deadline</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody id="jobsTable">
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

<!-- Create Job Modal -->
<div class="modal fade" id="createJobModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form id="createJobForm">

                <div class="modal-header">
                    <h5 class="modal-title">Create Job</h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Title</label>
                            <input
                                type="text"
                                id="title"
                                class="form-control"
                                required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Department</label>
                            <input
                                type="text"
                                id="department"
                                class="form-control"
                                required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea
                                id="description"
                                class="form-control"
                                required></textarea>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Experience</label>
                            <input
                                type="number"
                                id="experience"
                                class="form-control"
                                min="0"
                                required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Min Salary</label>
                            <input
                                type="number"
                                id="salaryMin"
                                class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Max Salary</label>
                            <input
                                type="number"
                                id="salaryMax"
                                class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Application Deadline
                            </label>

                            <input
                                type="date"
                                id="deadline"
                                class="form-control"
                                required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status</label>

                            <select id="status" class="form-select">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-dark">
                        Create Job
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const token = localStorage.getItem('auth_token');

    if (!token) {
        window.location.href = '/login-ui';
    }

    // Load all jobs from the API.
    async function loadJobs() {
        const response = await fetch('/api/jobs', {
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        });

        if (!response.ok) {
            console.error('Unable to load jobs.');
            return;
        }

        const data = await response.json();
        const jobs = data.jobs ?? [];

        const table = document.getElementById('jobsTable');

        if (jobs.length === 0) {
            table.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center">
                        No jobs found.
                    </td>
                </tr>
            `;
            return;
        }

        table.innerHTML = jobs.map(job => `
            <tr>
                <td>${job.title}</td>
                <td>${job.department}</td>
                <td>${job.experience_required} year(s)</td>
                <td>${job.status}</td>
                <td>${job.application_deadline}</td>
                <td>
                    <button
                        class="btn btn-sm btn-danger"
                        onclick="deleteJob(${job.id})">
                        Delete
                    </button>
                </td>
            </tr>
        `).join('');
    }

    // Create a new job.
    document.getElementById('createJobForm')
        .addEventListener('submit', async function (event) {
            event.preventDefault();

            const response = await fetch('/api/jobs', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify({
                    title: document.getElementById('title').value,
                    department: document.getElementById('department').value,
                    description: document.getElementById('description').value,
                    experience_required:
                        document.getElementById('experience').value,
                    salary_min:
                        document.getElementById('salaryMin').value || null,
                    salary_max:
                        document.getElementById('salaryMax').value || null,
                    application_deadline:
                        document.getElementById('deadline').value,
                    status: document.getElementById('status').value
                })
            });

            const data = await response.json();

            if (!response.ok) {
                alert(data.message ?? 'Unable to create job.');
                return;
            }

            alert('Job created successfully.');

            // Close the modal.
            const modalElement =
                document.getElementById('createJobModal');

            bootstrap.Modal
                .getOrCreateInstance(modalElement)
                .hide();

            // Reset form and reload table.
            document.getElementById('createJobForm').reset();
            loadJobs();
        });

    // Delete a job.
    async function deleteJob(id) {
        if (!confirm('Are you sure you want to delete this job?')) {
            return;
        }

        const response = await fetch(`/api/jobs/${id}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        });

        const data = await response.json();

        if (!response.ok) {
            alert(data.message ?? 'Unable to delete job.');
            return;
        }

        alert('Job deleted successfully.');
        loadJobs();
    }

    // Load jobs when the page opens.
    loadJobs();
</script>
@endpush