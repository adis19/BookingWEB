@extends('layouts.admin')

@section('title', 'Edit Extra Service')

@section('actions')
<a href="{{ route('admin.extra-services.index') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left"></i> Back to Extra Services
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.extra-services.update', $extraService) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $extraService->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" required>{{ old('description', $extraService->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="price" class="form-label">Price ($)</label>
                <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $extraService->price) }}" required>
                @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Update Extra Service</button>
            </div>
        </form>
    </div>
</div>

@if($extraService->bookings()->count() > 0)
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">Usage in Bookings</h5>
    </div>
    <div class="card-body">
        <p>This extra service is currently being used in {{ $extraService->bookings()->count() }} bookings. Changes to the price will not affect existing bookings.</p>
    </div>
</div>
@endif
@endsection
