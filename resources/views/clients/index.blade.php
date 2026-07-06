<!DOCTYPE html>
<html>
    <head>
        <title>Clients</title>
    </head>

    <body>
        <h1>Clients and Pets</h1>

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
            $isEdit = isset($client) && $client instanceof \App\Models\Client;
            $pet = $isEdit ? $client->pets()->first() : null;
            $petAllergicValue = old('pet_allergic', $pet?->allergic ?? 0);
        @endphp

        <h2>{{ $isEdit ? 'Edit Client and Pet' : 'Add Client and Pet' }}</h2>

        <form action="{{ $isEdit ? route('clients.update', $client->id) : route('clients.store') }}" method="POST">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            <h3>Client information</h3>
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="{{ old('name', $isEdit ? $client->name : '') }}" required><br>

            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $isEdit ? $client->last_name : '') }}" required><br>

            <label for="phone">Phone:</label>
            <input type="text" name="phone" id="phone" value="{{ old('phone', $isEdit ? $client->phone : '') }}" required><br>

            <label for="address">Address:</label>
            <input type="text" name="address" id="address" value="{{ old('address', $isEdit ? $client->address : '') }}" required><br>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="{{ old('email', $isEdit ? $client->email : '') }}" required><br>
 
            <h3>Pet information</h3>
            <label for="pet_name">Pet Name:</label>
            <input type="text" name="pet_name" id="pet_name" value="{{ old('pet_name', $pet?->name ?? '') }}" required><br>

            <label for="pet_breed">Pet Breed:</label>
            <input type="text" name="pet_breed" id="pet_breed" value="{{ old('pet_breed', $pet?->breed ?? '') }}" required><br>

            <label for="pet_age">Pet Age:</label>
            <input type="number" name="pet_age" id="pet_age" value="{{ old('pet_age', $pet?->age ?? '') }}" required><br>

            <label for="pet_weight">Pet Weight:</label>
            <input type="number" name="pet_weight" id="pet_weight" value="{{ old('pet_weight', $pet?->weight ?? '') }}" required><br>

            <label for="pet_gender">Pet Gender:</label>
            <select name="pet_gender" id="pet_gender" required>
                
                <option value="Male" {{ old('pet_gender', $pet?->gender ?? '') === 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ old('pet_gender', $pet?->gender ?? '') === 'Female' ? 'selected' : '' }}>Female</option>
            </select><br>

            <label for="pet_allergic">Pet Allergic:</label>
            <select name="pet_allergic" id="pet_allergic" required>
                <option value="0" {{ $petAllergicValue == 0 ? 'selected' : '' }}>No</option>
                <option value="1" {{ $petAllergicValue == 1 ? 'selected' : '' }}>Yes</option>
            </select><br>

            <label for="pet_observations">Pet Observations:</label>
            <textarea name="pet_observations" id="pet_observations">{{ old('pet_observations', $pet?->observations ?? '') }}</textarea><br>

            <button type="submit">{{ $isEdit ? 'Update' : 'Create' }} Client and Pet</button>
        </form>

        @if ($isEdit)
            <p><a href="{{ route('clients.index') }}">Cancel edit</a></p>
        @endif

        <h2>Clients List</h2>

        <table border="1">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Last Name</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Email</th>
                    <th>Pets</th>
                    <th>Pet breed</th>
                    <th>Pet age</th>
                    <th>Pet weight</th>
                    <th>Pet gender</th>
                    <th>Pet allergic</th>
                    <th>Pet observations</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clients as $clientItem)
                    <tr>
                        <td>{{ $clientItem->name }}</td>
                        <td>{{ $clientItem->last_name }}</td>
                        <td>{{ $clientItem->phone }}</td>
                        <td>{{ $clientItem->address }}</td>
                        <td>{{ $clientItem->email }}</td>
                        <td>
                            @foreach ($clientItem->pets as $petItem)
                                {{ $petItem->name }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </td>
                        <td>
                            @foreach ($clientItem->pets as $petItem)
                                {{ $petItem->breed }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </td>
                        <td>
                            @foreach ($clientItem->pets as $petItem)
                                {{ $petItem->age }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </td>
                        <td>
                            @foreach ($clientItem->pets as $petItem)
                                {{ $petItem->weight }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </td>
                        <td>
                            @foreach ($clientItem->pets as $petItem)
                                {{ $petItem->gender }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </td>
                        <td>
                            @foreach ($clientItem->pets as $petItem)
                                {{ $petItem->allergic ? 'Yes' : 'No' }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </td>
                        <td>
                            @foreach ($clientItem->pets as $petItem)
                                {{ $petItem->observations }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </td>
                        <td>
                            <a href="{{ route('clients.edit', $clientItem->id) }}">
                                <button type="button">Edit</button>
                            </a>

                            <form action="{{ route('clients.destroy', $clientItem->id) }}"
                                  method="POST"
                                  style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this client?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>