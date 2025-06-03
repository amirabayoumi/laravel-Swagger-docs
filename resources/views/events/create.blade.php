<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <div class="container mt-5">
        <h2>Create New Event</h2>

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('events.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="title" class="form-label">Event Title</label>
                <input type="text" class="form-control" name="title" value="{{ old('title') }}" required>
            </div>

            <div class="mb-3">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="datetime-local" class="form-control" name="start_date" value="{{ old('start_date') }}" required>
            </div>

            <div class="mb-3">
                <label for="end_date" class="form-label">End Date</label>
                <input type="datetime-local" class="form-control" name="end_date" value="{{ old('end_date') }}" required>
            </div>

            <!-- Location Autocomplete -->
            <div class="mb-3 position-relative">
                <label for="location" class="form-label">Location</label>
                <input type="text" id="location" name="location" class="form-control" autocomplete="off" value="{{ old('location') }}" required>
                <div id="location-results" class="list-group position-absolute w-100 z-index-3" style="max-height: 200px; overflow-y: auto;"></div>
            </div>

            <!-- Latitude and Longitude (auto-filled) -->
            <div class="row mb-3">
                <div class="col">
                    <label for="latitude" class="form-label">Latitude</label>
                    <input type="text" class="form-control" id="latitude" name="latitude" value="{{ old('latitude') }}" readonly>
                </div>
                <div class="col">
                    <label for="longitude" class="form-label">Longitude</label>
                    <input type="text" class="form-control" id="longitude" name="longitude" value="{{ old('longitude') }}" readonly>
                </div>
            </div>

            <!-- <button type="button" class="btn btn-outline-secondary mb-3" id="geocode-btn">Find Coordinates</button> -->

            <div class="mb-3">
                <label for="organizer" class="form-label">Organizer</label>
                <input type="text" class="form-control" name="organizer" value="{{ old('organizer') }}">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" name="description" rows="3">{{ old('description') }}</textarea>
            </div>

            <!-- Categories -->
            <div class="mb-3">
                <label class="form-label">Categories</label>
                <div class="border rounded p-3">
                    @forelse($categories as $category)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $category->id }}"
                            id="category-{{ $category->id }}" {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                        <label class="form-check-label" for="category-{{ $category->id }}">{{ $category->name }}</label>
                    </div>
                    @empty
                    <p>No categories available</p>
                    @endforelse
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Create Event</button>
                <a href="{{ route('events') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <!-- JavaScript for Autocomplete & Geocoding -->
    <script>
        $(document).ready(function() {
            let typingTimer;
            const doneTypingInterval = 300;

            $('#location').on('input', function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(fetchLocations, doneTypingInterval);
            });

            function fetchLocations() {
                const query = $('#location').val();
                if (query.length < 2) return;

                $.get('https://nominatim.openstreetmap.org/search', {
                    q: query + ', Belgium',
                    format: 'json',
                    limit: 5,
                    countrycodes: 'be',
                    addressdetails: 1
                }, function(data) {
                    const resultsContainer = $('#location-results');
                    resultsContainer.empty();

                    data.forEach(function(item) {
                        resultsContainer.append(`<a href="#" class="list-group-item list-group-item-action" data-lat="${item.lat}" data-lon="${item.lon}">${item.display_name}</a>`);
                    });

                    resultsContainer.show();
                });
            }

            $(document).on('click', '#location-results a', function(e) {
                e.preventDefault();
                const name = $(this).text();
                const lat = $(this).data('lat');
                const lon = $(this).data('lon');

                $('#location').val(name);
                $('#latitude').val(lat);
                $('#longitude').val(lon);
                $('#location-results').hide();
            });

            $(document).click(function(e) {
                if (!$(e.target).closest('#location').length) {
                    $('#location-results').hide();
                }
            });

            $('#geocode-btn').on('click', function() {
                const query = $('#location').val();
                if (!query) return alert('Please enter a location');

                $.get('https://nominatim.openstreetmap.org/search', {
                    q: query + ', Belgium',
                    format: 'json',
                    limit: 1,
                    countrycodes: 'be'
                }, function(data) {
                    if (data.length) {
                        $('#latitude').val(data[0].lat);
                        $('#longitude').val(data[0].lon);
                        alert('Coordinates found');
                    } else {
                        alert('Could not find coordinates. Try a more specific address.');
                    }
                });
            });
        });
    </script>
</body>

</html>