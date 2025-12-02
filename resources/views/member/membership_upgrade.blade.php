@extends('layouts.inner_page')

@section('page_title', 'Upgrade Membership')

@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('member.dashboard', $organization->slug) }}"><i class="mdi mdi-home-outline"></i></a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('member.dashboard', $organization->slug) }}">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">Upgrade Membership</li>
    </ol>
@endsection

@php
    $initialMembershipId = is_array($initialMembershipData ?? null) ? $initialMembershipData['id'] : null;
    $currentMembershipId = is_array($currentMembershipData ?? null) ? $currentMembershipData['id'] : null;
@endphp

@push('styles')
    <style>
        .membership-upgrade-page .membership-option {
            background-color: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.25);
            transition: background-color .2s ease, border-color .2s ease, color .2s ease;
        }

        .membership-upgrade-page .membership-option .fw-bold {
            color: #fdfdfd;
        }

        .membership-upgrade-page .membership-option .text-muted,
        .membership-upgrade-page .plan-detail-muted,
        .membership-upgrade-page .current-membership-label,
        .membership-upgrade-page .text-muted {
            color: rgba(255, 255, 255, 0.7) !important;
        }

        .membership-upgrade-page .membership-option.active,
        .membership-upgrade-page .membership-option:focus {
            background: rgba(13, 110, 253, 0.35);
            border-color: rgba(13, 110, 253, 0.7);
            color: #fff;
        }

        .membership-upgrade-page .membership-option.active .text-muted {
            color: rgba(255, 255, 255, 0.85) !important;
        }

        .membership-upgrade-page .membership-option.active .fw-bold {
            color: #fff;
        }

        .membership-upgrade-page .membership-option:hover:not(.active) {
            background-color: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.4);
        }
    </style>
@endpush

