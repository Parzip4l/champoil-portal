@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/themes/default/style.min.css" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/dropify/css/dropify.min.css') }}" rel="stylesheet" />
@endpush

@php 
    $user_id = Auth::user()->id;
@endphp

@section('content')
<div class="row">
    
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                    DOCUMENT CONTROL
                    <i data-feather="folder-plus" class="icon-lg folder-icon" style="float:right; color: black; cursor: pointer;"></i>
            </div>  
            <div class="card-body">
                <div id="folderTreeView"></div>
            </div>
        </div>
        
    </div>
    <div class="col-md-9 grid-margin stretch-card">
        <div class="card">
            <div class="card-header" style="background: linear-gradient(to right, #1f4598, #fbaf44); color: white; padding: 10px;"
            >
                <h6 class="card-title" id="documentGalleryTitle">
                    Files Recents
                </h6>
            </div>
            <div class="card-body" style="background-color: #f9fafb; border:1px solid #1f4598">
                <div class="row mt-3">
                    <div class="col-md-12">
                        <select class="form-control select2" 
                                id="documentTags" 
                                name="tags[]" 
                                multiple="multiple" 
                                data-width="100%"
                                placeholder="Filter by tags">
                                   
                        </select>
                </div>
                <input type="hidden" id="selectedFolderId" value="">
                <div class="row mt-3" id="recentDocuments">
                    <!-- Recent documents will be dynamically loaded here -->
                </div>
                
            </div>
        </div>
    </div>
</div>

<!-- Modal for creating a folder -->
<div class="modal fade" id="createFolderModal" tabindex="-1" aria-labelledby="createFolderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createFolderModalLabel">Create New Folder</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createFolderForm">
                    <div class="mb-3">
                        <label for="folderName" class="form-label">Folder Name</label>
                        <input type="text" class="form-control" id="folderName" name="folderName" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Create</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for uploading a document -->
