@extends('layouts.home')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid">

    <!-- CARD -->
    <div class="row g-4 mb-4">

        <!-- TOTAL VENDOR -->
        <div class="col-md-4">

            <div class="card shadow-sm border-0 p-4">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <p class="text-secondary mb-2">
                            Total Vendors
                        </p>

                        <h2 class="fw-bold">
                            {{ $vendorCount }}
                        </h2>
                    </div>

                    <div class="bg-primary text-white rounded-4 p-3">
                        <i class="fa-solid fa-users fa-xl"></i>
                    </div>

                </div>

            </div>

        </div>

        <!-- TOTAL TENDER -->
        <div class="col-md-4">

            <div class="card shadow-sm border-0 p-4">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <p class="text-secondary mb-2">
                            Total Tenders
                        </p>

                        <h2 class="fw-bold">
                            {{ $tenderCount }}
                        </h2>
                    </div>

                    <div class="bg-success text-white rounded-4 p-3">
                        <i class="fa-solid fa-file-contract fa-xl"></i>
                    </div>

                </div>

            </div>

        </div>

        <!-- TOTAL RESULT -->
        <div class="col-md-4">

            <div class="card shadow-sm border-0 p-4">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <p class="text-secondary mb-2">
                            Tender Results
                        </p>

                        <h2 class="fw-bold">
                            {{ $resultCount }}
                        </h2>
                    </div>

                    <div class="bg-warning text-white rounded-4 p-3">
                        <i class="fa-solid fa-chart-column fa-xl"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- TABLE -->
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 pt-4">

            <h5 class="fw-bold">
                Latest Tenders
            </h5>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table align-middle">

                    <thead>

                        <tr>
                            <th>ID</th>
                            <th>Tender Name</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($latestTenders as $tender)

                        <tr>

                            <td>
                                #{{ $tender->id }}
                            </td>

                            <td>
                                {{ $tender->title }}
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

                        </tr>

                        @empty

                        <tr>

                            <td colspan="4" class="text-center text-secondary">
                                No Tender Data
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