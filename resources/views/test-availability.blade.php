
<!DOCTYPE html>
<html>
<head>
    <title>Test Availability</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .blocked { background-color: #ffcccc; }
        .available { background-color: #ccffcc; }
    </style>
</head>
<body>
    <h1>Testing Availability for: {{ $newStart->format('Y-m-d H:i') }} to {{ $newEnd->format('Y-m-d H:i') }}</h1>
    
    <table>
        <tr>
            <th>Client</th>
            <th>Existing Appointment</th>
            <th>New Booking</th>
            <th>Overlaps?</th>
            <th>Check 1 (New Start < Existing End)</th>
            <th>Check 2 (New End > Existing Start)</th>
        </tr>
        @foreach($results as $result)
        <tr class="{{ str_contains($result['overlaps'], 'YES') ? 'blocked' : 'available' }}">
            <td>{{ $result['client'] }}</td>
            <td>{{ $result['existing_start'] }} to {{ $result['existing_end'] }}</td>
            <td>{{ $result['new_start'] }} to {{ $result['new_end'] }}</td>
            <td><strong>{{ $result['overlaps'] }}</strong></td>
            <td>{{ $result['condition_1'] }}</td>
            <td>{{ $result['condition_2'] }}</td>
        </tr>
        @endforeach
    </table>
    
    <h2>Time Analysis:</h2>
    @foreach($results as $result)
    <p>
        <strong>{{ $result['client'] }}:</strong><br>
        New booking at 3:00 PM would end at: {{ \Carbon\Carbon::parse($result['new_start'])->addHours(2)->format('H:i') }} (2 hours later)<br>
        Existing appointment ends at: {{ \Carbon\Carbon::parse($result['existing_end'])->format('H:i') }}<br>
        Gap: {{ \Carbon\Carbon::parse($result['new_start'])->diffInMinutes(\Carbon\Carbon::parse($result['existing_end'])) }} minutes
    </p>
    @endforeach
</body>
</html>