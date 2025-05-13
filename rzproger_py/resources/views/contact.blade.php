@extends('layouts.app')

@section('title', 'Contact Us')

@section('hero')
<div class="hero-section">
    <div class="container text-center">
        <h1 class="display-4">Contact Us</h1>
        <p class="lead">We're here to help with any questions you may have</p>
    </div>
</div>
@endsection

@section('content')
<div class="row mb-5">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <h2>Get in Touch</h2>
                <p class="mb-4">Have questions about our rooms, services, or bookings? Fill out the form and we'll get back to you as soon as possible.</p>
                
                <form>
                    <div class="mb-3">
                        <label for="name" class="form-label">Your Name</label>
                        <input type="text" class="form-control" id="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="subject" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" rows="5" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <h2>Contact Information</h2>
                <p class="mb-4">You can also reach us through the following contact details:</p>
                
                <div class="d-flex mb-3">
                    <i class="fas fa-map-marker-alt text-primary me-3" style="font-size: 1.5rem;"></i>
                    <div>
                        <h5 class="mb-1">Address</h5>
                        <p>123 Luxury Avenue<br>City, Country, 12345</p>
                    </div>
                </div>
                
                <div class="d-flex mb-3">
                    <i class="fas fa-phone text-primary me-3" style="font-size: 1.5rem;"></i>
                    <div>
                        <h5 class="mb-1">Phone</h5>
                        <p>+1 234 567 8901<br>+1 234 567 8902</p>
                    </div>
                </div>
                
                <div class="d-flex mb-3">
                    <i class="fas fa-envelope text-primary me-3" style="font-size: 1.5rem;"></i>
                    <div>
                        <h5 class="mb-1">Email</h5>
                        <p>info@luxurystay.com<br>bookings@luxurystay.com</p>
                    </div>
                </div>
                
                <div class="d-flex mb-4">
                    <i class="fas fa-clock text-primary me-3" style="font-size: 1.5rem;"></i>
                    <div>
                        <h5 class="mb-1">Business Hours</h5>
                        <p>Monday - Friday: 9:00 AM - 6:00 PM<br>Saturday: 10:00 AM - 4:00 PM<br>Sunday: Closed</p>
                    </div>
                </div>
                
                <h5 class="mb-3">Follow Us</h5>
                <div class="social-icons">
                    <a href="#" class="me-3"><i class="fab fa-facebook fa-2x text-primary"></i></a>
                    <a href="#" class="me-3"><i class="fab fa-twitter fa-2x text-primary"></i></a>
                    <a href="#" class="me-3"><i class="fab fa-instagram fa-2x text-primary"></i></a>
                    <a href="#" class="me-3"><i class="fab fa-linkedin fa-2x text-primary"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body p-0">
                <div class="ratio ratio-16x9">
                    <!-- Replace with your actual Google Maps embed code -->
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12345.67890!2d-122.4194!3d37.7749!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzfCsDQ2JzMwLjkiTiAxMjLCsDI1JzEwLjAiVw!5e0!3m2!1sen!2sus!4v1234567890" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