@section('content')
    <section class="content membership-upgrade-page">
        <div class="row g-3">
            <div class="col-lg-4 col-md-5">
                <div class="box h-100">
                        <div class="box-header with-border">
                            <h4 class="box-title mb-0">Available Memberships</h4>
                        </div>
                        <div class="box-body">
                            @if ($pendingRequest)
                                <div class="alert alert-warning mb-3">
                                    You already requested an upgrade to
                                    <strong>{{ $pendingRequest->membership?->name ?? 'another plan' }}</strong>.
                                    Current status: <strong>{{ ucfirst($pendingRequest->status) }}</strong>.
                                </div>
                            @endif
                            <div id="membership-list-empty" class="{{ $memberships->isEmpty() ? '' : 'd-none' }}">
                                <p class="text-muted mb-0">No other memberships are available to join right now.</p>
                            </div>
                        <div class="list-group" id="membership-options">
                            @foreach ($memberships as $option)
                                <button type="button"
                                    class="list-group-item list-group-item-action membership-option {{ $loop->first ? 'active' : '' }}"
                                    data-membership-id="{{ $option->id }}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">{{ $option->name }}</span>
                                        <span class="text-muted">{{ number_format((float) $option->joining_fee, 2) }}</span>
                                    </div>
                                    <small class="text-muted">Monthly Fee:
                                        {{ number_format((float) $option->monthly_fee, 2) }}</small>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-7">
                <div class="box mb-3">
                    <div class="box-body">
                        <p class="text-muted mb-1">Current Membership</p>
                        @if ($currentMembership)
                            <h3 class="mb-1" id="current-membership-name">{{ $currentMembership->name }}</h3>
                            <p class="mb-0 text-muted">
                                Membership Code:
                                <span id="current-membership-code">{{ $user->membership_code ?? '-' }}</span>
                            </p>
                        @else
                            <p class="mb-0">You do not have a membership assigned yet.</p>
                        @endif
                    </div>
                </div>
                <div class="box">
                        <div class="box-header with-border d-flex justify-content-between align-items-center">
                            <h4 class="box-title mb-0">Plan Details</h4>
                        <span class="badge bg-secondary current-membership-label" id="current-membership-label">
                            Current: {{ $currentMembership->name ?? 'Not Assigned' }}
                        </span>
                    </div>
                    <div class="box-body">
                        <div id="membership-upgrade-alert" class="alert d-none" role="alert"></div>
                        <div id="membership-detail-loading" class="alert alert-info d-none" role="alert">
                            Loading selected membership...
                        </div>
                        <div id="membership-detail-empty" class="{{ $initialMembershipData ? 'd-none' : '' }}">
                            <p class="text-muted mb-0">Select a membership from the left to see its rules and benefits.</p>
                        </div>
                        <div id="membership-detail-content" class="{{ $initialMembershipData ? '' : 'd-none' }}">
                            <h3 id="selected-membership-name" class="mb-2"></h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1">
                                        <strong>Joining Fee:</strong>
                                        <span id="selected-joining-fee">-</span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1">
                                        <strong>Monthly Fee:</strong>
                                        <span id="selected-monthly-fee">-</span>
                                    </p>
                                </div>
                            </div>
                            <div class="mt-3">
                                <h5 class="mb-2">Rules</h5>
                                <ul class="list-unstyled mb-3" id="selected-rules"></ul>
                            </div>
                            <div class="mt-3">
                                <h5 class="mb-2">Benefits</h5>
                                <ul class="list-unstyled mb-0" id="selected-benefits"></ul>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer text-end">
                        <button class="btn btn-primary"
                            id="apply-membership-btn"
                            {{ $memberships->isEmpty() || $pendingRequest ? 'disabled' : '' }}>
                            Upgrade To This Plan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const detailUrlTemplate = @json(route('member.membership.details', [$organization->slug, '__MEMBERSHIP__']));
            const changeUrl = @json(route('member.membership.change', $organization->slug));
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';
            const detailContent = document.getElementById('membership-detail-content');
            const detailEmpty = document.getElementById('membership-detail-empty');
            const detailLoading = document.getElementById('membership-detail-loading');
            const alertBox = document.getElementById('membership-upgrade-alert');
            const applyButton = document.getElementById('apply-membership-btn');
            const listWrapper = document.getElementById('membership-options');
            const listEmptyState = document.getElementById('membership-list-empty');
            const currentLabel = document.getElementById('current-membership-label');
            const currentNameEl = document.getElementById('current-membership-name');
            const currentCodeEl = document.getElementById('current-membership-code');
            const initialDetails = @json($initialMembershipData);
            const hasPendingRequest = @json((bool) $pendingRequest);
            let selectedMembershipId = @json($initialMembershipId);
            let currentMembershipId = @json($currentMembershipId);
            const applyButtonDefault = applyButton ? applyButton.innerHTML : '';

            if (listWrapper) {
                listWrapper.querySelectorAll('.membership-option').forEach(button => {
                    button.addEventListener('click', onMembershipClick);
                });
            }

            if (applyButton) {
                applyButton.addEventListener('click', () => {
                    if (!selectedMembershipId) {
                        return;
                    }
                    if (hasPendingRequest) {
                        showAlert('You already have an upgrade request that is pending review.', 'warning');
                        return;
                    }
                    if (!window.confirm('Are you sure you want to request this membership upgrade?')) {
                        return;
                    }
                    setApplyLoading(true);
                    hideAlert();
                    fetch(changeUrl, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken || ''
                        },
                        body: JSON.stringify({membership_id: selectedMembershipId})
                    })
                        .then(handleResponse)
                        .then(({message, redirect}) => {
                            if (redirect) {
                                window.location.href = redirect;
                                return;
                            }
                            showAlert(message || 'Request submitted.', 'success');
                        })
                        .catch(error => {
                            showAlert(error.message || 'Unable to change membership right now.', 'danger');
                        })
                        .finally(() => {
                            selectedMembershipId = null;
                            setApplyLoading(false);
                            if (applyButton) {
                                applyButton.disabled = true;
                            }
                        });
                });
            }

            if (initialDetails) {
                updateDetailPanel(initialDetails);
            } else if (applyButton) {
                applyButton.disabled = true;
            }

            function onMembershipClick(event) {
                const button = event.currentTarget;
                const membershipId = button.getAttribute('data-membership-id');
                if (!membershipId) {
                    return;
                }
                setActiveButton(button);
                fetchMembershipDetails(membershipId);
            }

            function fetchMembershipDetails(membershipId) {
                setLoading(true);
                hideAlert();
                fetch(detailUrlTemplate.replace('__MEMBERSHIP__', membershipId))
                    .then(handleResponse)
                    .then(data => {
                        updateDetailPanel(data);
                    })
                    .catch(error => {
                        showAlert(error.message || 'Unable to load membership details.', 'danger');
                    })
                    .finally(() => {
                        setLoading(false);
                    });
            }

            function updateDetailPanel(data) {
                selectedMembershipId = data.id;
                detailEmpty.classList.add('d-none');
                detailContent.classList.remove('d-none');
                document.getElementById('selected-membership-name').textContent = data.name;
                document.getElementById('selected-joining-fee').textContent = formatCurrency(data.joining_fee);
                document.getElementById('selected-monthly-fee').textContent = formatCurrency(data.monthly_fee);
                renderList('selected-rules', data.rules, 'No rules defined for this membership yet.');
                renderList('selected-benefits', data.benefits, 'No benefits defined for this membership yet.');
                if (applyButton) {
                    applyButton.disabled = false;
                }
            }

            function renderList(targetId, items, emptyText) {
                const container = document.getElementById(targetId);
                container.innerHTML = '';
                if (!items || items.length === 0) {
                    const emptyRow = document.createElement('li');
                    emptyRow.className = 'text-muted';
                    emptyRow.textContent = emptyText;
                    container.appendChild(emptyRow);
                    return;
                }

                items.forEach(item => {
                    const row = document.createElement('li');
                    row.className = 'mb-2';
                    const title = document.createElement('strong');
                    title.textContent = item.title;
                    row.appendChild(title);
                    if (item.description) {
                        const description = document.createElement('div');
                        description.className = 'text-muted';
                        description.textContent = item.description;
                        row.appendChild(description);
                    }
                    container.appendChild(row);
                });
            }

            function updateCurrentMembership(data) {
                currentMembershipId = data.id;
                if (currentLabel) {
                    currentLabel.textContent = `Current: ${data.name}`;
                }
                if (currentNameEl) {
                    currentNameEl.textContent = data.name;
                }
                if (currentCodeEl && data.membership_code) {
                    currentCodeEl.textContent = data.membership_code;
                }
            }

            function refreshMembershipLists(newMembership, previousMembership) {
                removeMembershipOption(newMembership.id);
                if (previousMembership) {
                    addMembershipOption(previousMembership);
                }

                if (!listWrapper) {
                    return;
                }

                const remainingOption = listWrapper.querySelector('.membership-option');
                if (remainingOption) {
                    if (listEmptyState) {
                        listEmptyState.classList.add('d-none');
                    }
                    remainingOption.click();
                } else {
                    if (listEmptyState) {
                        listEmptyState.classList.remove('d-none');
                    }
                    detailContent.classList.add('d-none');
                    detailEmpty.classList.remove('d-none');
                }
            }

            function removeMembershipOption(id) {
                if (!listWrapper) {
                    return;
                }
                const button = listWrapper.querySelector(`[data-membership-id="${id}"]`);
                if (button) {
                    button.remove();
                }
            }

            function addMembershipOption(data) {
                if (!listWrapper) {
                    return;
                }
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'list-group-item list-group-item-action membership-option';
                button.setAttribute('data-membership-id', data.id);
                button.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold">${data.name}</span>
                        <span class="text-muted">${formatCurrency(data.joining_fee)}</span>
                    </div>
                    <small class="text-muted">Monthly Fee: ${formatCurrency(data.monthly_fee)}</small>
                `;
                button.addEventListener('click', onMembershipClick);
                listWrapper.appendChild(button);
                if (listEmptyState) {
                    listEmptyState.classList.add('d-none');
                }
            }

            function setActiveButton(activeButton) {
                if (!listWrapper) {
                    return;
                }
                listWrapper.querySelectorAll('.membership-option').forEach(button => {
                    if (button === activeButton) {
                        button.classList.add('active');
                    } else {
                        button.classList.remove('active');
                    }
                });
            }

            function setLoading(isLoading) {
                if (!detailLoading) {
                    return;
                }
                if (isLoading) {
                    detailLoading.classList.remove('d-none');
                } else {
                    detailLoading.classList.add('d-none');
                }
            }

            function setApplyLoading(isLoading) {
                if (!applyButton) {
                    return;
                }
                if (isLoading) {
                    applyButton.disabled = true;
                    applyButton.innerHTML = 'Updating...';
                } else {
                    applyButton.innerHTML = applyButtonDefault;
                }
            }

            function showAlert(message, type) {
                if (!alertBox) {
                    return;
                }
                alertBox.textContent = message;
                alertBox.className = `alert alert-${type}`;
            }

            function hideAlert() {
                if (!alertBox) {
                    return;
                }
                alertBox.className = 'alert d-none';
                alertBox.textContent = '';
            }

            function formatCurrency(value) {
                const number = Number(value) || 0;
                return number.toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            function handleResponse(response) {
                return response.json().then(data => {
                    if (!response.ok) {
                        const error = new Error(data.message || 'Request failed.');
                        throw error;
                    }
                    return data;
                });
            }
        });
    </script>
@endpush
