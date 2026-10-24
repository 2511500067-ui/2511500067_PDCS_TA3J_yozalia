<?php

require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../helpers/response.php";

//GET by ID 
    if (isset($_GET['id'])) {
        $id = (int) $_GET['id'];

        $q = "SELECT
                m.id,
                m.nama,
                m.nim,
                j.nama_jurusan AS jurusan
                FROM mahasiswa m
                LEFT JOIN jurusan j ON m.jurusan_id = j.id
                WHERE m.id = '$id'";

            $r = mysqli_query($koneksi , $q);

            if(!$r){
                sendResponse(
                    false, "query gagal: " . mysqli_error($koneksi),
                    null,
                    500
                );
            }

            $data = mysqli_fetch_assoc($r);

            if (!$data) {
                sendResponse(false, "Mahasiswa tidak ditemukan",
                null,404);
            }

            sendResponse(true,"berhasil", $data, 200);
    }

//search
    if (isset($_GET['search'])) {
        $search = $_GET['search'];

        $q = "SELECT
                m.id,
                m.nama,
                m.nim,
                j.nama_jurusan AS jurusan
                FROM mahasiswa m
                LEFT JOIN jurusan j ON m.jurusan_id = j.id
                WHERE m.nama LIKE '%$search%' 
                OR m.nim LIKE '%$search%'";

            $r = mysqli_query($koneksi, $q);

            if(!$r){
                sendResponse(
                    false, "query gagal: " . mysqli_error($koneksi),
                    null,
                    500
                );
            }

            $data = mysqli_fetch_all($r, MYSQLI_ASSOC);

            if (!$data) {
                sendResponse(false, "Mahasiswa tidak ditemukan",
                null,404);
            }

            sendResponse(true,"berhasil", $data, 200);
    }

    //pagination
    if (isset($_GET['page']) || isset($_GET['limit'])) {

        $page = isset($_GET['page']) 
        ?(int) $_GET['page'] : 1;

        $limit = isset($_GET['limit'])
         ? (int) $_GET['limit']
          : 10;

        if ($page < 1) {
            $page = 1;
        }

        if ($limit < 1) {
            $limit = 10;
        }

        $offset = ($page - 1) * $limit;

        $q = "SELECT
                m.id,
                m.nama,
                m.nim,
                j.nama_jurusan AS jurusan
                FROM mahasiswa m
                LEFT JOIN jurusan j ON m.jurusan_id = j.id
                LIMIT $limit OFFSET $offset";

            $r = mysqli_query($koneksi, $q);

            if(!$r){
                sendResponse(
                    false, "query gagal: " . mysqli_error($koneksi),
                    null,
                    500
                );
            }

            $data = [];

            while ($row = mysqli_fetch_assoc($r)) {
                $data[] = $row;
            }

            sendResponse(
                true,
                "berhasil",
                $data,
                200
            );
            
            }

            
    