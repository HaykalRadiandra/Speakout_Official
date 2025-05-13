<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";

$cari = $_GET['cari'];

$dataPerPage = 10;
if(isset($_GET['page']))
{
    $noPage = $_GET['page'];
}
else $noPage = 1;

$offset = ($noPage - 1) * $dataPerPage;

$text = "SELECT COUNT(*) AS jumData FROM guru WHERE onview=1 ";            

if(!empty($cari)) {
	$text .= "AND (nama LIKE '%$cari%' OR nip LIKE '%$cari%' OR alamat LIKE '%$cari%' OR notelp LIKE '%$cari%') ";
}
    
$hasil = mysqli_query($conn,$text);
$data = mysqli_fetch_array($hasil);

$jumData = $data['jumData'];
$jumPage = ceil($jumData / $dataPerPage);

// menampilkan navigasi paging
echo "<ul class='pagination pagination-sm no-margin pull-right'>";
if ($noPage > 1) echo  "<li><a href='javascript:void(0)' onClick=\"tampildata('".($noPage-1)."')\"><< Prev</a></li>";
for($page = 1; $page <= $jumPage; $page++)
{
    if ((($page >= $noPage - 2) && ($page <= $noPage + 2)) || ($page == 1) || ($page == $jumPage))
    {
        if (($showPage == 1) && ($page != 2))  echo "<li class='disabled'><a href='#'>...</a></li>";
        if (($showPage != ($jumPage - 1)) && ($page == $jumPage))  echo "<li class='disabled'><a href='#'>...</a></li>";
        if ($page == $noPage) echo "<li class='active'><a href='#'>".$page."</a></li>";
        else echo "<li><a href='javascript:void(0)' onClick=\"tampildata('".$page."')\">".$page."</a></li>";
        $showPage = $page;
    }
}

if ($noPage < $jumPage) echo "<li><a href='javascript:void(0)' onClick=\"tampildata('".($noPage+1)."')\">Next >></a></li>";

echo "</ul>";   
    
?>
