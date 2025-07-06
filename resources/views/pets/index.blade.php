@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Pets List</span>
                    <a href="#" class="btn btn-primary btn-sm" id="addPetBtn">Add Pet</a>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <label for="statusFilter" class="form-label">Filter by Status:</label>
                        <select id="statusFilter" class="form-select" multiple>
                            <option value="available">Available</option>
                            <option value="pending">Pending</option>
                            <option value="sold">Sold</option>
                        </select>
                        <small class="form-text text-muted">Select multiple statuses or none to show all</small>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Category</th>
                                <th>Tags</th>
                                <th>Photo URLs</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="petsTableBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addImageModal" tabindex="-1" aria-labelledby="addImageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addImageModalLabel">Add Image URL</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addImageForm">
                    <input type="hidden" id="petIdForImage" value="">
                    <div class="mb-3">
                        <label for="imageUrl" class="form-label">Image URL</label>
                        <input type="text" class="form-control" id="imageUrl" placeholder="Enter image URL" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="submitImageUrl">Add</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetchPets();

        // Add event listener for status filter changes
        document.getElementById('statusFilter').addEventListener('change', function() {
            fetchPets();
        });

        document.getElementById('submitImageUrl').addEventListener('click', function() {
            const petId = document.getElementById('petIdForImage').value;
            const imageUrl = document.getElementById('imageUrl').value.trim();

            if (!imageUrl) {
                alert('Please enter an image URL');
                return;
            }

            uploadPetImage(petId, imageUrl);
        });

        document.getElementById('addPetBtn').addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = '/pets/details/new';
        });

        function fetchPets() {
            // Get selected statuses from the multi-select box
            const statusSelect = document.getElementById('statusFilter');
            const selectedOptions = Array.from(statusSelect.selectedOptions).map(option => option.value);

            // If no statuses are selected, use all available statuses
            const statuses = selectedOptions.length > 0 ? selectedOptions : ['available', 'pending', 'sold'];

            const url = new URL('/api/pet/find-by-status', window.location.origin);
            url.searchParams.set('statuses', statuses.join(','));

            fetch(url)
                .then(response => response.json())
                .then(data => {
                        renderPetsTable(data);
                })
                .catch(error => {
                    console.error('Error fetching pets:', error);
                });
        }

        function renderPetsTable(pets) {
            const tableBody = document.getElementById('petsTableBody');
            tableBody.innerHTML = '';

            if (pets.length === 0) {
                const row = document.createElement('tr');
                row.innerHTML = '<td colspan="6" class="text-center">No pets found</td>';
                tableBody.appendChild(row);
                return;
            }

            pets.forEach(pet => {
                const row = document.createElement('tr');

                row.innerHTML = `
                    <td>${pet.id || 'N/A'}</td>
                    <td>${pet.name || 'N/A'}</td>
                    <td>${pet.status || 'N/A'}</td>
                    <td>${pet.category ? pet.category.name : 'N/A'}</td>
                    <td>
                        ${pet.tags && pet.tags.length > 0
                            ? pet.tags.map(tag => `<div>${tag.name}</div>`).join('')
                            : 'No tags'}
                    </td>
                    <td>
                        ${pet.photo_urls && pet.photo_urls.length > 0
                            ? pet.photo_urls.map(url => `<div>${url}</div>`).join('')
                            : 'No photos'}
                    </td>
                    <td>
                        <button class="btn btn-sm btn-primary edit-btn" data-id="${pet.id}">Edit</button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="${pet.id}">Delete</button>
                        <button class="btn btn-sm btn-success add-image-btn" data-id="${pet.id}">Add Image</button>
                    </td>
                `;

                tableBody.appendChild(row);
            });

            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const petId = this.getAttribute('data-id');
                    window.location.href = `/pets/details/${petId}`;
                });
            });

            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const petId = this.getAttribute('data-id');
                    if (confirm(`Are you sure you want to delete pet with ID: ${petId}?`)) {
                        deletePet(petId);
                    }
                });
            });

            // Add event listeners for the "Add Image" buttons
            document.querySelectorAll('.add-image-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const petId = this.getAttribute('data-id');
                    document.getElementById('petIdForImage').value = petId;
                    document.getElementById('imageUrl').value = '';

                    // Show the modal
                    const modal = new bootstrap.Modal(document.getElementById('addImageModal'));
                    modal.show();
                });
            });
        }

        function deletePet(petId) {
            fetch(`/api/pet/${petId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                response.json();
                if (response.ok) {
                    alert('Pet deleted successfully');
                    fetchPets();
                } else {
                    alert(`Error: ${response.error}`);
                }
            })
            .catch(error => {
                console.error('Error deleting pet:', error);
                alert('An error occurred while deleting the pet');
            });
        }

        function uploadPetImage(petId, imageUrl) {
            fetch(`/api/pet/${petId}/upload-image`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ url: imageUrl })
            })
            .then(response => {
                response.json();
                if (response.ok) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addImageModal'));
                    modal.hide();
                    alert('Image URL added successfully');
                    fetchPets();
                } else {
                    alert(`Error: ${response.error}`);
                }
            })
            .catch(error => {
                console.error('Error uploading image URL:', error);
                alert('An error occurred while uploading the image URL');
            });
        }
    });
</script>
@endsection
