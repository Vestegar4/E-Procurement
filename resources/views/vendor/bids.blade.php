@extends('layouts.vendor')

@section('title', 'Penawaran Saya')

@section('content')

    <div class="container-fluid p-0">

        <div class="mb-4">

            <h4 class="fw-bold mb-1">
                Penawaran Saya
            </h4>

            <p class="text-muted mb-0">
                Daftar penawaran tender yang telah dikirim.
            </p>

        </div>

        <div class="card card-custom">

            <div class="card-body p-4">

                <div class="table-responsive">

                    <table class="table align-middle">

                        <thead>

                            <tr>
                                <th>Tender</th>
                                <th>Nominal</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Proposal</th>
                            </tr>

                        </thead>

                        <tbody>

                            @forelse($bids as $bid)
                                <tr>

                                    <td>
                                        <div class="fw-bold">
                                            {{ $bid->tender->title }}
                                        </div>
                                    </td>

                                    <td>
                                        Rp {{ number_format($bid->bid_amount, 0, ',', '.') }}
                                    </td>

                                    <td>

                                        @if ($bid->status == 'pending')
                                            <span class="badge bg-warning">
                                                Pending
                                            </span>
                                        @elseif($bid->status == 'accepted')
                                            <span class="badge bg-success">
                                                Accepted
                                            </span>
                                        @elseif($bid->status == 'rejected')
                                            <span class="badge bg-danger">
                                                Rejected
                                            </span>
                                        @endif

                                    </td>

                                    <td>
                                        {{ $bid->created_at->format('d M Y') }}
                                    </td>

                                    <td>

                                        <a href="{{ asset('storage/' . $bid->proposal_file) }}" target="_blank"
                                            class="btn btn-sm btn-outline-primary">

                                            Download

                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="5" class="text-center py-5 text-muted">

                                        Belum ada penawaran.

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

                <div class="mt-4">

                    {{ $bids->links() }}

                </div>

            </div>

        </div>

    </div>

@endsection
