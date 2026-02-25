<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="30">
    <title>Admin - Manage Contacts | Tigapagi</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            background-image: url('{{ asset("img/Cover 1.png") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: #fff;
            padding: 40px 20px;
            position: relative;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.75);
            z-index: 0;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .header-left h1 {
            font-size: 48px;
            font-weight: 700;
            margin: 0 0 8px 0;
            color: #fff;
            letter-spacing: -0.5px;
        }
        
        .header-left p {
            font-size: 17px;
            color: rgba(255, 255, 255, 0.8);
            margin: 0;
        }
        
        .header-buttons {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        
        .btn-dashboard {
            display: inline-block;
            padding: 12px 24px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .btn-dashboard:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.01);
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: rgba(15, 15, 15, 0.85);
            backdrop-filter: blur(10px);
            padding: 25px;
            border-radius: 12px;
            border: 1.5px solid rgba(255, 255, 255, 0.2);
        }
        
        .stat-card h3 {
            color: #00ff88;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .stat-card .number {
            font-size: 36px;
            font-weight: bold;
            color: #fff;
        }
        
        .contacts-table {
            background: rgba(15, 15, 15, 0.85);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 30px;
            border: 1.5px solid rgba(255, 255, 255, 0.2);
            overflow-x: auto;
        }
        
        .table-header {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1.5px solid rgba(255, 255, 255, 0.2);
        }
        
        .table-header h2 {
            color: #fff;
            font-size: 24px;
            font-weight: 600;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            font-weight: 600;
        }
        
        .btn-delete {
            background: #dc3545;
            color: white;
            font-size: 12px;
            padding: 8px 16px;
        }
        
        .btn-delete:hover {
            background: #c82333;
            transform: scale(1.01);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th {
            background: rgba(0, 0, 0, 0.3);
            padding: 20px 15px;
            text-align: left;
            font-weight: 600;
            color: #fff;
            border-bottom: 1.5px solid rgba(255, 255, 255, 0.2);
            font-size: 16px;
        }
        
        td {
            padding: 18px 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            color: rgba(255, 255, 255, 0.95);
            font-size: 15px;
        }
        
        tr:last-child td {
            border-bottom: none;
        }
        
        tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: rgba(255, 255, 255, 0.6);
        }
        
        .empty-state h3 {
            margin-bottom: 10px;
            color: rgba(255, 255, 255, 0.9);
            font-size: 24px;
        }
        
        .success-message {
            background: rgba(209, 231, 221, 0.95);
            color: #0f5132;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #badbcc;
            font-weight: 500;
        }
        
        @media (max-width: 768px) {
            body {
                padding: 20px 15px;
            }
            
            .header-left h1 {
                font-size: 32px;
            }
            
            .header-left p {
                font-size: 15px;
            }
            
            .stats {
                grid-template-columns: 1fr;
            }
            
            .contacts-table {
                padding: 20px;
            }
            
            table {
                font-size: 14px;
                display: block;
                overflow-x: auto;
            }
            
            th, td {
                padding: 12px 10px;
                font-size: 14px;
            }
        }
    </style>
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
