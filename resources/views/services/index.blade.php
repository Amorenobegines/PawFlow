<!DOCTYPE html>
<html>
    <head>
        <title>Services</title>
    </head>
    <body>

        <h1>Services</h1>

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
            $isEdit = isset($service);
        @endphp

        <h2>{{ $isEdit ? 'Edit Service' : 'Add New Service' }}</h2>

        <form action="{{ $isEdit ? route('services.update', $service->id) : route('services.store') }}" method="POST">
            @csrf
            @if ($isEdit)
                @method('PATCH')
            @endif

            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="{{ old('name', $isEdit ? $service->name : '') }}" required><br>

            <label for="type">Type:</label>
            <input type="text" name="type" id="type" value="{{ old('type', $isEdit ? $service->type : '') }}" required><br>

            <label for="price">Price:</label>
            <input type="number" name="price" id="price" step="0.01" value="{{ old('price', $isEdit ? $service->price : '') }}" required><br>
            
            <label for="duration">Duration:</label>
            <input type="number" name="duration" id="duration" value="{{ old('duration', $isEdit ? $service->duration : '') }}" required><br>

            <button type="submit">{{ $isEdit ? 'Save Changes' : 'Add Service' }}</button>
        </form>

        @if ($isEdit)
            <p><a href="{{ route('services.index') }}">Cancel edit</a></p>
        @endif

        <h2>Services List</h2>

        <table border="1">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Duration</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

                @foreach($services as $serviceItem)

                                <tr>
                                    <td>{{ $serviceItem->name }}</td>
                                    <td>{{ $serviceItem->type }}</td>
                                    <td>{{ $serviceItem->price }}</td>
                                    <td>{{ $serviceItem->duration }}</td>

                                    <td>

                                        <a href="{{ route('services.edit', $serviceItem->id) }}">
                                            <button type="button">Edit</button>
                                        </a>

                                        <form action="{{ route('services.destroy', $serviceItem->id) }}"
                                    method="POST"
                                    style="display:inline;">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit">
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