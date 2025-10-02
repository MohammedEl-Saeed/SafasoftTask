<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>⚽ Stadium Booking</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center py-10">

    <div class="w-full max-w-3xl bg-white shadow-lg rounded-2xl p-8">
        <h2 class="text-2xl font-bold text-center text-blue-600 mb-6">⚽ Stadium Booking System</h2>

        <!-- Form Controls -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div>
                <label class="block text-gray-700 mb-2">Pitch</label>
                <select id="pitch_id" class="w-full border rounded-lg p-2">
                    @foreach(\App\Models\Pitch::all() as $pitch)
                        <option value="{{ $pitch->id }}">
                            {{ $pitch->name }} ({{ $pitch->stadium->name }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-gray-700 mb-2">Date</label>
                <input type="date" id="date" class="w-full border rounded-lg p-2">
            </div>

            <div>
                <label class="block text-gray-700 mb-2">Duration</label>
                <select id="duration" class="w-full border rounded-lg p-2">
                    <option value="60">60 minutes</option>
                    <option value="90">90 minutes</option>
                </select>
            </div>
        </div>

        <div class="text-center mb-6">
            <button onclick="getSlots()" 
                class="bg-blue-600 text-white px-6 py-2 rounded-lg shadow hover:bg-blue-700 transition">
                🔍 Find Available Slots
            </button>
        </div>

        <!-- Messages -->
        <div id="messages" class="mb-4"></div>

        <!-- Slots -->
        <div id="slots" class="grid grid-cols-1 md:grid-cols-2 gap-4"></div>
    </div>

    <script>
        async function getSlots() {
            let pitch_id = document.getElementById('pitch_id').value;
            let date = document.getElementById('date').value;
            let duration = document.getElementById('duration').value;

            document.getElementById('messages').innerHTML = "";
            document.getElementById('slots').innerHTML = "";

            try {
                let res = await fetch(`/api/bookings/slots?pitch_id=${pitch_id}&date=${date}&duration=${duration}`);
                let data = await res.json();

                if (!res.ok || !data.slots) {
                    document.getElementById('messages').innerHTML = 
                        `<div class="text-red-600 font-semibold">⚠️ ${data.error || "Failed to fetch slots"}</div>`;
                    return;
                }

                if (data.slots.length === 0) {
                    document.getElementById('messages').innerHTML = 
                        `<div class="text-yellow-600 font-semibold">😢 No slots available</div>`;
                } else {
                    data.slots.forEach(slot => {
                        let div = document.createElement('div');
                        div.className = "p-4 bg-gray-50 rounded-lg border flex justify-between items-center";
                        div.innerHTML = `
                            <div class="font-semibold text-gray-700">
                                ⏰ ${slot.start_time} - ${slot.end_time}
                            </div>
                            <button 
                                class="bg-green-500 text-white px-4 py-1 rounded-lg hover:bg-green-600 transition"
                                onclick="bookSlot('${pitch_id}','${date}','${slot.start_time}','${slot.end_time}')">
                                Book
                            </button>
                        `;
                        document.getElementById('slots').appendChild(div);
                    });
                }
            } catch (err) {
                document.getElementById('messages').innerHTML = 
                    `<div class="text-red-600 font-semibold">❌ Error fetching slots</div>`;
            }
        }

        async function bookSlot(pitch_id, date, start_time, end_time) {
            document.getElementById('messages').innerHTML = "";
            let res = await fetch('/api/bookings', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ pitch_id, date, start_time, end_time })
            });

            let data = await res.json();
            if (res.ok) {
                document.getElementById('messages').innerHTML = 
                    `<div class="text-green-600 font-semibold">✅ ${data.message}</div>`;
                getSlots();
            } else {
                document.getElementById('messages').innerHTML = 
                    `<div class="text-red-600 font-semibold">⚠️ ${data.error || "Booking failed"}</div>`;
            }
        }
    </script>
</body>
</html>
