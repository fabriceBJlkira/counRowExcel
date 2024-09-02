<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Excel File</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold text-center mb-6">Upload Excel File to Count Rows</h2>
        <form action="countRow.php" method="post" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label for="excelFile" class="block text-sm font-medium text-gray-700">Choose Excel File</label>
                <input type="file" name="excelFile" id="excelFile" accept=".xlsx, .xls, .csv" required
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Upload and Count Rows
                </button>
            </div>
        </form>
        
    </div>

</body>
</html>
