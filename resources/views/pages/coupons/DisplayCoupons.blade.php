

@extends('dashboard')
@section('content')

@push('styles')


    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        
        .filter-container {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }
        .filter-container label {
            margin-bottom: 0;
            font-weight: 600;
            color: #6e707e;
        }
        * {
            border-radius: 0 !important;
        }
    </style>
@endpush

<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <div class="container-fluid">
            <!-- Activity Log Table -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Manage Coupon Codes</h6>
            
                    <button class="btn btn-primary" data-toggle="modal" data-target="#addCouponModal">
                        Create Coupon Codes
                      </button>
                </div>
                <div class="card-body">
                    
                    <div class="table-responsive">
                        <table class="table table-bordered" id="couponTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>SL no</th>
                                    <th>Coupon Code</th>
                                    <th>Discount Flat</th>
                                    <th>Max uses</th>
                                    <th>Used</th>
                                    <th>Valid from</th>
                                    <th>Valid Until</th>
                                    <th>Status</th>
                                    <th>Updated At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>                                                                                                                    
                                @foreach($coupons as $coupon)
                                <tr>
                                    <td>{{ $coupon->id }}</td>
                                    <td>{{ $coupon->code }}</td>
                                    <td>{{ $coupon->value }}</td>
                                    <td>{{ $coupon->max_uses }}</td>
                                    <td>{{ $coupon->uses }}</td>
                                    <td>{{ $coupon->valid_from }}</td>
                                    <td>{{ $coupon->valid_until}}</td>
                                    <td>
                                        @if($coupon->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>
                                        @endif
                                        
                                </td> 
                                    <td>{{ $coupon->updated_at->format('d M Y, h:i A') }}</td>
                                    <td>
                                      <a href="#" data-toggle="modal" data-target="#editCouponModal{{ $coupon->id }}">
                                          <i class="fas fa-edit" style="color: #1e67e6; cursor: pointer;"></i>
                                      </a>
                                  </td>
                                  
                                   
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Coupon Modal -->
<div class="modal fade" id="addCouponModal" tabindex="-1" role="dialog" aria-labelledby="addCouponModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form class="modal-content" method="POST" action="{{ route('coupons.store') }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="addCouponModalLabel">Create Coupon</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
  
        <div class="modal-body">
          <div class="form-group">
            <label for="code">Coupon Code</label>
            <input id="code" name="code" class="form-control" placeholder="Enter coupon code" required>
          </div>
  
          <div class="form-group">
            <label for="value">Discount Flat ₹</label>
            <input type="number" id="value" name="value" class="form-control" placeholder="Enter flat discount value" required>
          </div>
  
          <div class="form-group">
            <label for="max_uses">Max Uses</label>
            <input type="number" id="max_uses" name="max_uses" class="form-control" placeholder="Enter max number of uses">
          </div>
  
          <div class="form-group">
            <label for="valid_from">Valid From</label>
            <input type="date" id="valid_from" name="valid_from" class="form-control">
          </div>
  
          <div class="form-group">
            <label for="valid_until">Valid Until</label>
            <input type="date" id="valid_until" name="valid_until" class="form-control">
          </div>
  
          <div class="form-group">
            <label for="is_active">Status</label>
            <select id="is_active" name="is_active" class="form-control">
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
        </div>
  
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Add Coupon</button>
        </div>
      </form>
    </div>
  </div>


  <!-- Edit Coupon Modal -->
<div class="modal fade" id="editCouponModal{{ $coupon->id }}" tabindex="-1" role="dialog" aria-labelledby="editCouponModalLabel{{ $coupon->id }}" aria-hidden="true">
  <div class="modal-dialog" role="document">
      <form class="modal-content" method="POST" action="{{ route('coupons.update', $coupon->id) }}">
          @csrf
          @method('PUT')
          <div class="modal-header">
              <h5 class="modal-title" id="editCouponModalLabel{{ $coupon->id }}">Edit Coupon</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
  
          <div class="modal-body">
              <div class="form-group">
                  <label for="code{{ $coupon->id }}">Coupon Code</label>
                  <input id="code{{ $coupon->id }}" name="code" class="form-control" value="{{ $coupon->code }}" required>
              </div>

              <div class="form-group">
                  <label for="value{{ $coupon->id }}">Discount Flat ₹</label>
                  <input type="number" id="value{{ $coupon->id }}" name="value" class="form-control" value="{{ $coupon->value }}" required>
              </div>

              <div class="form-group">
                  <label for="max_uses{{ $coupon->id }}">Max Uses</label>
                  <input type="number" id="max_uses{{ $coupon->id }}" name="max_uses" class="form-control" value="{{ $coupon->max_uses }}">
              </div>

              <div class="form-group">
                  <label for="valid_from{{ $coupon->id }}">Valid From</label>
                  <input type="date" id="valid_from{{ $coupon->id }}" name="valid_from" class="form-control" value="{{ $coupon->valid_from }}">
              </div>

              <div class="form-group">
                  <label for="valid_until{{ $coupon->id }}">Valid Until</label>
                  <input type="date" id="valid_until{{ $coupon->id }}" name="valid_until" class="form-control" value="{{ $coupon->valid_until }}">
              </div>

              <div class="form-group">
                  <label for="is_active{{ $coupon->id }}">Status</label>
                  <select id="is_active{{ $coupon->id }}" name="is_active" class="form-control">
                      <option value="1" {{ $coupon->is_active ? 'selected' : '' }}>Active</option>
                      <option value="0" {{ !$coupon->is_active ? 'selected' : '' }}>Inactive</option>
                  </select>
              </div>
          </div>
  
          <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Update Coupon</button>
          </div>
      </form>
  </div>
</div>

  


@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
  $(document).ready(function () {
      @if(session('success'))
          toastr.success("{{ session('success') }}");
      @endif

      @if(session('error'))
          toastr.error("{{ session('error') }}");
      @endif
  });
</script>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#couponTable').DataTable({ 
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "order": [[7, "desc"]],
            "columnDefs": [
                { "searchable": false, "targets": [8] } // Disable sorting for action column
            ]
            
        });

        // Filter table when user is selected
        $('#userFilter').change(function() {
            var userId = $(this).val();
            table.column(5).search(userId).draw();
        });
    });

   
</script>

@endpush

@endsection