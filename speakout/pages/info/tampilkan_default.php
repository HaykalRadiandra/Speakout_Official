<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";

// Ambil tahun sekarang untuk set selected
$tahunSekarang = date('Y');

// Query ambil 7 tahun terakhir dari MySQL
$query = "
    SELECT 
        YEAR(CURDATE()) - n AS tahun,
        CASE WHEN n = 0 THEN 1 ELSE 0 END AS is_selected
    FROM (
        SELECT 0 AS n UNION ALL
        SELECT 1 UNION ALL
        SELECT 2 UNION ALL
        SELECT 3 UNION ALL
        SELECT 4 UNION ALL
        SELECT 5 UNION ALL
        SELECT 6
    ) AS x
    ORDER BY tahun DESC
";

$hasil = mysqli_query($conn, $query);

// Generate opsi
while($row = mysqli_fetch_assoc($hasil)) {
    $tahun = $row['tahun'];
    $selected = ($row['is_selected'] == 1) ? "selected" : "";
    echo "<option value='$tahun' $selected>Tahun $tahun</option>";
}
?>
