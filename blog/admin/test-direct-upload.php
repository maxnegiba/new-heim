<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    die('Not logged in');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Direct Upload Test</title>
</head>
<body>
    <h2>Direct Upload Test</h2>
    
    <form action="/blog/admin/upload.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="upload">
        <input type="file" name="file" required>
        <button type="submit">Upload</button>
    </form>
    
    <hr>
    
    <h3>Test List Media</h3>
    <button onclick="testList()">List Media</button>
    <div id="result"></div>
    
    <script>
    function testList() {
        fetch('/blog/admin/upload.php?action=list')
            .then(r => r.text())
            .then(text => {
                document.getElementById('result').innerHTML = '<pre>' + text + '</pre>';
            })
            .catch(e => {
                document.getElementById('result').innerHTML = 'Error: ' + e;
            });
    }
    </script>
</body>
</html>