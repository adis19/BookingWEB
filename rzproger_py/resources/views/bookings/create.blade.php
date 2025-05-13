@extends('layouts.app')

@section('title', 'Book Room')

@section('content')
<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('rooms.index') }}">Rooms</a></li>
                <li class="breadcrumb-item active" aria-current="page">Book Room</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Complete Your Booking</h4>
            </div>
            <div class="card-body">
                <form id="booking-form" action="{{ route('bookings.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="room_id" value="{{ $availableRoom->id }}">
                    <input type="hidden" name="check_in_date" value="{{ $checkIn->format('Y-m-d') }}">
                    <input type="hidden" name="check_out_date" value="{{ $checkOut->format('Y-m-d') }}">
                    <input type="hidden" name="guests" value="{{ $guests }}">
                    
                    <div class="mb-4">
                        <h5>Booking Details</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <p><strong>Check-in:</strong> {{ $checkIn->format('M d, Y') }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Check-out:</strong> {{ $checkOut->format('M d, Y') }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Guests:</strong> {{ $guests }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Duration:</strong> {{ $duration }} night(s)</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h5>Extra Services</h5>
                        <p class="text-muted mb-3">Select any additional services you would like to add to your stay:</p>
                        
                        <div class="row">
                            @foreach($extraServices as $service)
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="card-title mb-0">{{ $service->name }}</h6>
                                                <span class="badge bg-primary">${{ $service->price }}</span>
                                            </div>
                                            <p class="card-text text-muted small">{{ $service->description }}</p>
                                            <div class="d-flex justify-content-end align-items-center">
                                                <label for="service_{{ $service->id }}" class="me-2">Quantity:</label>
                                                <select class="form-select form-select-sm extra-service" 
                                                        style="width: 80px;" 
                                                        id="service_{{ $service->id }}" 
                                                        name="extra_services[{{ $service->id }}]"
                                                        data-price="{{ $service->price }}">
                                                    <option value="0">0</option>
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h5>Special Requests</h5>
                        <textarea class="form-control" name="special_requests" rows="3" placeholder="Do you have any special requests or requirements? Let us know here."></textarea>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Confirm Booking</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4 sticky-top" style="top: 20px;">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Booking Summary</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6>{{ $roomType->name }}</h6>
                    <p class="text-muted small mb-0">Room #{{ $availableRoom->room_number }}</p>
                </div>
                
                <hr>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Room Rate:</span>
                        <span>${{ $roomType->price_per_night }} × {{ $duration }} night(s)</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Room Total:</span>
                        <span class="fw-bold">${{ $roomPrice }}</span>
                    </div>
                </div>
                
                <div id="extra-services-summary" class="mb-3 d-none">
                    <hr>
                    <h6>Extra Services:</h6>
                    <div id="selected-services"></div>
                    <div class="d-flex justify-content-between">
                        <span>Services Total:</span>
                        <span class="fw-bold" id="services-total">$0.00</span>
                    </div>
                </div>
                
                <hr>
                
                <div class="d-flex justify-content-between">
                    <span class="h5">Total:</span>
                    <span class="h5" id="total-price">${{ $roomPrice }}</span>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Need Help?</h5>
                <p class="card-text">If you have any questions or need assistance with your booking, please don't hesitate to contact us.</p>
                <div class="d-grid">
                    <a href="{{ route('contact') }}" class="btn btn-outline-primary">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const extraServices = document.querySelectorAll('.extra-service');
        const totalPriceEl = document.getElementById('total-price');
        const servicesTotalEl = document.getElementById('services-total');
        const extraServicesSummary = document.getElementById('extra-services-summary');
        const selectedServicesEl = document.getElementById('selected-services');
        const roomPrice = {{ $roomPrice }};
        
        // Update booking summary when extra services are selected
        extraServices.forEach(service => {
            service.addEventListener('change', updateBookingSummary);
        });
        
        function updateBookingSummary() {
            let servicesTotal = 0;
            let hasServices = false;
            let servicesHtml = '';
            
            extraServices.forEach(service => {
                const quantity = parseInt(service.value);
                if (quantity > 0) {
                    hasServices = true;
                    const price = parseFloat(service.getAttribute('data-price'));
                    const serviceTotal = price * quantity;
                    servicesTotal += serviceTotal;
                    
                    const serviceName = service.closest('.card').querySelector('.card-title').textContent;
                    servicesHtml += `
                        <div class="d-flex justify-content-between mb-2">
                            <span>${serviceName} × ${quantity}</span>
                            <span>$${serviceTotal.toFixed(2)}</span>
                        </div>
                    `;
                }
            });
            
            if (hasServices) {
                extraServicesSummary.classList.remove('d-none');
                selectedServicesEl.innerHTML = servicesHtml;
                servicesTotalEl.textContent = '$' + servicesTotal.toFixed(2);
            } else {
                extraServicesSummary.classList.add('d-none');
            }
            
            const totalPrice = roomPrice + servicesTotal;
            totalPriceEl.textContent = '$' + totalPrice.toFixed(2);
        }
    });
</script>
@endsection
