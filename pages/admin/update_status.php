<?php

include '../../middleware/admin.php';
include '../../config/koneksi.php';

$id = $_GET['id'];

$query = mysqli_query(
    $conn,
    "SELECT * FROM transactions WHERE id='$id'"
);

$data = mysqli_fetch_assoc($query);

?>



<h2>Update Status Laundry</h2>

<form action="../../process/update_status_process.php" method="POST">

    <input 
        type="hidden" 
        name="id"
        value="<?= $data['id']; ?>"
    >

    <label>Status Laundry</label>

    <br><br>

    <select name="status">

        <option value="Pending">
            Pending
        </option>

        <option value="Washing">
            Washing
        </option>

        <option value="Drying">
            Drying
        </option>

        <option value="Ironing">
            Ironing
        </option>

        <option value="Ready">
            Ready
        </option>

        <option value="Picked Up">
            Picked Up
        </option>

    </select>

    <br><br>

    <button type="submit">
        Update Status
    </button>

</form>