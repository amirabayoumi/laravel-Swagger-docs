<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col-md-8">
                <h2>Edit Event</h2>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('events') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('events.update', $event->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="title" class="form-label">Event Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $event->title) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="datetime-local" class="form-control" id="start_date" name="start_date"
                            value="{{ old('start_date', $event->start_date ? date('Y-m-d\TH:i', strtotime($event->start_date)) : '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="datetime-local" class="form-control" id="end_date" name="end_date"
                            value="{{ old('end_date', $event->end_date ? date('Y-m-d\TH:i', strtotime($event->end_date)) : '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" class="form-control" id="location" name="location" value="{{ old('location', $event->location) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="organizer" class="form-label">Organizer</label>
                        <input type="text" class="form-control" id="organizer" name="organizer" value="{{ old('organizer', $event->organizer) }}">
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $event->description) }}</textarea>
                    </div>

                    <!-- Category Selection -->
                    <div class="mb-3">
                        <label for="categories" class="form-label">Categories</label>
                        <div class="border rounded p-3">
                            @forelse($categories as $category)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $category->id }}"
                                    id="category-{{ $category->id }}"
                                    {{ in_array($category->id, old('categories', $event->categories->pluck('id')->toArray())) ? 'checked' : '' }}>
                                <label class="form-check-label" for="category-{{ $category->id }}">
                                    {{ $category->name }}
                                </label>
                            </div>
                            @empty
                            <p class="text-muted">No categories available</p>
                            @endforelse
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Event</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>