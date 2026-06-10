// =========================
// DASHBOARD READY
// =========================

document.addEventListener("DOMContentLoaded", function(){

    console.log("Dashboard Admin Cafe Pawie aktif.");

});

// =========================
// FITUR SEARCH TABLE
// =========================

function searchTable(){

    let input = document.getElementById("searchInput");

    let filter = input.value.toUpperCase();

    let table = document.getElementById("reservasiTable");

    let tr = table.getElementsByTagName("tr");

    for(let i = 1; i < tr.length; i++){

        let td = tr[i].getElementsByTagName("td")[1];

        if(td){

            let txtValue =
                td.textContent || td.innerText;

            if(txtValue.toUpperCase().indexOf(filter) > -1){

                tr[i].style.display = "";

            }else{

                tr[i].style.display = "none";

            }

        }

    }

}

// =========================
// KONFIRMASI HAPUS
// =========================

function hapusReservasi(id){

    let konfirmasi = confirm(
        "Apakah Anda yakin ingin menghapus reservasi ini?"
    );

    if(konfirmasi){

        window.location.href =
            "hapus_reservasi.php?id=" + id;

    }

}

// =========================
// KONFIRMASI PELUNASAN
// =========================

function lunaskan(id){

    let konfirmasi = confirm(
        "Apakah pelanggan sudah melunasi pembayaran?"
    );

    if(konfirmasi){

        window.location.href =
            "proses_pelunasan.php?id=" + id;

    }

}