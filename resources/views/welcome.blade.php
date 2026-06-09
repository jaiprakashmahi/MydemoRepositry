@extends('layouts.app')

@section('content')

<!-- Hero Section -->
<section class="hero">

    <div class="container text-center">

        <h1 class="display-4 fw-bold">
            Find Your Dream Job Today
        </h1>

        <p class="lead mt-3">
            Search thousands of jobs from top companies.
        </p>

        <div class="row justify-content-center mt-5">

            <div class="col-md-4">
                <input type="text"
                       class="form-control form-control-lg"
                       placeholder="Job Title">
            </div>

            <div class="col-md-3">
                <input type="text"
                       class="form-control form-control-lg"
                       placeholder="Location">
            </div>

            <div class="col-md-2">
                <button class="btn btn-warning btn-lg w-100">
                    Search
                </button>
            </div>

        </div>

    </div>

</section>

<!-- Statistics -->

<section class="py-5">

    <div class="container">

        <div class="row g-4">

            <div class="col-md-3">
                <div class="stat-box">
                    <h2>10,000+</h2>
                    <p>Jobs Posted</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-box">
                    <h2>5,000+</h2>
                    <p>Companies</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-box">
                    <h2>20,000+</h2>
                    <p>Candidates</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-box">
                    <h2>1,500+</h2>
                    <p>Hiring Today</p>
                </div>
            </div>

        </div>

    </div>

</section>

<!-- Featured Jobs -->

<section class="py-5">

    <div class="container">

        <h2 class="text-center mb-5">
            Featured Jobs
        </h2>

        <div class="row g-4">

            <div class="col-md-4">

                <div class="card job-card p-3">

                    <h5>Senior Laravel Developer</h5>

                    <p class="text-muted">
                        ABC Technologies
                    </p>

                    <p>
                        Experience: 3-5 Years
                    </p>

                    <a href="#"
                       class="btn btn-primary">
                        Apply Now
                    </a>

                </div>

            </div>

            <div class="col-md-4">

                <div class="card job-card p-3">

                    <h5>Project Manager</h5>

                    <p class="text-muted">
                        XYZ Solutions
                    </p>

                    <p>
                        Experience: 5+ Years
                    </p>

                    <a href="#"
                       class="btn btn-primary">
                        Apply Now
                    </a>

                </div>

            </div>

            <div class="col-md-4">

                <div class="card job-card p-3">

                    <h5>UI/UX Designer</h5>

                    <p class="text-muted">
                        Creative Studio
                    </p>

                    <p>
                        Experience: 2+ Years
                    </p>

                    <a href="#"
                       class="btn btn-primary">
                        Apply Now
                    </a>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- How It Works -->

<section class="bg-light py-5">

    <div class="container">

        <h2 class="text-center mb-5">
            How It Works
        </h2>

        <div class="row text-center">

            <div class="col-md-3">
                <h4>1</h4>
                <p>Register</p>
            </div>

            <div class="col-md-3">
                <h4>2</h4>
                <p>Upload Resume</p>
            </div>

            <div class="col-md-3">
                <h4>3</h4>
                <p>Apply Jobs</p>
            </div>

            <div class="col-md-3">
                <h4>4</h4>
                <p>Get Hired</p>
            </div>

        </div>

    </div>

</section>

<!-- CTA -->

<section class="py-5">

    <div class="container text-center">

        <h2>
            Are You Hiring?
        </h2>

        <p>
            Post jobs and hire the best talent.
        </p>

        <a href="{{ route('register') }}"
           class="btn btn-success btn-lg">
            Post a Job
        </a>

    </div>

</section>

@endsection
