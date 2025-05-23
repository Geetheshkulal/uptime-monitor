<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="customersTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Role</th>
                        <th>Premium End</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                    @if($user->hasRole('user') || $user->hasRole('subuser'))
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone ?? 'N/A' }}</td>
                            <td>
                                @if($user->status === 'paid')
                                    <span class="badge badge-success">Paid</span>
                                @elseif($user->status === 'free')
                                    <span class="badge badge-secondary">Free</span>
                                @elseif($user->status === 'free_trial')
                                    <span class="badge badge-warning">Free Trial</span>
                                @elseif($user->status === 'subuser')
                                    @if($user->parentUser->status === 'paid')
                                        <span class="badge badge-success">Paid</span>
                                    @elseif($user->parentUser->status === 'free_trial')
                                        <span class="badge badge-warning">Free Trial</span> 
                                    @else
                                        <span class="badge badge-warning">Free</span> 
                                    @endif
                                @endif
                            </td>
                            <td>
                                @foreach($user->roles as $role)
                                    <span class="badge badge-info">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                @if($user->premium_end_date)
                                    {{ \Carbon\Carbon::parse($user->premium_end_date)->format('M d, Y') }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('show.user', $user->id) }}" class="btn btn-sm btn-primary" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endif
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No customers found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>