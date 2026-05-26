@extends('layouts.vendor')

@section('title', 'Submit Penawaran')

@section('content')

    <div class="container-fluid p-0">

        <div class="mb-4">

            <h4 class="fw-bold mb-1">
                Submit Penawaran
            </h4>

            <p class="text-muted mb-0">
                {{ $tender->title }}
            </p>

        </div>

        <div class="card card-custom">

            <div class="card-body p-4">

                <form action="{{ route('vendor.bids.store', $tender->id) }}" method="POST" enctype="multipart/form-data">

                    @csrf

                    <div class="mb-4">

                        <label class="form-label fw-semibold">
                            Nominal Penawaran
                        </label>

                        <input type="number" name="bid_amount" class="form-control" required>

                    </div>

                    <div class="mb-4">

                        <label class="form-label fw-semibold">
                            Upload Proposal (PDF)
                        </label>

                        <input type="file" name="proposal_file" class="form-control" accept=".pdf" required>

                    </div>

                    <div class="mb-4">

                        <label class="form-label fw-semibold">
                            Catatan Tambahan
                        </label>

                        <textarea name="notes" rows="4" class="form-control"></textarea>

                    </div>

                    <button type="submit" class="btn btn-primary">

                        Kirim Penawaran

                    </button>

                </form>

            </div>

        </div>

    </div>

@endsection
