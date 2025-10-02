@extends('layouts.app')

@section('title', __('Projects'))
@section('page-title', __('Projects'))

@section('content')
<div class="row">
    <!-- Header Actions -->
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1">{{ __('Projects') }}</h2>
                <p class="text-muted mb-0">{{ __('Manage and track your projects') }}</p>
            </div>
            <div>
                @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                <a href="{{ route('projects.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>
                    {{ __('New Project') }}
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <form id="filter-form" class="row g-3">
                    <div class="col-md-4">
                        <label for="status" class="form-label">{{ __('Status') }}</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">{{ __('All Statuses') }}</option>
                            <option value="en_cours" {{ request('status') === 'en_cours' ? 'selected' : '' }}>
                                {{ __('In Progress') }}
                            </option>
                            <option value="terminé" {{ request('status') === 'terminé' ? 'selected' : '' }}>
                                {{ __('Completed') }}
                            </option>
                            <option value="annulé" {{ request('status') === 'annulé' ? 'selected' : '' }}>
                                {{ __('Cancelled') }}
                            </option>
                        </select>
                    </div>

                    @if(auth()->user()->isAdmin())
                    <div class="col-md-4">
                        <label for="manager_id" class="form-label">{{ __('Manager') }}</label>
                        <select class="form-select" id="manager_id" name="manager_id">
                            <option value="">{{ __('All Managers') }}</option>
                            @foreach($managers as $manager)
                            <option value="{{ $manager->id }}" {{ request('manager_id') == $manager->id ? 'selected' : '' }}>
                                {{ $manager->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="col-md-4">
                        <label for="search" class="form-label">{{ __('Search') }}</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="search" name="search"
                                   placeholder="{{ __('Search projects...') }}" value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bi bi-funnel me-2"></i>{{ __('Filter') }}
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="clearFilters()">
                            <i class="bi bi-x-circle me-2"></i>{{ __('Clear') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Projects Grid -->
    <div class="col-12">
        <div id="projects-container">
            <!-- Loading State -->
            <div class="row" id="loading-skeleton">
                @for($i = 0; $i < 6; $i++)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="placeholder-glow">
                                <h5 class="card-title">
                                    <span class="placeholder col-8"></span>
                                </h5>
                                <p class="card-text">
                                    <span class="placeholder col-12"></span>
                                    <span class="placeholder col-8"></span>
                                    <span class="placeholder col-6"></span>
                                </p>
                                <div class="d-flex justify-content-between">
                                    <span class="placeholder col-3"></span>
                                    <span class="placeholder col-2"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endfor
            </div>

            <!-- Projects will be loaded here -->
            <div class="row" id="projects-grid" style="display: none;">
                <!-- Projects cards will be dynamically inserted -->
            </div>

            <!-- Empty State -->
            <div id="empty-state" class="text-center py-5" style="display: none;">
                <i class="bi bi-folder-x text-muted" style="font-size: 4rem;"></i>
                <h4 class="mt-3 text-muted">{{ __('No Projects Found') }}</h4>
                <p class="text-muted">
                    @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                        {{ __('Create your first project to get started') }}
                    @else
                        {{ __('No projects available at the moment') }}
                    @endif
                </p>
                @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                <a href="{{ route('projects.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>
                    {{ __('Create Project') }}
                </a>
                @endif
            </div>
        </div>

        <!-- Pagination -->
        <nav id="pagination-container" aria-label="{{ __('Projects pagination') }}" style="display: none;">
            <!-- Pagination will be loaded here -->
        </nav>
    </div>
</div>

<!-- Project Actions Modal -->
<div class="modal fade" id="projectActionsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Project Actions') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="d-grid gap-2">
                    <a href="#" class="btn btn-outline-primary" id="view-project-btn">
                        <i class="bi bi-eye me-2"></i>{{ __('View Details') }}
                    </a>
                    <a href="#" class="btn btn-outline-success" id="edit-project-btn">
                        <i class="bi bi-pencil me-2"></i>{{ __('Edit Project') }}
                    </a>
                    <button type="button" class="btn btn-outline-warning" onclick="updateProjectStatus()">
                        <i class="bi bi-arrow-repeat me-2"></i>{{ __('Change Status') }}
                    </button>
                    @if(auth()->user()->isAdmin())
                    <button type="button" class="btn btn-outline-danger" onclick="deleteProject()">
                        <i class="bi bi-trash me-2"></i>{{ __('Delete Project') }}
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentPage = 1;
let selectedProject = null;

document.addEventListener('DOMContentLoaded', function() {
    loadProjects();

    // Filter form submission
    document.getElementById('filter-form').addEventListener('submit', function(e) {
        e.preventDefault();
        currentPage = 1;
        loadProjects();
    });

    // Real-time search
    let searchTimeout;
    document.getElementById('search').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            currentPage = 1;
            loadProjects();
        }, 500);
    });
});

function loadProjects(page = 1) {
    const formData = new FormData(document.getElementById('filter-form'));
    const params = new URLSearchParams(formData);
    params.append('page', page);

    showLoading();

    axios.get(`/ajax/projects?${params}`)
        .then(response => {
            const data = response.data;
            renderProjects(data.data);
            renderPagination(data.meta);
            hideLoading();
        })
        .catch(error => {
            console.error('Failed to load projects:', error);
            showError('{{ __("Failed to load projects. Please try again.") }}');
            hideLoading();
        });
}

