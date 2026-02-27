<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Contacts | Tigapagi</title>
    <link rel="stylesheet" href="{{ asset('css/admin/contacts.css') }}">
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-left">
                <h1>Contact Management</h1>
                <p>View and manage all contact form submissions</p>
            </div>
        </div>
        
        <div class="header-buttons">
            <a href="{{ url('/tracking/admin_dashboard.php') }}" class="btn-dashboard">← Back to Admin Dashboard</a>
        </div>
        
        <div class="stats">
            <div class="stat-card">
                <h3>Total Contacts</h3>
                <div class="number">{{ $contacts->count() }}</div>
            </div>
            <div class="stat-card">
                <h3>Today</h3>
                <div class="number">{{ $todayCount }}</div>
            </div>
            <div class="stat-card">
                <h3>This Week</h3>
                <div class="number">{{ $weekCount }}</div>
            </div>
        </div>
        
        @if(session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif
        
        <div class="contacts-table">
            <div class="table-header">
                <h2>All Contacts</h2>
            </div>
            
            @if($contacts->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Submitted</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contacts as $index => $contact)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $contact->name }}</td>
                                <td>{{ $contact->email }}</td>
                                <td>{{ $contact->phone }}</td>
                                <td>{{ $contact->created_at->format('d M Y, H:i') }}</td>
                                <td>
                                    <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this contact?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-delete">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <h3>No contacts yet</h3>
                    <p>Contact submissions will appear here.</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
