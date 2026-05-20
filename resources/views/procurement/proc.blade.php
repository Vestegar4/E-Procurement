@extends('layouts.home')

@section('title', 'Procurement')

@section('content')

<div class="container-fluid">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                Procurement Management
            </h3>

            <p class="text-secondary mb-0">
                Manage all tender procurement data
            </p>
        </div>

        <button class="btn btn-primary px-4 py-2">
            <i class="fa-solid fa-plus"></i>
            Add Tender
        </button>

    </div>

    <!-- TABLE CARD -->
    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table align-middle">

                    <thead>

                        <tr>
                            <th>ID</th>
                            <th>Tender Name</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($tenders as $tender)

                        <tr>

                            <td>
                                #{{ $tender->id }}
                            </td>

                            <td>

                                <div class="fw-semibold">
                                    {{ $tender->title }}
                                </div>

                            </td>

                            <td>

                                @if($tender->status == 'open')

                                <span class="badge bg-success">
                                    Open
                                </span>

                                @elseif($tender->status == 'closed')

                                <span class="badge bg-danger">
                                    Closed
                                </span>

                                @else

                                <span class="badge bg-secondary">
                                    Pending
                                </span>

                                @endif

                            </td>

                            <td>
                                {{ $tender->created_at->format('d M Y') }}
                            </td>

                            <td>

                                <div class="d-flex gap-2">

                                    <button class="btn btn-sm btn-warning">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>

                                    <button class="btn btn-sm btn-danger">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>

                                </div>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="5" class="text-center text-secondary py-4">
                                No Tender Data Found
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