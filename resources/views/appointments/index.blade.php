<!Doctype html>
<html>

<head>
    <title>Appointments</title>
</head>

<body>

    <h1>Appointments</h1>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @if ($errors->any())
        <div>
            <strong>There are some errors:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $isEdit = isset($appointment);
    @endphp

    @php
        $user = Auth::user();
    @endphp

    <h2>{{ $isEdit ? 'Edit Appointment' : 'Add New Appointment' }}</h2>

    <form action="{{ $isEdit ? route('appointments.update', $appointment->id) : route('appointments.store') }}"
        method="POST">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <p>Selecciona un cliente y verás solo sus mascotas en el siguiente campo.</p>

        <label for="date">Date:</label>
        <input type="date" name="date" id="date" value="{{ old('date', $isEdit ? $appointment->date : '') }}"
            required><br>

        <label for="time">Time:</label>
        <select name="time" id="time" required>
            <option value="">Choose a time</option>
            @for ($hour = 9; $hour < 21; $hour++)
                @for ($minute = 0; $minute < 60; $minute += 30)
                    @php
                        $value = sprintf('%02d:%02d', $hour, $minute);
                    @endphp
                    <option value="{{ $value }}"
                        {{ old('time', $isEdit ? $appointment->time : '') == $value ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endfor
            @endfor
        </select><br>

        <label for="status">Status:</label>
        <select name="status" id="status" required>
            <option value="">Choose a status</option>
            <option value="pending"
                {{ old('status', isset($appointment) ? $appointment->status : '') === 'pending' ? 'selected' : '' }}>
                Pending</option>
            <option value="completed"
                {{ old('status', isset($appointment) ? $appointment->status : '') === 'completed' ? 'selected' : '' }}>
                Completed</option>
            <option value="canceled"
                {{ old('status', isset($appointment) ? $appointment->status : '') === 'canceled' ? 'selected' : '' }}>
                Canceled</option>
        </select><br>

        <label for="client_id">Client:</label>
        <select name="client_id" id="client_id" required>
            <option value="">Choose a client</option>
            @foreach ($clients as $client)
                <option value="{{ $client->id }}"
                    {{ old('client_id', isset($appointment) ? $appointment->client_id : '') == $client->id ? 'selected' : '' }}>
                    {{ $client->name }} {{ $client->last_name }}
                </option>
            @endforeach
        </select><br>

        <label for="pet_id">Pet:</label>
        <select name="pet_id" id="pet_id" required>
            <option value="">Choose a pet</option>
            @foreach ($pets as $pet)
                <option value="{{ $pet->id }}"
                    {{ old('pet_id', isset($appointment) ? $appointment->pet_id : '') == $pet->id ? 'selected' : '' }}>
                    {{ $pet->name }}
                </option>
            @endforeach
        </select><br>

        <label for="service_ids">Service:</label>
        @foreach ($services as $service)
            <input type="checkbox" name="service_ids[]" id="service_ids" value="{{ $service->id }}"
                {{ in_array($service->id, old('service_ids', isset($appointment) ? $appointment->services->pluck('id')->toArray() : [])) ? 'checked' : '' }}>
            {{ $service->name }} - {{ $service->price }}€
            </input>
        @endforeach
        </checkbox><br>

        <label for="user_id">User:</label>
        <select name="user_id" id="user_id" required>
            <option value="">Choose a user</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}"
                    {{ old('user_id', isset($appointment) ? $appointment->user_id : '') == $user->id ? 'selected' : '' }}>
                    {{ $user->name }}
                </option>
            @endforeach
        </select><br>

        <button type="submit">{{ $isEdit ? 'Save Changes' : 'Add Appointment' }}</button>
    </form>

    @if ($isEdit)
        <p><a href="{{ route('appointments.index') }}">Cancel edit</a></p>
    @endif

    <h2>Search Appointments</h2>
    <form action="{{ route('appointments.index') }}" method="GET" style="margin-bottom: 20px;">
        <label for="user_id_filter">User:</label>
        <select name="user_id" id="user_id_filter">
            <option value="">All users</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                    {{ $user->name }}
                </option>
            @endforeach
        </select>

        <label for="search_date">Date:</label>
        <input type="date" name="search_date" id="search_date" value="{{ request('search_date') }}">

        <button type="submit">Search</button>
        <a href="{{ route('appointments.index') }}"><button type="button">Clear</button></a>
    </form>

    <h2>Appointments List</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Client</th>
                <th>Pet</th>
                <th>Service</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>User</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($appointments as $appointmentItem)
                <tr>
                    <td>{{ $appointmentItem->client->name }} {{ $appointmentItem->client->last_name }}</td>
                    <td>{{ $appointmentItem->pet->name }}</td>
                    <td>{{ $appointmentItem->services->pluck('name')->join(', ') }}</td>
                    <td>{{ $appointmentItem->date }}</td>
                    <td>{{ $appointmentItem->time }}</td>
                    <td>
                        @can('changeStatus', $appointmentItem)
                            <form action="{{ route('appointments.changeStatus', $appointmentItem->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()">
                                    <option value="pending" {{ $appointmentItem->status === 'pending' ? 'selected' : '' }}>
                                        Pending</option>
                                    <option value="completed"
                                        {{ $appointmentItem->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="canceled"
                                        {{ $appointmentItem->status === 'canceled' ? 'selected' : '' }}>Canceled</option>
                                </select>
                            </form>
                        @else
                            {{ $appointmentItem->status }}
                        @endcan
                    </td>
                    <td>{{ $appointmentItem->user->name }}</td>
                    <td>
                        @can('update', $appointmentItem)
                            <a href="{{ route('appointments.edit', $appointmentItem->id) }}">
                                <button type="button">Edit</button>
                            </a>
                        @endcan
                        <form action="{{ route('appointments.destroy', $appointmentItem->id) }}" method="POST"
                            style="display:inline;">

                            @csrf
                            @method('DELETE')
                            @can('delete', $appointmentItem)
                                <button type="submit">
                                    Delete
                                </button>
                            @endcan

                        </form>

                    </td>
                </tr>
            @endforeach

        </tbody>

        <script>
            const clientPets = @json($clientPets);
            const clientSelect = document.getElementById('client_id');
            const petSelect = document.getElementById('pet_id');

            function renderPets(clientId) {
                const pets = clientPets[clientId] || [];
                petSelect.innerHTML = '';

                const placeholder = document.createElement('option');
                placeholder.value = '';
                placeholder.textContent = pets.length ? 'Choose a pet' : 'No pets for this client';
                petSelect.appendChild(placeholder);

                if (pets.length === 0) {
                    petSelect.disabled = true;
                    return;
                }

                const selectedPetId = '{{ isset($appointment) ? $appointment->pet_id : '' }}';

                pets.forEach(function(pet) {
                    const option = document.createElement('option');
                    option.value = pet.id;
                    option.textContent = pet.name;

                    if (String(pet.id) === String(selectedPetId)) {
                        option.selected = true;
                    }

                    petSelect.appendChild(option);
                });

                petSelect.disabled = false;
            }

            if (clientSelect && petSelect) {
                clientSelect.addEventListener('change', function() {
                    renderPets(this.value);
                });

                renderPets(clientSelect.value || '');
            }
        </script>

</body>

</html>