function renderProjects(projects) {
    const container = document.getElementById('projects-grid');
    const emptyState = document.getElementById('empty-state');

    if (projects.length === 0) {
        container.style.display = 'none';
        emptyState.style.display = 'block';
        return;
    }

    emptyState.style.display = 'none';
    container.style.display = 'flex';

    container.innerHTML = projects.map(project => `
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card hover-shadow h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h5 class="card-title mb-0">
                            <a href="/projects/${project.id}" class="text-decoration-none">
                                ${project.title}
                            </a>
                        </h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary" type="button"
                                    data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/projects/${project.id}">
                                    <i class="bi bi-eye me-2"></i>{{ __('View') }}
                                </a></li>
                                @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                                <li><a class="dropdown-item" href="/projects/${project.id}/edit">
                                    <i class="bi bi-pencil me-2"></i>{{ __('Edit') }}
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-warning" href="#"
                                       onclick="openProjectActions(${project.id})">
                                    <i class="bi bi-gear me-2"></i>{{ __('Actions') }}
                                </a></li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    <p class="card-text text-truncate-2 mb-3">
                        ${project.description || '{{ __("No description") }}'}
                    </p>

                    <div class="mb-3">
                        <span class="badge status-${project.status} status-badge">
                            ${getStatusText(project.status)}
                        </span>
                    </div>

                    <div class="row text-muted small mb-3">
                        <div class="col-6">
                            <i class="bi bi-person me-1"></i>
                            ${project.manager?.name || '{{ __("No manager") }}'}
                        </div>
                        <div class="col-6">
                            <i class="bi bi-calendar me-1"></i>
                            ${formatDate(project.start_date)}
                        </div>
                    </div>

                    <div class="progress mb-2" style="height: 6px;">
                        <div class="progress-bar bg-${getProgressColor(project.progress_percentage || 0)}"
                             style="width: ${project.progress_percentage || 0}%"></div>
                    </div>
                    <div class="d-flex justify-content-between small text-muted">
                        <span>{{ __('Progress') }}</span>
                        <span>${Math.round(project.progress_percentage || 0)}%</span>
                    </div>
                </div>

                <div class="card-footer bg-transparent">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="fw-bold text-primary">${project.tasks_count || 0}</div>
                            <small class="text-muted">{{ __('Tasks') }}</small>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold text-success">${project.completed_tasks || 0}</div>
                            <small class="text-muted">{{ __('Done') }}</small>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold text-info">${project.total_hours || 0}h</div>
                            <small class="text-muted">{{ __('Hours') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

function renderPagination(meta) {
    const container = document.getElementById('pagination-container');

    if (meta.last_page <= 1) {
        container.style.display = 'none';
        return;
    }

    container.style.display = 'block';

    let pagination = '<ul class="pagination justify-content-center">';

    // Previous button
    if (meta.current_page > 1) {
        pagination += `
            <li class="page-item">
                <a class="page-link" href="#" onclick="loadProjects(${meta.current_page - 1})">
                    <i class="bi bi-chevron-left"></i>
                </a>
            </li>
        `;
    }

    // Page numbers
    for (let i = Math.max(1, meta.current_page - 2);
         i <= Math.min(meta.last_page, meta.current_page + 2);
         i++) {
        pagination += `
            <li class="page-item ${i === meta.current_page ? 'active' : ''}">
                <a class="page-link" href="#" onclick="loadProjects(${i})">${i}</a>
            </li>
        `;
    }

    // Next button
    if (meta.current_page < meta.last_page) {
        pagination += `
            <li class="page-item">
                <a class="page-link" href="#" onclick="loadProjects(${meta.current_page + 1})">
                    <i class="bi bi-chevron-right"></i>
                </a>
            </li>
        `;
    }

    pagination += '</ul>';
    container.innerHTML = pagination;
}

function showLoading() {
    document.getElementById('loading-skeleton').style.display = 'flex';
    document.getElementById('projects-grid').style.display = 'none';
    document.getElementById('empty-state').style.display = 'none';
}

function hideLoading() {
    document.getElementById('loading-skeleton').style.display = 'none';
}

function clearFilters() {
    document.getElementById('filter-form').reset();
    currentPage = 1;
    loadProjects();
}

function openProjectActions(projectId) {
    selectedProject = projectId;
    document.getElementById('view-project-btn').href = `/projects/${projectId}`;
    document.getElementById('edit-project-btn').href = `/projects/${projectId}/edit`;

    const modal = new bootstrap.Modal(document.getElementById('projectActionsModal'));
    modal.show();
}

function updateProjectStatus() {
    if (!selectedProject) return;

    // This would open a status change modal
    console.log('Update status for project:', selectedProject);
}

function deleteProject() {
    if (!selectedProject) return;

    if (confirm('{{ __("Are you sure you want to delete this project?") }}')) {
        axios.delete(`/ajax/projects/${selectedProject}`)
            .then(response => {
                if (response.data.success) {
                    showSuccess('{{ __("Project deleted successfully") }}');
                    loadProjects(currentPage);
                    bootstrap.Modal.getInstance(document.getElementById('projectActionsModal')).hide();
                }
            })
            .catch(error => {
                console.error('Failed to delete project:', error);
                showError('{{ __("Failed to delete project") }}');
            });
    }
}

// Utility functions
function getStatusText(status) {
    const statuses = {
        'en_cours': '{{ __("In Progress") }}',
        'terminé': '{{ __("Completed") }}',
        'annulé': '{{ __("Cancelled") }}'
    };
    return statuses[status] || status;
}

function getProgressColor(percentage) {
    if (percentage < 30) return 'danger';
    if (percentage < 70) return 'warning';
    return 'success';
}

function formatDate(dateString) {
    if (!dateString) return '';
    return new Date(dateString).toLocaleDateString();
}
</script>
@endpush