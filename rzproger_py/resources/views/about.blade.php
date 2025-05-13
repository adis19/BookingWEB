@extends('layouts.app')

@section('title', 'About Us')

@section('hero')
<div class="hero-section">
    <div class="container text-center">
        <h1 class="display-4">About LuxuryStay</h1>
        <p class="lead">Your premium accommodation provider since 2010</p>
    </div>
</div>
@endsection

@section('content')
<div class="row mb-5">
    <div class="col-md-6">
        <h2>Our Story</h2>
        <p>LuxuryStay was founded in 2010 with a simple yet ambitious goal: to provide guests with an unparalleled luxury accommodation experience at reasonable prices. What began as a small boutique hotel has since grown into a renowned name in the hospitality industry.</p>
        <p>Our journey has been defined by a commitment to excellence, attention to detail, and a genuine passion for hospitality. Over the years, we've refined our services and expanded our offerings, but our core values remain unchanged.</p>
        <p>Today, LuxuryStay stands as a testament to our dedication to creating memorable stays for our guests, combining modern amenities with timeless elegance.</p>
    </div>
    <div class="col-md-6">
        <img src="https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" class="img-fluid rounded" alt="Hotel image">
    </div>
</div>

<div class="row mb-5">
    <div class="col-md-12 text-center">
        <h2>Our Values</h2>
        <p class="lead mb-5">These core principles guide everything we do at LuxuryStay</p>
    </div>
    
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-star text-primary mb-3" style="font-size: 3rem;"></i>
                <h4 class="card-title">Excellence</h4>
                <p class="card-text">We strive for excellence in every aspect of our service, from room cleanliness to customer interactions. Our high standards ensure that guests receive nothing but the best.</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-heart text-primary mb-3" style="font-size: 3rem;"></i>
                <h4 class="card-title">Hospitality</h4>
                <p class="card-text">True hospitality means creating a warm, welcoming environment where guests feel valued and cared for. Our staff is dedicated to making your stay comfortable and memorable.</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-thumbs-up text-primary mb-3" style="font-size: 3rem;"></i>
                <h4 class="card-title">Integrity</h4>
                <p class="card-text">We believe in honest, transparent business practices. Our pricing is clear, our promises are kept, and we always put our guests' needs first.</p>
            </div>
        </div>
    </div>
</div>

<div class="row mb-5">
    <div class="col-md-12 text-center mb-4">
        <h2>Meet Our Team</h2>
        <p class="lead">The dedicated professionals who make LuxuryStay special</p>
    </div>
    
    <div class="col-md-3">
        <div class="card text-center mb-4">
            <img src="https://randomuser.me/api/portraits/men/32.jpg" class="card-img-top" alt="Team member">
            <div class="card-body">
                <h5 class="card-title">John Doe</h5>
                <p class="card-subtitle text-muted mb-2">General Manager</p>
                <p class="card-text">With over 15 years in the hospitality industry, John ensures that every aspect of LuxuryStay operates smoothly.</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-center mb-4">
            <img src="https://randomuser.me/api/portraits/women/44.jpg" class="card-img-top" alt="Team member">
            <div class="card-body">
                <h5 class="card-title">Jane Smith</h5>
                <p class="card-subtitle text-muted mb-2">Customer Relations</p>
                <p class="card-text">Jane's friendly demeanor and attention to detail ensures that guest needs are always met with a smile.</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-center mb-4">
            <img src="https://randomuser.me/api/portraits/men/67.jpg" class="card-img-top" alt="Team member">
            <div class="card-body">
                <h5 class="card-title">Robert Johnson</h5>
                <p class="card-subtitle text-muted mb-2">Head Chef</p>
                <p class="card-text">Robert's culinary expertise brings exquisite flavors to our dining experience, delighting guests with every meal.</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-center mb-4">
            <img src="https://randomuser.me/api/portraits/women/28.jpg" class="card-img-top" alt="Team member">
            <div class="card-body">
                <h5 class="card-title">Emily Davis</h5>
                <p class="card-subtitle text-muted mb-2">Housekeeping Manager</p>
                <p class="card-text">Emily's eye for detail ensures that our rooms are immaculately maintained for the comfort of our guests.</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 text-center">
        <h2>Visit Us Today</h2>
        <p class="lead mb-4">Experience the luxury and comfort that our hotel has to offer</p>
        <a href="{{ route('rooms.index') }}" class="btn btn-primary btn-lg">Browse Our Rooms</a>
    </div>
</div>
@endsection
