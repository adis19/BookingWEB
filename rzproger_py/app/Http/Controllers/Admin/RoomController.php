<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    // Room Types Management
    public function indexRoomTypes()
    {
        $roomTypes = RoomType::all();
        return view('admin.room_types.index', compact('roomTypes'));
    }

    public function createRoomType()
    {
        return view('admin.room_types.create');
    }

    public function storeRoomType(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price_per_night' => 'required|numeric|min:0',
            'max_occupancy' => 'required|integer|min:1',
            'image' => 'nullable|image|max:2048',
            'amenities' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('room-types', 'public');
            $validated['image'] = '/storage/' . $path;
        }

        // Convert amenities string to array
        if (isset($validated['amenities'])) {
            $validated['amenities'] = explode(',', $validated['amenities']);
        }

        RoomType::create($validated);

        return redirect()->route('admin.room-types.index')->with('success', 'Тип номера успешно создан');
    }

    public function editRoomType(RoomType $roomType)
    {
        return view('admin.room_types.edit', compact('roomType'));
    }

    public function updateRoomType(Request $request, RoomType $roomType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price_per_night' => 'required|numeric|min:0',
            'max_occupancy' => 'required|integer|min:1',
            'image' => 'nullable|image|max:2048',
            'amenities' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($roomType->image && file_exists(public_path($roomType->image))) {
                @unlink(public_path($roomType->image));
            }

            $path = $request->file('image')->store('room-types', 'public');
            $validated['image'] = '/storage/' . $path;
        }

        // Convert amenities string to array
        if (isset($validated['amenities'])) {
            $validated['amenities'] = explode(',', $validated['amenities']);
        }

        $roomType->update($validated);

        return redirect()->route('admin.room-types.index')->with('success', 'Тип номера успешно обновлен');
    }

    public function destroyRoomType(RoomType $roomType)
    {
        // Check if rooms of this type exist
        if ($roomType->rooms()->count() > 0) {
            return redirect()->route('admin.room-types.index')->with('error', 'Невозможно удалить тип номера с связанными номерами');
        }

        // Delete the image if exists
        if ($roomType->image && file_exists(public_path($roomType->image))) {
            @unlink(public_path($roomType->image));
        }

        $roomType->delete();

        return redirect()->route('admin.room-types.index')->with('success', 'Тип номера успешно удален');
    }

    // Individual Rooms Management
    public function indexRooms()
    {
        $rooms = Room::with('roomType')->get();
        return view('admin.rooms.index', compact('rooms'));
    }

    public function createRoom()
    {
        $roomTypes = RoomType::all();
        return view('admin.rooms.create', compact('roomTypes'));
    }

    public function storeRoom(Request $request)
    {
        $validated = $request->validate([
            'room_number' => 'required|string|max:255|unique:rooms',
            'room_type_id' => 'required|exists:room_types,id',
            'is_available' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        Room::create($validated);

        return redirect()->route('admin.rooms.index')->with('success', 'Номер успешно создан');
    }

    public function editRoom(Room $room)
    {
        $roomTypes = RoomType::all();
        return view('admin.rooms.edit', compact('room', 'roomTypes'));
    }

    public function updateRoom(Request $request, Room $room)
    {
        $validated = $request->validate([
            'room_number' => 'required|string|max:255|unique:rooms,room_number,' . $room->id,
            'room_type_id' => 'required|exists:room_types,id',
            'is_available' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $room->update($validated);

        return redirect()->route('admin.rooms.index')->with('success', 'Номер успешно обновлен');
    }

    public function destroyRoom(Room $room)
    {
        // Check if room has bookings
        if ($room->bookings()->count() > 0) {
            return redirect()->route('admin.rooms.index')->with('error', 'Невозможно удалить номер с связанными бронированиями');
        }

        $room->delete();

        return redirect()->route('admin.rooms.index')->with('success', 'Номер успешно удален');
    }
}
