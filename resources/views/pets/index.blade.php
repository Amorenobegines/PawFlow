<!Doctype html>
<html>

<head>
    <title>Pets</title>
</head>

<body>

    <h1>Pets</h1>

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
        $isEdit = isset($pet);
    @endphp


    <h2>{{ $isEdit ? 'Edit Pet' : 'Add New Pet' }}</h2>

    <form action="{{ $isEdit ? route('pets.update', $pet->id) : route('pets.store') }}" method="POST">
        @csrf
        @if ($isEdit)
            @method('PATCH')
        @endif

        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="{{ old('name', $isEdit ? $pet->name : '') }}"
            required><br>

        <label for="breed">Dog Breed:</label>
        <input type="text" name="breed" id="breed" value="{{ old('breed', $isEdit ? $pet->breed : '') }}"
            required><br>

        <label for="age">Age:</label>
        <input type="number" name="age" id="age" value="{{ old('age', $isEdit ? $pet->age : '') }}"
            required><br>

        <label for="weight">Weight:</label>
        <input type="number" name="weight" id="weight" value="{{ old('weight', $isEdit ? $pet->weight : '') }}"
            required><br>

        <label for="gender">Gender:</label>
        <select name="gender" id="gender" required>
            <option value="Male" {{ old('gender', $isEdit && $pet->gender === 'Male' ? 'selected' : '') }}>Male
            </option>
            <option value="Female" {{ old('gender', $isEdit && $pet->gender === 'Female' ? 'selected' : '') }}>Female
            </option>
        </select><br>

        <label for="allergic">Allergic:</label>
        <select name="allergic" id="allergic" required>
            <option value="0" {{ old('allergic', $isEdit && $pet->allergic == 0 ? 'selected' : '') }}>No</option>
            <option value="1" {{ old('allergic', $isEdit && $pet->allergic == 1 ? 'selected' : '') }}>Yes</option>
        </select><br>

        <label for="observations">Observations:</label>
        <textarea name="observations" id="observations">{{ old('observations', $isEdit ? $pet->observations : '') }}</textarea><br>

        <label for="client_id">Client:</label>
        <select name="client_id" id="client_id" required>
            @foreach ($clients as $client)
                <option value="{{ $client->id }}"
                    {{ old('client_id', $isEdit ? $pet->client_id : '') == $client->id ? 'selected' : '' }}>
                    {{ $client->name }} {{ $client->last_name }} ({{ $client->email }})
                </option>
            @endforeach
        </select><br><br>
        @if ($isEdit)
            @can('update', $pet)
                <button type="submit">Update Pet</button>
            @endcan
        @else
            @can('create', App\Models\Pet::class)
                <button type="submit">Add Pet</button>
            @endcan
        @endif
    </form>

    @if ($isEdit)
        <p><a href="{{ route('pets.index') }}">Cancel edit</a></p>
    @endif

    <h2>Search Pets</h2>
    <form action="{{ route('pets.index') }}" method="GET" style="margin-bottom: 20px;">
        <label for="name_filter">Search by Name:</label>
        <input type="text" name="name_filter" id="name_filter" value="{{ request('name_filter') }}"
            placeholder="Escribe el nombre para filtrar">
    </form>

    <h2>Pet List</h2>

    <table border="1">
        <thead>
            <tr>
                <th>Name</th>
                <th>Breed</th>
                <th>Age</th>
                <th>Weight</th>
                <th>Gender</th>
                <th>Allergic</th>
                <th>Photo</th>
                <th>Observations</th>
                <th>Client</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody id="petsTableBody">

            @foreach ($pets as $pet)
                <tr data-pet-id="{{ $pet->id }}">
                    <td>{{ $pet->name }}</td>
                    <td>{{ $pet->breed }}</td>
                    <td>{{ $pet->age }}</td>
                    <td>{{ $pet->weight }}</td>
                    <td>{{ $pet->gender }}</td>
                    <td>{{ $pet->allergic ? 'Yes' : 'No' }}</td>
                    <td>{{ $pet->photo }}</td>
                    <td>{{ $pet->observations }}</td>
                    <td>{{ $pet->client ? $pet->client->name . ' ' . $pet->client->last_name : '-' }}</td>

                    <td>
                        @can('update', $pet)
                            <a href="{{ route('pets.edit', $pet->id) }}">
                                <button type="button">Edit</button>
                            </a>
                        @endcan

                        @can('delete', $pet)
                            <form action="{{ route('pets.destroy', $pet->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @endforeach

        </tbody>

    </table>

    <script>
        // JavaScript code to handle the search functionality
        document.getElementById('name_filter').addEventListener('input', function() {
            const filterValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('#petsTableBody tr');

            rows.forEach(row => {
                const nameCell = row.querySelector('td:first-child');
                if (nameCell) {
                    const nameText = nameCell.textContent.toLowerCase();
                    row.style.display = nameText.includes(filterValue) ? '' : 'none';
                }
            });
        });
    </script>

</body>

</html>
