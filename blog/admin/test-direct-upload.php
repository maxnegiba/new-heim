<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) die('Not logged in');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Raw Upload Response</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .test-box { background: #f5f5f5; padding: 20px; margin: 20px 0; border-radius: 5px; }
        pre { background: white; padding: 10px; border: 1px solid #ddd; overflow: auto; }
        button { background: #667eea; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #5a67d8; }
    </style>
</head>
<body>
    <h1>Test Raw Upload Response</h1>
    
    <div class="test-box">
        <h3>1. Test List Action</h3>
        <button onclick="testList()">Test List</button>
        <pre id="listResult">Click button to test...</pre>
    </div>
    
    <div class="test-box">
        <h3>2. Test File Upload</h3>
        <input type="file" id="testFile" accept="image/*">
        <button onclick="testUpload()">Upload Test</button>
        <pre id="uploadResult">Select a file and click upload...</pre>
    </div>
    
    <div class="test-box">
        <h3>3. Test Direct Form Submit</h3>
        <form id="directForm" action="/blog/admin/upload.php" method="POST" enctype="multipart/form-data" target="resultFrame">
            <input type="hidden" name="action" value="upload">
            <input type="file" name="file" required>
            <button type="submit">Direct Submit</button>
        </form>
        <iframe name="resultFrame" style="width: 100%; height: 200px; border: 1px solid #ddd; margin-top: 10px;"></iframe>
    </div>

    <script>
    function testList() {
        const result = document.getElementById('listResult');
        result.textContent = 'Loading...';
        
        fetch('/blog/admin/upload.php?action=list')
            .then(response => {
                console.log('Response headers:', response.headers);
                return response.text();
            })
            .then(text => {
                console.log('Raw response:', text);
                result.textContent = 'Raw Response:\n' + text + '\n\n';
                
                try {
                    const json = JSON.parse(text);
                    result.textContent += 'Parsed JSON:\n' + JSON.stringify(json, null, 2);
                } catch (e) {
                    result.textContent += 'JSON Parse Error: ' + e.message;
                }
            })
            .catch(error => {
                result.textContent = 'Fetch Error: ' + error;
            });
    }
    
    function testUpload() {
        const fileInput = document.getElementById('testFile');
        const result = document.getElementById('uploadResult');
        
        if (!fileInput.files[0]) {
            result.textContent = 'Please select a file first';
            return;
        }
        
        const formData = new FormData();
        formData.append('file', fileInput.files[0]);
        formData.append('action', 'upload');
        
        result.textContent = 'Uploading...';
        
        const xhr = new XMLHttpRequest();
        
        xhr.onload = function() {
            console.log('Status:', xhr.status);
            console.log('Response:', xhr.responseText);
            
            result.textContent = 'Status: ' + xhr.status + '\n';
            result.textContent += 'Raw Response:\n' + xhr.responseText + '\n\n';
            
            try {
                const json = JSON.parse(xhr.responseText);
                result.textContent += 'Parsed JSON:\n' + JSON.stringify(json, null, 2);
            } catch (e) {
                result.textContent += 'JSON Parse Error: ' + e.message + '\n';
                result.textContent += 'First 100 chars: ' + xhr.responseText.substring(0, 100);
            }
        };
        
        xhr.onerror = function() {
            result.textContent = 'XHR Error occurred';
        };
        
        xhr.open('POST', '/blog/admin/upload.php');
        xhr.send(formData);
    }
    </script>
</body>
</html>