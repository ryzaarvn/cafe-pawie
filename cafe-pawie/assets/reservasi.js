// Fitur Cari Nama di Tabel
function searchTable() {
    let input = document.getElementById("searchInput");
    let filter = input.value.toUpperCase();
    let table = document.getElementById("reservasiTable");
    let tr = table.getElementsByTagName("tr");

    for (let i = 1; i < tr.length; i++) {
        let td = tr[i].getElementsByTagName("td")[1]; // Kolom Nama Pemesan
        if (td) {
            let txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

// Konfirmasi Hapus
function hapusReservasi(id) {
    if (confirm("Apakah Anda yakin ingin menghapus data reservasi ini?")) {
        window.location.href = "hapus_reservasi.php?id=" + id;
    }
}