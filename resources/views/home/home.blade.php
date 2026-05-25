@extends('layouts.app')

@section('title', 'Home')

@section('content')

    {{-- HERO --}}
    <section class="landing-hero">

        <div class="container py-5">

            <div class="row align-items-center min-vh-75">

                <div class="col-lg-6">

                    <span class="hero-badge">
                        Modern E-Procurement Platform
                    </span>

                    <h1 class="hero-title mt-4">
                        Digital Procurement Solution
                        for Modern Enterprise
                    </h1>

                    <p class="hero-description mt-4">
                        Kelola proses tender, vendor management,
                        bid monitoring, invoice, dan procurement
                        perusahaan dalam satu platform terintegrasi.
                    </p>

                    <div class="d-flex flex-wrap gap-3 mt-4">

                        <a href="{{ route('login') }}" class="btn btn-pink btn-lg px-4">
                            Login
                        </a>

                        <a href="{{ route('register') }}" class="btn btn-outline-danger btn-lg px-4">
                            Register Vendor
                        </a>

                    </div>

                </div>

                <div class="col-lg-6 text-center">

                    <div class="hero-card p-5">

                        <div class="row g-4">

                            <div class="col-6">
                                <div class="feature-mini-card">
                                    <h3>120+</h3>
                                    <p>Active Vendors</p>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="feature-mini-card">
                                    <h3>56</h3>
                                    <p>Open Tenders</p>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="feature-mini-card">
                                    <h3>98%</h3>
                                    <p>Procurement Efficiency</p>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="feature-mini-card">
                                    <h3>24/7</h3>
                                    <p>Monitoring System</p>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

    {{-- FEATURES --}}
    <section class="py-5">

        <div class="container">

            <div class="text-center mb-5">

                <h2 class="section-title">
                    Procurement Features
                </h2>

                <p class="section-subtitle">
                    Semua kebutuhan procurement perusahaan
                    dalam satu dashboard modern.
                </p>

            </div>

            <div class="row g-4">

                <div class="col-lg-3 col-md-6">
                    <div class="landing-feature-card h-100">
                        <h5>Tender Management</h5>

                        <p>
                            Kelola tender procurement secara
                            digital dan terstruktur.
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="landing-feature-card h-100">
                        <h5>Vendor Verification</h5>

                        <p>
                            Sistem approval vendor dengan
                            validasi dokumen perusahaan.
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="landing-feature-card h-100">
                        <h5>Bid Monitoring</h5>

                        <p>
                            Monitoring seluruh proses bidding
                            dan evaluasi vendor.
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="landing-feature-card h-100">
                        <h5>Invoice Tracking</h5>

                        <p>
                            Monitoring invoice dan purchase order
                            secara realtime.
                        </p>
                    </div>
                </div>

            </div>

        </div>

    </section>

    {{-- FLOW --}}
    <section class="py-5 bg-light">

        <div class="container">

            <div class="text-center mb-5">

                <h2 class="section-title">
                    Procurement Flow
                </h2>

            </div>

            <div class="row g-4 text-center">

                <div class="col-md-3">
                    <div class="flow-card">
                        <div class="flow-number">1</div>
                        <h5>Vendor Register</h5>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="flow-card">
                        <div class="flow-number">2</div>
                        <h5>Admin Verification</h5>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="flow-card">
                        <div class="flow-number">3</div>
                        <h5>Join Tender</h5>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="flow-card">
                        <div class="flow-number">4</div>
                        <h5>Bid & Result</h5>
                    </div>
                </div>

            </div>

        </div>

    </section>

    {{-- FOOTER --}}
    <footer class="landing-footer py-4">

        <div class="container text-center">

            <h5 class="fw-bold text-danger">
                Proculus E-Procurement
            </h5>

            <p class="mb-0 text-muted">
                Modern digital procurement platform for enterprise procurement management.
            </p>

        </div>

    </footer>

@endsection
