<?php
$xmlFile = 'data.xml';

if (!file_exists($xmlFile)) {
    $xml = new SimpleXMLElement('<users></users>');
    $xml->asXML($xmlFile);
}

$xml = simplexml_load_file($xmlFile);
$editing = false;
$editUser = null;
$editId = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $fullName = $_POST['full_name'];
    $age = $_POST['age'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $maritalStatus = $_POST['marital_status'];
    $position = $_POST['position'];
    $photoPath = '';

    if (!empty($_FILES['profile_photo']['name'])) {
        $targetDir = "uploads/";
        if (!file_exists($targetDir)) mkdir($targetDir);
        $photoPath = $targetDir . basename($_FILES["profile_photo"]["name"]);
        move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $photoPath);
    }

    $found = false;

    if ($id) {
        foreach ($xml->user as $user) {
            if ((string)$user['id'] === $id) {
                $user->full_name = $fullName;
                $user->age = $age;
                $user->contact = $contact;
                $user->address = $address;
                $user->marital_status = $maritalStatus;
                $user->position = $position;
                if ($photoPath) $user->photo = $photoPath;
                $found = true;
                break;
            }
        }
    }

    if (!$found) {
        $id = uniqid();
        $user = $xml->addChild('user');
        $user->addAttribute('id', $id);
        $user->addChild('photo', $photoPath);
        $user->addChild('full_name', $fullName);
        $user->addChild('age', $age);
        $user->addChild('contact', $contact);
        $user->addChild('address', $address);
        $user->addChild('marital_status', $maritalStatus);
        $user->addChild('position', $position);
    }

    $xml->asXML($xmlFile);
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

