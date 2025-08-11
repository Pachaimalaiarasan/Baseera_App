<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Branch</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background: #f4f6fc;
    }
    .container {
        max-width: 500px;
        margin: 40px auto;
        background: #fff;
        padding: 24px;
        border-radius: 12px;
        box-shadow: 0 4px 14px rgba(0,0,0,0.05);
    }
    h2 {
        margin-top: 0;
        text-align: center;
        color: #333;
    }
    label {
        font-weight: 500;
        margin-top: 12px;
        display: block;
    }
    input {
        width: 100%;
        padding: 11px;
        margin-top: 5px;
        border: 1px solid #ccc;
        border-radius: 6px;
        box-sizing: border-box;
    }
    .btn {
        width: 100%;
        padding: 13px;
        background-color: #1894f2;
        border: none;
        border-radius: 6px;
        color: #fff;
        font-size: 16px;
        margin-top: 16px;
        cursor: pointer;
    }
    .btn:hover {
        background-color: #0e67b2;
    }
    .message {
        margin-top: 14px;
        padding: 10px;
        border-radius: 6px;
        font-size: 14px;
        display: none;
    }
    .success {
        background: #e1f6e9;
        color: #277d45;
        border: 1px solid #27ae60;
    }
    .error {
        background: #fdecea;
        color: #c0392b;
        border: 1px solid #e74c3c;
    }
</style>
</head>
<body>

<div class="container">
    <h2>Add New Branch</h2>
    <form id="branch-form">
        <label for="branch_name">Branch Name *</label>
        <input type="text" id="branch_name" name="branch_name" required>

        <label for="branch_city">Branch City</label>
        <input type="text" id="branch_city" name="branch_city">

        <label for="branch_phone">Branch Phone</label>
        <input type="text" id="branch_phone" name="branch_phone">

        <button type="submit" class="btn">Add Branch</button>
    </form>
    <div id="message" class="message"></div>
</div>

<script>
document.getElementById('branch-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const branchName = document.getElementById('branch_name').value.trim();
    if (!branchName) {
        showMessage('Branch name is required.', 'error');
        return;
    }

    // Create FormData since backend is reading $_POST
    const formData = new FormData();
    formData.append('branch_name', branchName);
    formData.append('branch_city', document.getElementById('branch_city').value.trim());
    formData.append('branch_phone', document.getElementById('branch_phone').value.trim());

    fetch('add_branch.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            showMessage(data.message, 'success');
            document.getElementById('branch-form').reset();
            // Optionally redirect or refresh the branch list:
            // window.location.href = 'admin_dashboard.html';
        } else {
            showMessage(data.message, 'error');
        }
    })
    .catch(err => {
        console.error(err);
        showMessage('An error occurred. Please try again.', 'error');
    });
});

function showMessage(msg, type) {
    const msgDiv = document.getElementById('message');
    msgDiv.innerText = msg;
    msgDiv.className = 'message ' + type;
    msgDiv.style.display = 'block';
}
</script>

</body>
</html>
