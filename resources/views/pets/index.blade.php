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
            <input type="text" name="name" id="name" value="{{ old('name') }}" required><br>

            <label for="breed">Dog Breed:</label>
            <input type="text" name="breed" id="breed" value="{{ old('breed') }}" required><br>

            <label for="age">Age:</label>
            <input type="number" name="age" id="age" value="{{ old('age') }}" required><br>

            <label for="weight">Weight:</label>
            <input type="number" name="weight" id="weight" value="{{ old('weight') }}" required><br>

            <label for="gender">Gender:</label>
            <select name="gender" id="gender" required>
                <option value="Male" {{ old('gender', $isEdit && $pet->gender === 'Male' ? 'selected' : '') }}>Male</option>
                <option value="Female" {{ old('gender', $isEdit && $pet->gender === 'Female' ? 'selected' : '') }}>Female</option>
            </select><br>

            <label for="allergic">Allergic:</label>
            <select name="allergic" id="allergic" required>
                <option value="0" {{ old('allergic', $isEdit && $pet->allergic == 0 ? 'selected' : '') }}>No</option>
                <option value="1" {{ old('allergic', $isEdit && $pet->allergic == 1 ? 'selected' : '') }}>Yes</option>
            </select><br>

            <label for="observations">Observations:</label>
            <textarea name="observations" id="observations">{{ old('observations') }}</textarea><br>

            <label for="client_id">Client:</label>
            <select name="client_id" id="client_id" required>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ old('client_id', $isEdit ? $pet->client_id : '') == $client->id ? 'selected' : '' }}>
                        {{ $client->name }} {{ $client->last_name }} ({{ $client->email }})
                    </option>
                @endforeach
            </select><br>
            <button type="submit">Add Pet</button>
        </form>

        @if ($isEdit)
            <p><a href="{{ route('pets.index') }}">Cancel edit</a></p>
        @endif

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

                </tr>
            </thead>

            <tbody>

            @foreach($pets as $pet)

                <tr>
                    <td>{{ $pet->name }}</td>
                    <td>{{ $pet->breed }}</td>
                    <td>{{ $pet->age }}</td>
                    <td>{{ $pet->weight }}</td>
                    <td>{{ $pet->gender }}</td>
                    <td>{{ $pet->allergic ? 'Yes' : 'No' }}</td>
                    <td>{{ $pet->photo }}</td>
                    <td>{{ $pet->observations }}</td>
                    <td>{{ $pet->client ? $pet->client->name.' '.$pet->client->last_name : '-' }}</td>

                    <td>
                        <a href="{{ route('pets.edit', $pet->id) }}">
                            <button type="button">Edit</button>
                        </a>
                    
                        <form action="{{ route('pets.destroy', $pet->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>

            @endforeach

            </tbody>

        </table>

    </body>
</html>