if (isset($_GET['delete'])) {
    $idToDelete = $_GET['delete'];
    $index = 0;
    foreach ($xml->user as $user) {
        if ((string)$user['id'] === $idToDelete) {
            unset($xml->user[$index]);
            $xml->asXML($xmlFile);
            break;
        }
        $index++;
    }
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

if (isset($_GET['edit'])) {
    $editing = true;
    $editId = $_GET['edit'];
    foreach ($xml->user as $user) {
        if ((string)$user['id'] === $editId) {
            $editUser = $user;
            break;
        }
    }
}

$searchTerm = $_GET['search'] ?? '';

if ($searchTerm !== '') {
    $filteredUsers = [];
    foreach ($xml->user as $user) {
        if (stripos($user->full_name, $searchTerm) !== false) { // case-insensitive
            $filteredUsers[] = $user;
        }
    }
} else {
    $filteredUsers = $xml->user;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>MIRA Account Management</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #2a2a33;
        }

        .top-bar {
            background-color: #18cb96;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .top-bar img {
            height: 55px;
        }

        .title {
            font-size: 15px;
            font-weight: bold;
            color: #000;
        }

        .logout-btn {
            background-color: #333;
            color: #fff;
            border: none;
            padding: 5px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }

        .about-btn {
            background-color: #333;
            color: #fff;
            border: none;
            padding: 5px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }

        .main {
            display: flex;
            gap: 20px;
            padding: 30px;
        }

        .panel {
            background-color: #ffffff;
            padding: 25px;
            border-radius: 12px;
            flex: 1;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .panelone {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 350px;   /* fixed width */
            height: 650px;  /* fixed height */
        } 

        form label {
            font-weight: bold;
        }

        form input, form button {
            margin-bottom: 15px;
            display: block;
            width: 300px;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        form button {
            background-color: #00c49a;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 20px;
            background-color: #ffffff;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
            border-radius: 16px;
            overflow: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 15px;
            color: #333;
        }

        th, td {
            border-bottom: 1px solid #e6e6e6;
            padding: 14px 18px;
            text-align: center;
        }

        th {
            background: #00c49a;
            color: white;
            font-weight: 600;
            font-size: 15px;
            letter-spacing: 0.5px;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #eefaf7;
            cursor: pointer;
            transition: background-color 0.25s ease;
        }

        td {
            transition: background-color 0.2s ease;
        }

        img.profile-img {
            border-radius: 30%;
            object-fit: cover;
            width: 55px;
            height: 55px;
            border: 2px solid #ddd;
        }

        a {
            color: #00c49a;
            text-decoration: none;
            font-weight: bold;
            background-color: #333;
            border: none;
            padding: 2px 3px;
            font-size: 14px;
            border-radius: 8px;
            cursor: pointer;
            display: inline-block;
            transition: all 0.2s ease;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.15);
        }

        a:hover {
            background-color: #444;
            color: #00ffcc;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            text-decoration: none;
        }
        
        .search-form {
            margin-bottom: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }

        .search-input {
            padding: 10px 14px;
            width: 100%;
            max-width: 320px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .search-input:focus {
            border-color: #00c49a;
            box-shadow: 0 0 0 3px rgba(0, 196, 154, 0.2);
            outline: none;
        }

        .search-button {
            padding: 10px 16px;
            border-radius: 8px;
            border: none;
            background-color: #00c49a;
            color: white;
            cursor: pointer;
            font-weight: 600;
            font-size: 16px;
            transition: background-color 0.3s, transform 0.2s;
        }

        .search-button:hover {
            background-color: #00b28a;
            transform: translateY(-1px);
        }

        .clear-link {
            margin-left: 12px;
            color: #00c49a;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            text-decoration: underline;
            transition: color 0.3s;
        }   

        .clear-link:hover {
            color: #008f76;
        }
    </style>
</head>
<body>

<div class="top-bar" style="display: flex; justify-content: space-between; align-items: center;">
    <div style="display: flex; align-items: center; gap: 10px;">
        <img src="miratopbar.png" alt="MIRA Logo" />
        <span class="title">Martin Josshua G. Binuya (3B-G1) <br /> Irish Zabrina B. De Belen (3B-G1)</span>
    </div>
    <div style="display: flex; gap: 4px;">
        <a href="aboutus.php"><button class="about-btn">About Us</button></a>
        <a href="index.php"><button class="logout-btn">Log Out</button></a>
    </div>
</div>

<div class="main">
    <div class="panelone">
        <h2><?= $editing ? 'Update' : 'Add New' ?> Account</h2>
        <form method="post" enctype="multipart/form-data">
            <?php if ($editing): ?>
                <input type="hidden" name="id" value="<?= $editId ?>">
            <?php endif; ?>
            <label>Profile Photo:</label>
            <input type="file" name="profile_photo">
            <label>Full Name:</label>
            <input type="text" name="full_name" required value="<?= $editing ? $editUser->full_name : '' ?>">
            <label>Age:</label>
            <input type="number" name="age" required value="<?= $editing ? $editUser->age : '' ?>">
            <label>Contact:</label>
            <input type="text" name="contact" required value="<?= $editing ? $editUser->contact : '' ?>">
            <label>Address:</label>
            <input type="text" name="address" required value="<?= $editing ? $editUser->address : '' ?>">
            <label>Marital Status:</label>
            <input type="text" name="marital_status" required value="<?= $editing ? $editUser->marital_status : '' ?>">
            <label>Position:</label>
            <input type="text" name="position" required value="<?= $editing ? $editUser->position : '' ?>">
            <button type="submit"><?= $editing ? 'Update' : 'Add' ?> Record</button>
            <?php if ($editing): ?>
                <a href="<?= $_SERVER['PHP_SELF'] ?>">Cancel</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="panel">
        <h2>Account Records</h2>

        <form method="get" class="search-form">
            <input
                type="text"
                name="search"
                placeholder="Search by full name..."
                class="search-input"
                value="<?= htmlspecialchars($searchTerm) ?>"
            >
            <button type="submit" class="search-button">Search</button>
            <?php if (!empty($searchTerm)): ?>
                <a href="<?= $_SERVER['PHP_SELF'] ?>" class="clear-link">Clear</a>
            <?php endif; ?>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Profile Photo</th>
                    <th>Full Name</th>
                    <th>Age</th>
                    <th>Contact</th>
                    <th>Address</th>
                    <th>Marital Status</th>
                    <th>Position</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($filteredUsers) === 0): ?>
                    <tr><td colspan="8" style="text-align:center; padding: 20px;">No records found.</td></tr>
                <?php else: ?>
                    <?php foreach ($filteredUsers as $user): ?>
                        <tr>
                            <td>
                                <?php if (!empty($user->photo) && file_exists($user->photo)): ?>
                                    <img class="profile-img" src="<?= htmlspecialchars($user->photo) ?>" alt="Profile Photo" />
                                <?php else: ?>
                                    <img class="profile-img" src="default-avatar.png" alt="No Photo" />
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($user->full_name) ?></td>
                            <td><?= htmlspecialchars($user->age) ?></td>
                            <td><?= htmlspecialchars($user->contact) ?></td>
                            <td><?= htmlspecialchars($user->address) ?></td>
                            <td><?= htmlspecialchars($user->marital_status) ?></td>
                            <td><?= htmlspecialchars($user->position) ?></td>
                            <td>
                                <a href="?edit=<?= urlencode($user['id']) ?>">Edit</a> |
                                <a href="?delete=<?= urlencode($user['id']) ?>" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
