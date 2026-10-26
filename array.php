<?php
// deklarasi array
//$nama_mhsw[];
/// ngisi nilai array
$nama_mhsw = array("Gusty", "Fatimah", "Ika", "Aril");
$nilai_akhir = array(99.99,97,98,99);
// tampilkan manual
//echo "Isi elemen ke-1: ". $nama_mhsw[0]. "<br>";
//echo "Isi elemen ke-2: ". $nama_mhsw[1]. "<br>";
//echo "Isi elemen ke-3: ". $nama_mhsw[2]. "<br>";
//echo "Isi elemen ke-4: ". $nama_mhsw[3]. "<br>";

//menambah elemen baru pada array
$nama_mhsw[] = "Inka";
$nama_mhsw[] = "Sabrina";
$nama_mhsw[] = "Alya";
//$nama_mhsw[] = "Ahmad";
//$nama_mhsw[] = "Patrizio";
//$nama_mhsw[] = "Hikmal";
//$nama_mhsw[] = "Hezron";
//$nama_mhsw[] = "Restu";
//$nama_mhsw[] = "Fatih";
//$nama_mhsw[] = "Ilham";
//$nama_mhsw[] = "Freky";
//$nama_mhsw[] = "Surya";

//menambah elemen baru pada array
$nilai_akhir[] = 80;
$nilai_akhir[] = 70;
$nilai_akhir[] = 60;

//foreach($nama_mhsw as $key => $value) {
//    echo "Isi elemen ke-". ($key+1). ": ".$value . "<br><br>";
//}

// menghitung banyaknya elemen array
$elemen1 = count($nama_mhsw);
$elemen2 = count($nilai_akhir);

//cek apakah jumlah elemen sama
if($elemen1 == $elemen2) {
    echo "Jumlah elemen array SAMA = ". $elemen1."<br>";
}
    else {
        echo "Jumlah elemen BERBEDA <br>";
        echo "Elemen Array nama: ". $elemen1. "<br>";
        echo "Elemen Array nilai: ". $elemen2. "<br>";
    }

//echo "Jumlah elemen array: ".$elemen."<br>";

// pakai looping for(..) untuk menampilkan isi array
for($i=0; $i< $elemen1; $i++) {
    //echo $nama_mhsw[$i] . " = " . $nilai_akhir[$i]. "<br>";
    echo "Isi elemen ke-". ($i+1).": "."Nama Mahasiswa: ".$nama_mhsw[$i]. " - ". " Nilai AKhir: ".$nilai_akhir[$i]. "<br>";

}

?>