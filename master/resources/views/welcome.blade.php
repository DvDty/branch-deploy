@use('Illuminate\Support\Str')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kubectl Pods</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        button {
            padding: 5px;
            cursor: pointer;
        }
    </style>
</head>

<body>
<h2>Instances</h2>

<table>
    <thead>
    <tr>
        {{-- <th>Branch</th> --}}
        {{-- <th>Last commit hash</th> --}}
        <th>Pod Label</th>
        <th>Image</th>
        <th>Status</th>
        <th>Link</th>
        <th>Refresh</th>
    </tr>
    </thead>
    <tbody>
    @foreach($pods as $pod)
        <tr>
            <td>{{ $pod['podLabel'] }}</td>
            <td>{{ $pod['image'] }}</td>
            <td>{{ $pod['status'] }}</td>
            <td><a href="#">{{ $pod['podIp'] }}</a></td>
            <td>
                <button onclick="refreshPod('pod-1')">Refresh</button>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<br><br><br>
<h2>Add new instance</h2>

<form>
    <label for="branch">Select Branch:</label>
    <select id="branch" name="branch">
        @foreach($branches as $branch)
            <option value="{{ Str::slug($branch) }}">{{ $branch }}</option>
        @endforeach
    </select>

    <label for="time">Select Time (in hours):</label>
    <input type="number" id="time" name="time" min="1" value="1">

    <input type="submit" value="Submit">
</form>

<script>
    // JavaScript function to refresh a specific pod
    function refreshPod(podName) {
        // Add logic to refresh the specific pod (e.g., using AJAX)
        console.log("Refreshing pod:", podName);
    }
</script>
</body>
</html>