<div class="modal fade" id="uploadDocumentModal" tabindex="-1" aria-labelledby="uploadDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadDocumentModalLabel">Upload Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="uploadDocumentForm">
                    <div class="mb-3">
                        <label for="documentFile" class="form-label">Document File</label>
                        <input type="file" class="form-control dropify" id="documentFile" name="documentFile" required>
                    </div>
                    <div class="mb-3">
                        <label for="documentName" class="form-label">Document Name</label>
                        <input type="text" class="form-control" id="documentName" name="documentName" required>
                    </div>
                    <div class="mb-3">
                        <label for="useDueDate" class="form-label">Use Due Date?</label>
                        <select class="form-control" id="useDueDate" name="useDueDate" required>
                            <option value="no">No</option>
                            <option value="yes">Yes</option>
                        </select>
                    </div>
                    <div class="mb-3" id="dueDateFields" style="display: none;">
                        <label for="dueDate" class="form-label">Due Date</label>
                        <input type="date" class="form-control" id="dueDate" name="dueDate">
                        <label for="reminder" class="form-label">Reminder</label>
                        <input type="text" class="form-control" id="reminder" name="reminder" value="1">
                    </div>
                    <input type="hidden" id="uploadFolderId" name="folderId">
                    <input type="hidden" id="user_id" name="user_id" value="{{ $user_id }}">
                    <button type="submit" class="btn btn-primary">Upload</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('plugin-scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/jstree.min.js"></script>
    <script src="{{ asset('assets/plugins/dropify/js/dropify.min.js') }}"></script>
@endpush

@push('custom-scripts')
  <script>
    $(document).ready(function() {
        feather.replace();

        // Setup CSRF Token for Laravel
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        // Initialize Dropify
        $('.dropify').dropify();

        // Open modal for creating a new folder
        $('#newFolderOption').on('click', function(e) {
            e.preventDefault();
            $('#createFolderModalLabel').text('Create New Folder'); // Set modal title
            $('#createFolderModal').data('parent-id', null); // Clear parent ID
            $('#createFolderModal').modal('show');
        });

        // Handle folder editing (placeholder for now)
        $('#editFolderOption').on('click', function(e) {
            e.preventDefault();
            Swal.fire('Edit Folder', 'Edit folder functionality is not implemented yet.', 'info');
        });

        // Handle folder creation form submission
        $('#createFolderForm').on('submit', function(e) {
            e.preventDefault();
            const folderName = $('#folderName').val();
            const parentId = $('#createFolderModal').data('parent-id') || null; // Get parent ID if creating a subfolder
            if (folderName) {
                // Show SweetAlert2 loading indicator
                Swal.fire({
                    title: 'Creating folder...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.post('/api/v1/documents/root-folder', {
                    name: folderName,
                    user_id: {{ $user_id }},
                    parent_id: parentId
                }).done(function(response) {
                    Swal.close(); // Close the loading indicator
                    Swal.fire('Success', 'Folder created successfully!', 'success');
                    $('#createFolderModal').modal('hide');

                    // Clear form values
                    $('#createFolderForm')[0].reset();

                    // Append the new folder to jsTree dynamically
                    const newNode = {
                        id: response.id,
                        parent: response.parent_id ? response.parent_id : '#',
                        text: response.name + '&nbsp;' +
                            '<div class="dropdown" style="float:right">' +
                                '<a href="#" class="createFolderBtn" data-folder-id="' + response.id + '" data-folder-name="' + response.name + '" data-bs-toggle="dropdown" aria-expanded="false">' +
                                    '<i data-feather="settings" class="icon-sm" style="color: black;"></i>' +
                                '</a>' +
                                '<ul class="dropdown-menu" aria-labelledby="createFolderBtn">' +
                                    '<li><a class="dropdown-item newFolderOption" href="#" data-folder-id="' + response.id + '">New</a></li>' +
                                    '<li><a class="dropdown-item editFolderOption" href="#" data-folder-id="' + response.id + '" data-folder-name="' + response.name + '">Edit</a></li>' +
                                '</ul>' +
                            '</div>',
                        li_attr: { class: 'folder-item' }
                    };
                    $('#folderTreeView').jstree().create_node(newNode.parent, newNode, "last");

                    // Re-render Feather icons
                    feather.replace();
                }).fail(function() {
                    Swal.close(); // Close the loading indicator
                    Swal.fire('Error', 'Failed to create folder.', 'error');
                });
            }
        });

        // Fetch folder data
        $.ajax({
            url: '/api/v1/documents/folders/{{ $user_id }}',
            method: 'GET',
            beforeSend: function() {
                Swal.fire({
                    title: 'Loading folders...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function(response) {
                Swal.close(); // Close the loading indicator
                const folders = response.folders.map(folder => ({
                    id: folder.id,
                    parent: folder.parent_id ? folder.parent_id : '#',
                    text: folder.name + '&nbsp;' +
                        '<div class="dropdown" style="float:right">' +
                            '<a href="#" class="createFolderBtn" data-folder-id="' + folder.id + '" data-folder-name="' + folder.name + '" data-bs-toggle="dropdown" aria-expanded="false">' +
                                '<i data-feather="settings" class="icon-sm" style="color: black;"></i>' +
                            '</a>' +
                            '<ul class="dropdown-menu" aria-labelledby="createFolderBtn">' +
                                '<li><a class="dropdown-item newFolderOption" href="#" data-folder-id="' + folder.id + '">New</a></li>' +
                                '<li><a class="dropdown-item editFolderOption" href="#" data-folder-id="' + folder.id + '" data-folder-name="' + folder.name + '">Edit</a></li>' +
                                '</ul>' +
                            '</div>',
                    li_attr: { class: 'folder-item' }
                }));

                $('#folderTreeView').jstree({
                    'core': { 
                        'data': folders,
                        'check_callback': true // Ensure actions like creating nodes are allowed
                    },
                    'plugins': ['contextmenu'],
                    'contextmenu': {
                        'items': function(node) {
                            return {
                                create: {
                                    label: "New Folder",
                                    action: function() {
                                        $('#createFolderModalLabel').text('Create Subfolder');
                                        $('#createFolderModal').data('parent-id', node.id).modal('show');
                                    }
                                },
                                edit: {
                                    label: "Edit Folder",
                                    action: function() {
                                        $('#createFolderModalLabel').text('Edit Folder');
                                        $('#folderName').val(node.text.trim());
                                        $('#createFolderModal').data('folder-id', node.id).modal('show');
                                    }
                                },
                                delete: {
                                    label: "Delete Folder",
                                    action: function() {
                                        Swal.fire({
                                            title: 'Are you sure?',
                                            text: "This action cannot be undone!",
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonText: 'Yes, delete it!',
                                            cancelButtonText: 'Cancel'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                $.ajax({
                                                    url: '/api/v1/documents/folders/' + node.id,
                                                    method: 'DELETE',
                                                    success: function() {
                                                        $('#folderTreeView').jstree().delete_node(node);
                                                        Swal.fire('Deleted!', 'The folder has been deleted.', 'success');
                                                    },
                                                    error: function() {
                                                        Swal.fire('Error!', 'Failed to delete the folder.', 'error');
                                                    }
                                                });
                                            }
                                        });
                                    }
                                }
                            };
                        }
                    }
                }).on('loaded.jstree', function() {
                    feather.replace(); // Render ulang ikon Feather setelah jsTree selesai dimuat
                }).on('refresh.jstree', function() {
                    feather.replace(); // Render ulang ikon Feather setelah jsTree direfresh
                });

                // Handle "Create Subfolder" button click
                $('#folderTreeView').on('click', '.create-subfolder-btn', function(e) {
                    e.preventDefault();
                    const nodeId = $(this).closest('li').attr('id');
                    $('#createFolderModalLabel').text('Create Subfolder'); // Set modal title
                    $('#createFolderModal').data('parent-id', nodeId).modal('show'); // Set parent ID
                });
            },
            error: function(xhr, status, error) {
                Swal.close(); // Close the loading indicator
                console.error('Error fetching folder data:', error);
                alert('Failed to load folder data. Please try again later.');
            }
        });

        // Node selection event
        $('#folderTreeView').on("select_node.jstree", function (e, data) {
            console.log('Selected folder ID:', data.node.id);
            const folderName = $('<div>').html(data.node.text).text().trim().replace(/NewEdit/g, ''); // Remove "NewEdit"
            
            // Update the document gallery title
            $('#documentGalleryTitle').html(`
                ${folderName}
                <button class="btn btn-sm btn-primary" style="float: right; display: flex; align-items: center;" id="uploadFileButton">
                        <i data-feather="upload" class="icon-sm" style="margin-right: 5px;"></i> Upload File
                    </button>
            `);

            // Update the hidden folder ID
            $('#selectedFolderId').val(data.node.id);

            // Fetch tags for the selected folder
            $.ajax({
                url: `/api/v1/documents/tags-list/${data.node.id}`,
                method: 'GET',
                beforeSend: function() {
                    $('#documentTags').html('<option value="all">Loading...</option>'); // Show loading state
                },
                success: function(response) {
                    $('#documentTags').empty(); // Clear existing options
                    $('#documentTags').append('<option value="all">All Tags</option>'); // Add default option

                    // Iterate through the response and populate the dropdown
                    response.forEach(tag => {
                        $('#documentTags').append(`<option value="${tag.id}">${tag.name}</option>`);
                    });

                    // Destroy previous Select2 instance and reinitialize
                    if ($('#documentTags').data('select2')) {
                        $('#documentTags').select2('destroy');
                    }
                    $('#documentTags').select2({
                        placeholder: 'Select tags',
                        allowClear: true
                    });
                },
                error: function() {
                    $('#documentTags').html('<option value="all">Failed to load tags</option>'); // Show error state
                }
            });

            // Fetch files for the selected folder
            const recentDocumentsContainer = $('#recentDocuments');
            $.ajax({
                url: `/api/v1/documents/files/${data.node.id}`,
                method: 'GET',
                beforeSend: function() {
                    recentDocumentsContainer.html(`
                        <div class="d-flex justify-content-center align-items-center" style="height: 100px;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    `); // Show spinner
                },
                success: function(response) { 
                    recentDocumentsContainer.empty();
                    if (response.length > 0) {
                        response.forEach(document => {
                            const fileExtension = document.path.split('.').pop().toLowerCase();
                            let preview;

                            if (/\.(jpg|jpeg|png|gif|bmp|webp)$/i.test(document.path)) {
                                preview = `<img src="${document.full_url}" class="card-img-top" alt="Preview of ${document.name}">`;
                            } else if (['pdf', 'doc', 'docx', 'xls', 'xlsx'].includes(fileExtension)) {
                                preview = `<iframe src="${document.full_url}" class="card-img-top" style="width: 100%; height: 200px;" frameborder="0" sandbox></iframe>`;
                            } else {
                                preview = `<div class="card-img-top text-center" style="font-size: 50px; padding: 20px; color: #1f4598;">
                                               <i data-feather="file-text"></i>
                                           </div>`;
                            }

                            const documentCard = `
                                <div class="col-md-4 mb-4">
                                    <div class="card shadow-sm" style="border-radius: 10px; overflow: hidden; transition: transform 0.3s, box-shadow 0.3s;">
                                        <div style="height: 200px; overflow: hidden; background-color: #f4f4f4;">
                                            ${preview}
                                        </div>
                                        <div class="card-body" style="padding: 15px;">
                                            <h6 class="card-title" style="font-weight: bold; color: #1f4598;">${document.name}</h6>
                                            <p class="card-text" style="font-size: 14px; color: #555;">
                                                <strong>Uploaded By:</strong> ${document.uploader || 'Unknown'}<br>
                                            </p>
                                            <a href="${document.full_url}" class="btn btn-primary btn-sm mt-3" target="_blank" style="background: linear-gradient(to right, #1f4598, #fbaf44); border: none;">Download</a>
                                        </div>
                                    </div>
                                </div>
                            `;
                            recentDocumentsContainer.append(documentCard);
                        });
                        feather.replace(); // Re-render Feather icons
                    } else {
                        recentDocumentsContainer.html('<p>No files found in this folder.</p>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching files:', error);
                    recentDocumentsContainer.html('<p>Failed to load files. Please try again later.</p>');
                }
            });
        });

        $('#folderTreeView').on('click', '.newFolderOption', function(e) {
            e.preventDefault();
            const parentId = $(this).data('folder-id');
            $('#createFolderModalLabel').text('Create Subfolder');
            $('#createFolderModal').data('parent-id', parentId).modal('show');
        });

        $('#folderTreeView').on('click', '.editFolderOption', function(e) {
            e.preventDefault();
            const folderId = $(this).data('folder-id');
            const folderName = $(this).data('folder-name');
            $('#createFolderModalLabel').text('Edit Folder');
            $('#folderName').val(folderName);
            $('#createFolderModal').data('folder-id', folderId).modal('show');
        });

        // Open "Create Folder" modal when folder icon is clicked
        $('.folder-icon').on('click', function() {
            $('#createFolderModalLabel').text('Create New Folder'); // Set modal title
            $('#createFolderModal').data('parent-id', null); // Clear parent ID
            $('#createFolderModal').modal('show'); // Show modal
        });

        // Open "Upload Document" modal
        $(document).on('click', '#uploadFileButton', function() {
            const folderId = $('#selectedFolderId').val();
            if (!folderId) {
                Swal.fire('Error', 'Please select a folder first.', 'error');
                return;
            }
            $('#uploadFolderId').val(folderId); // Set folder ID in the form
            $('#uploadDocumentModal').modal('show');
        });

        // Handle document upload form submission
        $('#uploadDocumentForm').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            // Show SweetAlert2 loading indicator
            Swal.fire({
                title: 'Uploading document...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: '/api/v1/documents/uploads',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Add CSRF token
                },
                enctype: 'multipart/form-data', // Ensure multipart encoding
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    Swal.close(); // Close the loading indicator
                    Swal.fire('Success', 'Document uploaded successfully!', 'success');
                    $('#uploadDocumentModal').modal('hide');
                    $('#uploadDocumentForm')[0].reset(); // Clear form values
                    $('.dropify').dropify(); // Reinitialize Dropify to clear file input

                    // Dynamically add the uploaded file to the recentDocuments section
                    const fileExtension = response.path.split('.').pop().toLowerCase();
                    let preview;

                    if (/\.(jpg|jpeg|png|gif|bmp|webp)$/i.test(response.path)) {
                        preview = `<img src="${response.full_url}" class="card-img-top" alt="Preview of ${response.name}">`;
                    } else if (['pdf', 'doc', 'docx', 'xls', 'xlsx'].includes(fileExtension)) {
                        preview = `<iframe src="${response.full_url}" class="card-img-top" style="width: 100%; height: 200px;" frameborder="0" sandbox></iframe>`;
                    } else {
                        preview = `<div class="card-img-top text-center" style="font-size: 50px; padding: 20px; color: #1f4598;">
                                       <i data-feather="file-text"></i>
                                   </div>`;
                    }

                    const documentCard = `
                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm" style="border-radius: 10px; overflow: hidden; transition: transform 0.3s, box-shadow 0.3s;">
                                <div style="height: 200px; overflow: hidden; background-color: #f4f4f4;">
                                    ${preview}
                                </div>
                                <div class="card-body" style="padding: 15px;">
                                    <h6 class="card-title" style="font-weight: bold; color: #1f4598;">${response.name}</h6>
                                    <p class="card-text" style="font-size: 14px; color: #555;">
                                        <strong>Uploaded By:</strong> ${response.uploader || 'Unknown'}<br>
                                    </p>
                                    <a href="${response.full_url}" class="btn btn-primary btn-sm mt-3" target="_blank" style="background: linear-gradient(to right, #1f4598, #fbaf44); border: none;">Download</a>
                                </div>
                            </div>
                        </div>
                    `;
                    $('#recentDocuments').prepend(documentCard); // Add the new document at the top
                    feather.replace(); // Re-render Feather icons
                },
                error: function() {
                    Swal.close(); // Close the loading indicator
                    Swal.fire('Error', 'Failed to upload document.', 'error');
                }
            });
        });

        // Toggle Due Date fields based on "Use Due Date" selection
        $('#useDueDate').on('change', function() {
            if ($(this).val() === 'yes') {
                $('#dueDateFields').show();
            } else {
                $('#dueDateFields').hide();
            }
        });

        // Fetch recent documents
        const userId = {{ $user_id }};
        const recentDocumentsContainer = $('#recentDocuments');

        // Fetch recent documents on page load
        function fetchRecentDocuments() {
            console.log('Fetching recent documents for user ID:', userId); // Debugging log
            $.ajax({
                url: `/api/v1/documents/recents/${userId}`,
                method: 'GET',
                beforeSend: function() {
                    console.log('Sending request to fetch recent documents...'); // Debugging log
                    recentDocumentsContainer.html('<p>Loading recent documents...</p>');
                },
                success: function(response) {
                    console.log('Response received:', response); // Debugging log
                    recentDocumentsContainer.empty();
                    if (response.length > 0) {
                        response.forEach(document => {
                            const fileExtension = document.path.split('.').pop().toLowerCase();
                            let preview;

                            if (/\.(jpg|jpeg|png|gif|bmp|webp)$/i.test(document.path)) {
                                preview = `<img src="${document.full_url}" class="card-img-top" alt="Preview of ${document.name}">`;
                            } else if (['pdf', 'doc', 'docx', 'xls', 'xlsx'].includes(fileExtension)) {
                                preview = `<iframe src="${document.full_url}" class="card-img-top" style="width: 100%; height: 200px;" frameborder="0" sandbox></iframe>`;
                            } else {
                                preview = `<div class="card-img-top text-center" style="font-size: 50px; padding: 20px; color: #1f4598;">
                                               <i data-feather="file-text"></i>
                                           </div>`;
                            }

                            const dueDateInfo = document.due_date ? `
                                <span style="float: right;" class="${document.color}">
                                    <i data-feather="clock" class="icon-sm"></i> ${document.remaining}
                                </span>` : '';

                            const documentCard = `
                                <div class="col-md-4 mb-4">
                                    <div class="card shadow-sm" style="border-radius: 10px; overflow: hidden; transition: transform 0.3s, box-shadow 0.3s;">
                                        <div style="height: 200px; overflow: hidden; background-color: #f4f4f4;">
                                            ${preview}
                                        </div>
                                        <div class="card-body" style="padding: 15px;">
                                            <h6 class="card-title" style="font-weight: bold; color: #1f4598;">${document.name}</h6>
                                            <p class="card-text" style="font-size: 14px; color: #555;">
                                                <strong>Due Date:</strong> ${document.due_date || 'N/A'}
                                                ${dueDateInfo}
                                                <br>
                                                <strong>Uploaded By:</strong> ${document.uploader || 'Unknown'}<br>
                                            </p>
                                            <a href="${document.full_url}" class="btn btn-primary btn-sm mt-3" target="_blank" style="background: linear-gradient(to right, #1f4598, #fbaf44); border: none;">Download</a>
                                        </div>
                                    </div>
                                </div>
                            `;
                            recentDocumentsContainer.append(documentCard);
                        });
                        feather.replace(); // Re-render Feather icons
                    } else {
                        console.log('No recent documents found.'); // Debugging log
                        recentDocumentsContainer.html('<p>No recent documents found.</p>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching recent documents:', error); // Debugging log
                    recentDocumentsContainer.html('<p>Failed to load recent documents. Please try again later.</p>');
                }
            });
        }

        // Call the function to fetch recent documents
        fetchRecentDocuments();

        // Initialize Select2 for the tags field
        $('#documentTags').select2({
            placeholder: 'Select tags',
            allowClear: true
        });

        feather.replace();
    });
  </script>
@endpush
