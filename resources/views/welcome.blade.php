@extends('layouts.app')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1></h1>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addProductModal">
            <i class="fas fa-plus"></i> Add Product
        </button>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Add Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addProductForm" action="{{ url('products') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Product Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <table id="products-table" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
          <!-- table body -->
        </tbody>
    </table>
@endsection

@section('scripts')
<script>
$(document).ready(function() {

    function fetchDataAndDisplay() {
      
        $.ajax({
            
            url: '{{ url('get-products') }}',
            method: 'GET',
            success: function(response) {
                // Initialize DataTable with fetched data
                $('#products-table').DataTable({
                    "data": response.products,
                    "columns": [
                        { "data": "name" },
                        {
                            "data": null,
                            "render": function(data, type, row) {
                                return `
                                    <a href="#"  class="btn btn-warning">Edit</a>
                                    <form action="#" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button disabled type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                `;
                            }
                        }
                    ]
                });
            },
            error: function(xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                alert('Failed to fetch data: ' + errorMessage);
            }
        });
    }
  
    fetchDataAndDisplay();
  
    $('#addProductForm').on('submit', function(event) {
        event.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    // Add new product row to the DataTable
                    var newRow = `
                        <tr>
                            <td>${response.product.name}</td>
                            <td>
                                <a href="#" class="btn btn-warning">Edit</a>
                                <form action="#" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button disabled type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    `;
                    $('#products-table').DataTable().row.add($(newRow)).draw(false);
                    $('#addProductModal').modal('hide');
                    $('#addProductForm')[0].reset();
                } else {
                    alert('An error occurred: ' + response.message); 
                }
            },
            error: function(xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                alert('Ajax request failed: ' + errorMessage); 
            }
        });
    });
});
</script>
@endsection
