@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Add New Pet</div>

                <div class="card-body">
                    <form id="petForm">
                        <div class="mb-3">
                            <label for="name" class="form-label">Pet Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="">Select a status</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" id="category" name="category_id" required>
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="tags" class="form-label">Tags</label>
                            <select class="form-select" id="tags" name="tags[]" multiple>
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Hold Ctrl (or Cmd on Mac) to select multiple tags</small>
                        </div>

                        <div class="mb-3">
                            <label for="photo_url" class="form-label">Photo URLs</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="photo_url" placeholder="Enter photo URL">
                                <button class="btn btn-outline-secondary" type="button" id="add_photo_url">Add</button>
                            </div>
                            <div id="photo_urls_list" class="list-group mt-2">
                            </div>
                            <input type="hidden" id="photo_urls" name="photo_urls" value="">
                        </div>

                        <button type="submit" class="btn btn-primary">Save Pet</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('petForm');
        const photoUrlInput = document.getElementById('photo_url');
        const addPhotoUrlButton = document.getElementById('add_photo_url');
        const photoUrlsList = document.getElementById('photo_urls_list');
        const photoUrlsInput = document.getElementById('photo_urls');

        let photoUrls = [];

        function updatePhotoUrlsInput() {
            photoUrlsInput.value = photoUrls.join(',');
        }

        function addPhotoUrl() {
            const url = photoUrlInput.value.trim();
            if (url) {
                photoUrls.push(url);

                const listItem = document.createElement('div');
                listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
                listItem.innerHTML = `
                    <span>${url}</span>
                    <button type="button" class="btn btn-sm btn-danger remove-url">Remove</button>
                `;

                listItem.querySelector('.remove-url').addEventListener('click', function() {
                    const index = photoUrls.indexOf(url);
                    if (index !== -1) {
                        photoUrls.splice(index, 1);
                        listItem.remove();
                        updatePhotoUrlsInput();
                    }
                });

                photoUrlsList.appendChild(listItem);

                updatePhotoUrlsInput();

                photoUrlInput.value = '';
            }
        }

        addPhotoUrlButton.addEventListener('click', addPhotoUrl);

        photoUrlInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addPhotoUrl();
            }
        });

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const name = document.getElementById('name').value;
            const status = document.getElementById('status').value;
            const categorySelect = document.getElementById('category');
            if (!categorySelect.value) {
                alert('Please select a category');
                return;
            }
            const categoryId = categorySelect.value;
            const categoryName = categorySelect.selectedIndex > 0 ? categorySelect.options[categorySelect.selectedIndex].text : '';

            const tagsSelect = document.getElementById('tags');
            const selectedTags = [];
            for (let i = 0; i < tagsSelect.options.length; i++) {
                if (tagsSelect.options[i].selected) {
                    selectedTags.push({
                        id: parseInt(tagsSelect.options[i].value),
                        name: tagsSelect.options[i].text
                    });
                }
            }

            const parsedCategoryId = categoryId ? parseInt(categoryId) : null;

            const payload = {
                name: name,
                status: status,
                category: {
                    id: parsedCategoryId,
                    name: categoryName
                },
                photo_urls: photoUrlsInput.value,
                tags: selectedTags
            };

            fetch('/api/pet/', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(data => {
                if (data.id) {
                    alert('Pet added successfully!');
                    window.location.href = '/pets';
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the pet');
            });
        });
    });
</script>
@endsection
