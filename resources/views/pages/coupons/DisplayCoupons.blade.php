@extends('dashboard')
@section('content')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <style>
        * {
            border-radius: 0 !important;
        }

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
        
.select2-container {
  width: 100% !important;
  max-width: 100%;

}
.select2-container--default.select2-container--focus .select2-selection--multiple
{
    border: 1px solid #ced4da;
    outline: 0;
    /* margin-right: 14px;
    margin-left: 14px; */
}


.select2-container--open .select2-dropdown--below{
    border-top: none;
     
}

.select2-container--default .select2-selection--multiple {
  min-height: 38px;
  border: 1px solid #ced4da;
  border-radius: 4px;
  padding: 6px 8px;
  background-color: #fff;
  box-shadow: none;
  margin-left: 14px;
  margin-right: 14px;
  display: flex;
  flex-wrap: wrap;
  align-items: center;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice {
   background-color: #2e59d9;
   border-color: #2653d4;
  color: #fff;
  padding: 4px 8px 4px 24px; /* Extra left padding for close button */
  margin-top: 4px;
  margin-right: 4px;
  border-radius: 3px;
  font-size: 0.875rem;
  position: relative;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
  position: absolute;
  left: 6px;
  top: 50%;
  transform: translateY(-50%);
  font-weight: bold;
  cursor: pointer;
  padding: 0 4px;
  color: #000;
}



.select2-container--default .select2-selection--multiple .select2-search__field {
  padding: 5px;
  margin-top: 4px;
  width: auto !important;
  min-width: 150px;
}


.select2-container--open {
  z-index: 9999;
}


.select2-dropdown {
  /* max-width: 100% !important; */
  box-sizing: border-box;
  padding: 5px;
  border-color:#F8F8FF;
}


</style>
@endpush

<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Manage Coupon Codes</h1>
                <button class="btn btn-primary" data-toggle="modal" data-target="#addCouponModal">
                    Create Coupon Codes
                </button>
            </div>
            
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="couponTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>SL no</th>
                                    <th>Coupon Code</th>
                                    <th>Discount Type</th>
                                    <th>Discount Value</th>
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
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{ $coupon->code }}
                                        <i class="fas fa-copy ml-2 text-primary" style="cursor: pointer;" onclick="copyToClipboard('{{$coupon->code}}')" title="Copy code"></i>
                                    </td>
                                    <td>{{ $coupon->discount_type }}</td>
                                    <td>
                                        @if ($coupon->discount_type == 'percentage')
                                            {{ $coupon->value }}%
                                        @else
                                            ₹{{ $coupon->value }}       
                                        @endif
                                    </td>
                                    <td>{{ $coupon->max_uses ? $coupon->max_uses : 'N/A'}}</td>
                                    <td>{{ $coupon->uses }}</td>
                                    <td>{{ $coupon->valid_from ? \Carbon\Carbon::parse($coupon->valid_from)->format('d M y') : 'N/A'  }}</td>
                                    <td>{{ $coupon->valid_until ? \Carbon\Carbon::parse($coupon->valid_until)->format('d M y') : 'N/A' }}</td>
                                    <td>
                                        @if($coupon->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </td> 
                                    <td>{{ $coupon->updated_at->format('d M Y, h:i A') }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <a href="{{ route('view.claimed.users', ['coupon_id' => $coupon->id]) }}">
                                                <i class="fas fa-eye" style="color: #2dce89; cursor: pointer;" title="View Coupon claimed users"></i>
                                            </a>

                                            <a href="#" data-toggle="modal" data-target="#editCouponModal{{ $coupon->id }}" class="ml-2">
                                                <i class="fas fa-edit" style="color: #2653d4; cursor: pointer;"></i>
                                            </a>

                                            <a href="#" data-toggle="modal" data-target="#deleteCouponModal{{ $coupon->id }}" class="ml-2">
                                                <i class="fas fa-trash" style="color: #e74a3b; cursor: pointer;"></i>
                                            </a>
                                        </div>
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
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <form class="modal-content" method="POST" action="{{ route('coupons.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="addCouponModalLabel">Create Coupon</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="code">Coupon Code</label>
                            <input id="code" name="code" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="subscription_id">Subscription</label>
                            <select id="subscription_id" name="subscription_id" class="form-control" required>
                                <option value="">Select Subscription</option>
                                @foreach($subscriptions as $subscription)
                                    <option value="{{ $subscription->id }}">
                                        {{ $subscription->name }} ({{ $subscription->amount }} INR)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="discount_type">Discount Type</label>
                            <select id="discount_type" name="discount_type" class="form-control" required>
                                <option value="flat">Flat (₹)</option>
                                <option value="percentage">Percentage (%)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="value">Discount Value</label>
                            <input id="value" name="value" class="form-control" required min="1">
                        </div>

                        <div class="form-group">
                            <label for="max_uses">Max Uses</label>
                            <input id="max_uses" name="max_uses" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="valid_from">Valid From</label>
                            <input type="date" id="valid_from" name="valid_from" min="{{ \Carbon\Carbon::today()->toDateString() }}" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="valid_until">Valid Until</label>
                            <input type="date" id="valid_until" name="valid_until" min="{{ \Carbon\Carbon::today()->toDateString() }}" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="is_active">Status</label>
                            <select id="is_active" name="is_active" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="user_ids">Assign to Users (optional)</label>
                            <select id="user_ids" name="user_ids[]" class="form-control select2" multiple="multiple">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Add Coupon</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Coupon Modals -->
@foreach($coupons as $coupon)
<div class="modal fade" id="editCouponModal{{ $coupon->id }}" tabindex="-1" role="dialog" aria-labelledby="editCouponModalLabel{{ $coupon->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
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
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="code{{ $coupon->id }}">Coupon Code</label>
                            <input id="code{{ $coupon->id }}" name="code" class="form-control" value="{{ $coupon->code }}" required>
                        </div>

                        <div class="form-group">
                            <label for="value{{ $coupon->id }}">Discount Value</label>
                            <input type="number" id="value{{ $coupon->id }}" name="value" class="form-control" value="{{ $coupon->value }}" required>
                        </div>

                        <input type="hidden" value="{{$coupon->discount_type}}" name="discount_type"/>
                        <input type="hidden" value="{{$coupon->subscription->id}}" name="subscription_id"/>

                        <div class="form-group">
                            <label for="max_uses{{ $coupon->id }}">Max Uses</label>
                            <input type="number" id="max_uses{{ $coupon->id }}" name="max_uses" class="form-control" value="{{ $coupon->max_uses }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="valid_from{{ $coupon->id }}">Valid From</label>
                            <input type="date" id="valid_from{{ $coupon->id }}" name="valid_from" class="form-control" value="{{ $coupon->valid_from ? \Carbon\Carbon::parse($coupon->valid_from)->format('Y-m-d') : '' }}">
                        </div>

                        <div class="form-group">
                            <label for="valid_until{{ $coupon->id }}">Valid Until</label>
                            <input type="date" id="valid_until{{ $coupon->id }}" name="valid_until" class="form-control" value="{{ $coupon->valid_until ? \Carbon\Carbon::parse($coupon->valid_until)->format('Y-m-d') : '' }}">
                        </div>

                        <div class="form-group">
                            <label for="is_active{{ $coupon->id }}">Status</label>
                            <select id="is_active{{ $coupon->id }}" name="is_active" class="form-control">
                                <option value="1" {{ $coupon->is_active ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ !$coupon->is_active ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update Coupon</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Coupon Modal -->
<div class="modal fade" id="deleteCouponModal{{ $coupon->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteCouponModalLabel{{ $coupon->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content" method="POST" action="{{ route('coupons.destroy', $coupon->id) }}">
            @csrf
            @method('DELETE')
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCouponModalLabel{{ $coupon->id }}">Delete Coupon</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
    
            <div class="modal-body">
                <p>Are you sure you want to delete this coupon: <strong>{{ $coupon->code }}</strong>?</p>
                <p>This action cannot be undone.</p>
            </div>
    
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Delete Coupon</button>
            </div>
        </form>
    </div>
</div>
@endforeach

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#couponTable').DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "order": [[7, "desc"]],
        "columnDefs": [
            { "searchable": false, "targets": [8] }
        ],
        "language": {
            "searchPlaceholder": "Search by code or type"
        }
    });

    // Initialize Select2 with proper dropdown parent
    $('#user_ids').select2({
        placeholder: "Select users",
        allowClear: true,
        dropdownParent: $('#addCouponModal'),
        width: '100%'
    });

    // Set minimum date for valid_until based on valid_from
    $('#valid_from').on('change', function() {
        $('#valid_until').val('');
        $('#valid_until').attr('min', $('#valid_from').val());
    });

    // Initialize same functionality for edit modals
    @foreach($coupons as $coupon)
        $(`#valid_from{{ $coupon->id }}`).on('change', function() {
            $(`#valid_until{{ $coupon->id }}`).val('');
            $(`#valid_until{{ $coupon->id }}`).attr('min', $(`#valid_from{{ $coupon->id }}`).val());
        });
    @endforeach

    // Toastr notifications
    @if(session('success'))
        toastr.success("{{ session('success') }}");
    @endif

    @if ($errors->any())
        toastr.error("{{ $errors->first() }}");
    @endif
});

// Copy to clipboard function
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        toastr.success('Copied: ' + text);
    }, function(err) {
        toastr.error('Failed to copy: ' + err);
    });
}
</script>
@endpush

@endsection