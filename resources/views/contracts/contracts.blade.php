@extends('layouts.home')

@section('content')

<div class="content-area">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Contracts Management
            </h2>

            <p class="text-muted mb-0">
                Monitor all procurement contracts and agreement status
            </p>
        </div>

        <button class="btn btn-primary px-4">
            + Add Contract
        </button>

    </div>

    <!-- TABLE -->
    <div class="card shadow-sm border-0">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <h5 class="fw-bold mb-0">
                    Contract List
                </h5>

                <input
                    type="text"
                    class="form-control w-25"
                    placeholder="Search contract...">

            </div>

            <div class="table-responsive">

                <table class="table align-middle">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Vendor</th>
                            <th>Contract Name</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($contracts as $contract)

                        <tr>

                            <td>
                                #{{ $contract->id }}
                            </td>

                            <td>
                                {{ $contract->vendor_name ?? '-' }}
                            </td>

                            <td>
                                {{ $contract->title ?? 'Procurement Contract' }}
                            </td>

                            <td>
                                Rp {{ number_format($contract->total_amount ?? 0, 0, ',', '.') }}
                            </td>

                            <td>

                                @if(($contract->status ?? '') == 'approved')

                                <span class="badge bg-success">
                                    Approved
                                </span>

                                @elseif(($contract->status ?? '') == 'pending')

                                <span class="badge bg-warning text-dark">
                                    Pending
                                </span>

                                @else

                                <span class="badge bg-secondary">
                                    {{ $contract->status ?? 'Unknown' }}
                                </span>

                                @endif

                            </td>

                            <td>
                                {{ $contract->created_at->format('d M Y') }}
                            </td>

                            <td>

                                <button class="btn btn-sm btn-primary">
                                    View
                                </button>

                                <button class="btn btn-sm btn-warning">
                                    Edit
                                </button>

                            </td>

                        </tr>

                        @empty

                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                No contracts available
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection