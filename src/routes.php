<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    date_default_timezone_set('Asia/Jakarta');
    $container = $app->getContainer();

    // $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
    //     // Sample log message
    //     $container->get('logger')->info("Slim-Skeleton '/' route");

    //     // Render index view
    //     return $container->get('renderer')->render($response, 'index.phtml', $args);
    // });

    // $app->post('/user/register', function($request, $response){
    //     $nama = $request->getParsedBodyParam('nama');
    //     $email = $request->getParsedBodyParam('email');
    //     $telepon = $request->getParsedBodyParam('telepon');
    //     $password = $request->getParsedBodyParam('password');
    //     $uploadedFiles1  = $request->getUploadedFiles();
    //     $uploadedFiles2  = $request->getUploadedFiles();

    //     $queryTelp = "SELECT * FROM tb_user WHERE telepon = :telepon";

    //     $queryEmail = "SELECT * FROM tb_user WHERE email = :email";

    //     if(empty($telepon)||empty($nama)||empty($email)||empty($password)){
    //         return $response->withJson(["code"=>201, "msg"=>"Lengkapi Data"]);
    //     }

    //     if(!$uploadedFiles1){
    //         return $response->withJson(["meta"=>["code"=>201, "msg"=>"Foto Tidak Boleh Kosong"]]);
    //     }

    //     if(!$uploadedFiles2){
    //         return $response->withJson(["meta"=>["code"=>201, "msg"=>"Foto Tidak Boleh Kosong"]]);
    //     }

    //     $stmt = $this->db->prepare($queryTelp);
    //     if($stmt->execute([':telepon' => $telepon])){
    //         $result = $stmt->fetch();
    //         $row_telepon = $result['telepon'];
    //         if($row_telepon <> null){
    //             return $response->withJson(["code"=>201, "msg"=>"Email atau nomor telepon telah terdaftar!"]);
    //         }
    //     }

    //     $stmt = $this->db->prepare($queryEmail);
    //     if($stmt->execute([':email' => $email])){
    //         $result = $stmt->fetch();
    //         $row_telepon = $result['email'];
    //         if($row_telepon <> null){
    //             return $response->withJson(["code"=>201, "msg"=>"Email atau nomor telepon telah terdaftar!"]);
    //         }
    //     }

    //     $sql_uuid   = "SELECT UUID() as uuid";
    //     $stmt_uuid  = $this->db->prepare($sql_uuid);
    //     $stmt_uuid2 = $this->db->prepare($sql_uuid);
    //     $stmt_uuid->execute();
    //     $stmt_uuid2->execute();
    //     $uuid = $stmt_uuid->fetchColumn(0);
    //     $uuid2 = $stmt_uuid2->fetchColumn(0);
    //     // $uploadedFiles = $request->getUploadedFiles();
    //     //handle upload file
    //     $uploadedFile1 = $uploadedFiles1['foto'];
    //     $uploadedFile2 = $uploadedFiles2['ktp'];


    //     if($uploadedFile1->getError()===UPLOAD_ERR_OK){
    //         $exetension1 = pathinfo($uploadedFile1->getClientFilename(),PATHINFO_EXTENSION);
    //         $file_name1 = sprintf('%s.%0.8s', $uuid.$nama, $exetension1);
    //         $directory1 = $this->get('settings')['foto_user'];
    //         $uploadedFile1->moveTo($directory1 . DIRECTORY_SEPARATOR . $file_name1);
    //     }

    //     if($uploadedFile2->getError()===UPLOAD_ERR_OK){
    //         $exetension2 = pathinfo($uploadedFile2->getClientFilename(),PATHINFO_EXTENSION);
    //         $file_name2 = sprintf('%s.%0.8s', $uuid2.$nama, $exetension2);
    //         $directory2 = $this->get('settings')['foto_ktp'];
    //         $uploadedFile2->moveTo($directory2 . DIRECTORY_SEPARATOR . $file_name2);
    //     }

    //     $query = "INSERT INTO tb_user (nama, email, telepon, `password`, foto, foto_ktp) VALUES
    //     (:nama, :email, :telepon , MD5(:password), '$file_name1', '$file_name2')";

    //     $stmt = $this->db->prepare($query);
    //     if($stmt->execute([':nama' => $nama, ':email' => $email, 
    //     ':telepon' => $telepon, ':password' => $password])){
    //         return $response->withJson(["code"=>200, "msg"=>"Register berhasil!"]);
    //     }
    //         return $response->withJson(["code"=>201, "msg"=>"Register gagal!"]);
    // });

    $app->post('/user/register', function ($request, $response) {
        $nama = $request->getParsedBodyParam('nama');
        $email = $request->getParsedBodyParam('email');
        $telepon = $request->getParsedBodyParam('telepon');
        $password = $request->getParsedBodyParam('password');

        $queryTelp = "SELECT * FROM tb_user WHERE telepon = :telepon";

        $queryEmail = "SELECT * FROM tb_user WHERE email = :email";

        $queryInsert = "INSERT INTO tb_user (nama, email, telepon, `password`) VALUES (:nama, :email, :telepon, MD5(:pass))";

        if (empty($telepon) || empty($nama) || empty($email) || empty($password)) {
            return $response->withJson(["code" => 201, "msg" => "Lengkapi Data"]);
        }


        $stmt = $this->db->prepare($queryTelp);
        if ($stmt->execute([':telepon' => $telepon])) {
            $result = $stmt->fetch();
            $row_telepon = $result['telepon'];
            if ($row_telepon <> null) {
                return $response->withJson(["code" => 201, "msg" => "Email atau nomor telepon telah terdaftar!"]);
            }
        }

        $stmt = $this->db->prepare($queryEmail);
        if ($stmt->execute([':email' => $email])) {
            $result = $stmt->fetch();
            $row_telepon = $result['email'];
            if ($row_telepon <> null) {
                return $response->withJson(["code" => 201, "msg" => "Email atau nomor telepon telah terdaftar!"]);
            }
        }

        $stmt = $this->db->prepare($queryInsert);
        if ($stmt->execute([':nama' => $nama, ':email' => $email, ':telepon' => $telepon, ':pass' => $password])) {
            return $response->withJson(["code" => 200, "msg" => "Berhasil terdaftar!"]);
        }
        return $response->withJson(["code" => 201, "msg" => "Gagal terdaftar!"]);
    });


    $app->post('/user/login', function ($request, $response) {
        $email      = $request->getParsedBodyParam('email');
        $password   = $request->getParsedBodyParam('password');
        $token      = hash('sha256', md5(date('Y-m-d H:i:s'), $email));

        if (empty($email) || empty($password)) {
            return $response->withJson(["code" => 201, "msg" => "Lengkapi data!"]);
        }

        $query = "SELECT `user_id`,nama, email, telepon, foto, status_login, token_login, token_firebase
                 FROM tb_user WHERE email = :email AND `password` = MD5(:pass)";

        $queryUpdate = "UPDATE tb_user set status_login = '1', token_login = :token WHERE `user_id` = :id ";

        $stmt = $this->db->prepare($query);
        if ($stmt->execute([':email' => $email, ':pass' => $password])) {
            $result = $stmt->fetch();
            $rowIsLogin = $result['status_login'];
            $rowID      = $result['user_id'];
            if ($result) {
                if ($rowIsLogin === "0") {
                    $stmtLogin = $this->db->prepare($queryUpdate);
                    if ($stmtLogin->execute([':id' => $rowID, ':token' => $token])) {
                        $stmt = $this->db->prepare($query);
                        if ($stmt->execute([':email' => $email, ':pass' => $password])) {
                            $result = $stmt->fetch();
                            $rowIsLogin = $result['status_login'];
                            $rowID      = $result['user_id'];
                            if ($result) {
                                return $response->withJson(["code" => 200, "msg" => "Login berhasil!", "data" => $result]);
                            }
                        }
                    } else {
                        return $response->withJson(["code" => 201, "msg" => "Login gagal update status!"]);
                    }
                } else {
                    return $response->withJson(["code" => 201, "msg" => "Anda telah login diperangkat tertentu!"]);
                }
            } else {
                return $response->withJson(["code" => 201, "msg" => "Email atau password salah!"]);
            }
        }
        return $response->withJson(["code" => 201, "msg" => "Email atau password salah!"]);
    });


    $app->post('/user/update_firebase_token', function ($request, $response) {
        $id             = $request->getParsedBodyParam('id');
        $token_login    = $request->getParsedBodyParam('token_login');
        $token_firebase = $request->getParsedBodyParam('token_firebase');

        if (empty($id) || empty($token_login) || empty($token_firebase)) {
            return $response->withJson(["code" => 201, "msg" => "Lengkapi data!"]);
        }

        $query = "UPDATE tb_user set token_firebase = :firebase WHERE `user_id` = :id AND `token_login` = :token_login";

        $stmt = $this->db->prepare($query);
        if ($stmt->execute([':firebase' => $token_firebase, ':id' => $id, ':token_login' => $token_login])) {
            return $response->withJson(["code" => 200, "msg" => "Update token berhasil!"]);
        }
        return $response->withJson(["code" => 201, "msg" => "Update token gagal!"]);
    });


    $app->post('/user/update_name', function ($request, $response) {
        $id             = $request->getParsedBodyParam('id');
        $token_login    = $request->getParsedBodyParam('token_login');
        $nama           = $request->getParsedBodyParam('nama');
        $password       = $request->getParsedBodyParam('password');

        if (empty($nama) || empty($token_login) || empty($id) || empty($password)) {
            return $response->withJson(["code" => 201, "msg" => "Lengkapi data!"]);
        }
        $querySelect = "SELECT `user_id`, token_login FROM tb_user WHERE `user_id` = :id AND token_login = :token AND `password` = MD5(:pass)";
        $query = "UPDATE tb_user set nama = :nama WHERE `user_id` = :id AND `token_login` = :token_login AND `password` = MD5(:password)";

        $stmt1 = $this->db->prepare($querySelect);
        if ($stmt1->execute([':id' => $id, ':token' => $token_login, ':pass' => $password])) {
            $result = $stmt1->fetch();
            if ($result) {
                $stmt = $this->db->prepare($query);
                if ($stmt->execute([':nama' => $nama, ':id' => $id, ':token_login' => $token_login, ':password' => $password])) {
                    return $response->withJson(["code" => 200, "msg" => "Update nama berhasil!"]);
                }
                return $response->withJson(["code" => 201, "msg" => "Update nama gagal!"]);
            }
            return $response->withJson(["code" => 201, "msg" => "Password salah!"]);
        }
        return $response->withJson(["code" => 201, "msg" => "Update nama gagal!"]);
    });


    $app->post('/user/update_password', function ($request, $response) {
        $id             = $request->getParsedBodyParam('id');
        $token_login    = $request->getParsedBodyParam('token_login');
        $password_lama  = $request->getParsedBodyParam('password');
        $password_baru  = $request->getParsedBodyParam('password_baru');

        if (empty($password_baru) || empty($password_lama) || empty($id) || empty($token_login)) {
            return $response->withJson(["code" => 201, "msg" => "Lengkapi data!"]);
        }
        $querySelect = "SELECT `user_id`, token_login FROM tb_user WHERE `user_id` = :id AND token_login = :token AND `password` = MD5(:pass)";
        $query = "UPDATE tb_user set `password` = MD5(:password_baru) WHERE `user_id` = :id 
                  AND `token_login` = :token_login AND `password` = MD5(:password_lama)";

        $stmt = $this->db->prepare($querySelect);
        if ($stmt->execute([':id' => $id, ':token' => $token_login, ':pass' => $password_lama])) {
            $result = $stmt->fetch();
            if ($result) {
                $stmt1 = $this->db->prepare($query);
                if ($stmt1->execute([
                    ':id' => $id, ':token_login' => $token_login,
                    ':password_lama' => $password_lama, ':password_baru' => $password_baru
                ])) {
                    return $response->withJson(["code" => 200, "msg" => "Update password berhasil!"]);
                }
                return $response->withJson(["code" => 201, "msg" => "Update password gagal!"]);
            }
            return $response->withJson(["code" => 201, "msg" => "Password salah!"]);
        }
        return $response->withJson(["code" => 201, "msg" => "Update password gagal!"]);
    });


    $app->post('/user/update_email', function ($request, $response) {
        $id          = $request->getParsedBodyParam('id');
        $token_login = $request->getParsedBodyParam('token_login');
        $password    = $request->getParsedBodyParam('password');
        $email       = $request->getParsedBodyParam('email');

        $timeParam   = "";
        $timeUpdate  = date('Y-m-d H:i:s', time());


        if (empty($id) || empty($token_login) || empty($password) || empty($email)) {
            return $response->withJson(["code" => 201, "msg" => "Lengkapi Data"]);
        }

        $queryEmail = "SELECT * FROM tb_user WHERE email = :email";
        $query = "SELECT `user_id`, token_login, waktu_update 
        FROM tb_user WHERE `user_id` = :id AND token_login = :token AND `password` = MD5(:pass)";

        $queryUpdate = "UPDATE tb_user SET email = :email, waktu_update = :waktu WHERE `user_id` = :id AND `password` = MD5(:pass) ";

        $stmt = $this->db->prepare($queryEmail);
        if ($stmt->execute([':email' => $email])) {
            $result = $stmt->fetch();
            $row_telepon = $result['email'];
            if ($row_telepon <> null) {
                return $response->withJson(["code" => 201, "msg" => "Email telah terdaftar!"]);
            }
        }

        $stmtUpdate = $this->db->prepare($queryUpdate);
        $stmt = $this->db->prepare($query);
        if ($stmt->execute([':id' => $id, ':token' => $token_login, ":pass" => $password])) {
            $result = $stmt->fetch();
            $rowUpdate = $result['waktu_update'];
            $time = strtotime($rowUpdate);
            $time1 = strtotime($timeUpdate);
            $time2 = date('Y-m-d H:i:s', $time + 2 * 24 * 60 * 60);
            $timeP = strtotime($time2);
            $jml = $timeP - $time1;
            $timeParam = floor($jml / (60 * 60 * 24));
            // return $rowUpdate;
            if ($result) {
                if (empty($rowUpdate)) {
                    if ($stmtUpdate->execute([
                        ':id' => $id, ':email' => $email,
                        ':pass' => $password, ':waktu' => $timeUpdate
                    ])) {
                        return $response->withJson(["code" => 200, "msg" => "Update email berhasil!"]);
                    }
                } else {
                    if ($stmtUpdate->execute([
                        ':id' => $id, ':email' => $email,
                        ':pass' => $password, ':waktu' => $timeUpdate
                    ])) {
                        return $response->withJson(["code" => 200, "msg" => "Update email berhasil!"]);
                    }
                }
                return $response->withJson(["code" => 201, "msg" => "Parameter salah!"]);
            }
            return $response->withJson(["code" => 201, "msg" => "Password salah!"]);
        }
        return $response->withJson(["code" => 201, "msg" => "Parameter salah!"]);
    });


    $app->post('/user/update_telepon', function ($request, $response) {
        $id          = $request->getParsedBodyParam('id');
        $token_login = $request->getParsedBodyParam('token_login');
        $password    = $request->getParsedBodyParam('password');
        $telepon     = $request->getParsedBodyParam('telepon');

        $timeParam   = "";
        $timeUpdate  = date('Y-m-d H:i:s', time());

        // return $timeParam;

        if (empty($id) || empty($token_login) || empty($password) || empty($telepon)) {
            return $response->withJson(["code" => 201, "msg" => "Lengkapi Data"]);
        }

        $queryTelepon = "SELECT * FROM tb_user WHERE telepon = :telepon";
        $query = "SELECT `user_id`, token_login, waktu_update 
        FROM tb_user WHERE `user_id` = :id AND token_login = :token AND `password` = MD5(:pass)";

        $queryUpdate = "UPDATE tb_user SET telepon = :telepon, waktu_update = :waktu WHERE `user_id` = :id AND `password` = MD5(:pass) ";

        $stmt = $this->db->prepare($queryTelepon);
        if ($stmt->execute([':telepon' => $telepon])) {
            $result = $stmt->fetch();
            $row_telepon = $result['telepon'];
            if ($row_telepon <> null) {
                return $response->withJson(["code" => 201, "msg" => "Nomor telepon telah terdaftar!"]);
            }
        }

        $stmtUpdate = $this->db->prepare($queryUpdate);
        $stmt = $this->db->prepare($query);
        if ($stmt->execute([':id' => $id, ':token' => $token_login, ':pass' => $password])) {
            $result = $stmt->fetch();
            $rowUpdate = $result['waktu_update'];
            $time = strtotime($rowUpdate);
            $time1 = strtotime($timeUpdate);
            $time2 = date('Y-m-d H:i:s', $time + 2 * 24 * 60 * 60);
            $timeP = strtotime($time2);
            $jml = $timeP - $time1;
            $timeParam = floor($jml / (60 * 60 * 24));

            // return $rowUpdate;
            if ($result) {
                if (empty($rowUpdate)) {
                    if ($stmtUpdate->execute([
                        ':id' => $id, ':telepon' => $telepon,
                        ':pass' => $password, ':waktu' => $timeUpdate
                    ])) {
                        return $response->withJson(["code" => 200, "msg" => "Update telepon berhasil!"]);
                    }
                } else {
                    if ($stmtUpdate->execute([
                        ':id' => $id, ':telepon' => $telepon,
                        ':pass' => $password, ':waktu' => $timeUpdate
                    ])) {
                        return $response->withJson(["code" => 200, "msg" => "Update telepon berhasil!"]);
                    }
                }
                return $response->withJson(["code" => 201, "msg" => "Parameter salah!"]);
            }
            return $response->withJson(["code" => 201, "msg" => "Password salah!"]);
        }
        return $response->withJson(["code" => 201, "msg" => "Parameter salah!"]);
    });


    $app->post('/user/update_foto', function ($request, $response) {
        $id             = $request->getParsedBodyParam('id');
        $token_login    = $request->getParsedBodyParam('token_login');
        $nama           = "";
        $uploadedFiles  = $request->getUploadedFiles();

        if (empty($id) || empty($token_login)) {
            return $response->withJson(["code" => 201, "msg" => "Lengkapi data!"]);
        }

        $queryCheck = "SELECT foto, nama FROM tb_user WHERE `user_id` = :id AND token_login = :token";
        $stmt = $this->db->prepare($queryCheck);
        if ($stmt->execute([':id' => $id, ':token' => $token_login])) {
            $result     = $stmt->fetch();
            $rowFoto    = $result['foto'];
            $nama       = $result['nama'];
            if ($rowFoto <> null) {
                $directory = $this->get('settings')['upload_customer'];
                unlink($directory . '/' . $rowFoto);
            }
        }

        $sql_uuid = "SELECT UUID() as uuid";
        $stmt_uuid = $this->db->prepare($sql_uuid);
        $stmt_uuid->execute();
        $uuid = $stmt_uuid->fetchColumn(0);

        $uploadedFile = $uploadedFiles['foto'];

        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            $exetension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
            $file_name = sprintf('%s.%0.8s', $uuid . $nama, $exetension);
            $directory = $this->get('settings')['upload_customer'];
            $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $file_name);

            $sql = "UPDATE tb_user set foto= :foto WHERE `user_id` = :id AND token_login = :token_login";
        }

        $stmt = $this->db->prepare($sql);
        if ($stmt->execute([':id' => $id, ':foto' => $file_name, ':token_login' => $token_login])) {
            return $response->withJson(["code" => 200, "msg" => "Foto berhasil di update!", "foto" => $file_name]);
        }
        return $response->withJson(["code" => 201, "msg" => "Foto gagal di update!"]);
    });


    $app->post('/user/hapus_foto', function ($request, $response) {
        $id             = $request->getParsedBodyParam('id');
        $token_login    = $request->getParsedBodyParam('token_login');

        if (empty($id) || empty($token_login)) {
            return $response->withJson(["code" => 201, "msg" => "Lengkapi data!"]);
        }

        $query = "UPDATE tb_user SET foto = '' WHERE `user_id` = :id AND token_login = :token";
        $queryCheck = "SELECT foto FROM tb_user WHERE `user_id` = :id AND token_login = :token";
        $stmt = $this->db->prepare($queryCheck);
        if ($stmt->execute([':id' => $id, ':token' => $token_login])) {
            $result     = $stmt->fetch();
            $rowFoto    = $result['foto'];
            if ($rowFoto <> null) {
                $directory = $this->get('settings')['upload_customer'];
                unlink($directory . '/' . $rowFoto);
                $stmt = $this->db->prepare($query);
                if ($stmt->execute([':id' => $id, ':token' => $token_login])) {
                    return $response->withJson(["code" => 200, "msg" => "Foto berhasil di hapus!"]);
                }
                return $response->withJson(["code" => 201, "msg" => "Foto gagal di hapus!"]);
            }
            return $response->withJson(["code" => 201, "msg" => "Foto kosong!"]);
        }
    });


    $app->post('/user/logout_user', function ($request, $response) {
        $id             = $request->getParsedBodyParam('id');
        $token_login    = $request->getParsedBodyParam('token');

        $queryCheck = "SELECT * FROM tb_user WHERE `user_id` = :id AND `token_login` = :token AND status_login = '1'";
        $query = "UPDATE tb_user set status_login = '0', token_firebase = '' WHERE `user_id` = :id AND `token_login` = :token AND status_login = '1'";

        $stmt1 = $this->db->prepare($queryCheck);
        if ($stmt1->execute([':id' => $id, ':token' => $token_login])) {
            $result = $stmt1->fetch();
            if ($result) {
                $stmt = $this->db->prepare($query);
                if ($stmt->execute([':id' => $id, ':token' => $token_login])) {
                    return $response->withJson(["code" => 200, "msg" => "Logout berhasil!"]);
                }
                return $response->withJson(["code" => 201, "msg" => "Logout gagal!"]);
            }
            return $response->withJson(["code" => 201, "msg" => "Logout gagal1!"]);
        }
        return $response->withJson(["code" => 201, "msg" => "Logout gagal!"]);
    });


    $app->post('/user/getListMobil', function ($request, $response) {
        $id = $request->getParsedBodyParam('id');
        $token = $request->getParsedBodyParam('token');
        $jenis = $request->getParsedBodyParam('jenis');
        $nama = $request->getParsedBodyParam('nama');
        $alamat_id = $request->getParsedBodyParam('alamat_id');

        if (empty($id) || empty($token) || empty($jenis) || empty($alamat_id)) {
            return $response->withJson(["code" => 201, "msg" => "Lengkapi data!"]);
        }

        $queryCheck = "SELECT * FROM tb_user WHERE `user_id` = :id AND token_login = :token ";
        $querySelect = "SELECT * FROM tb_mobil WHERE jenis = :tipe AND alamat_id = :alamat AND nama LIKE '%$nama%' ORDER BY `status` ASC";

        $stmt = $this->db->prepare($queryCheck);
        if ($stmt->execute([':id' => $id, ':token' => $token])) {
            $result = $stmt->fetch();
            if ($result) {
                $stmt = $this->db->prepare($querySelect);
                if ($stmt->execute([':tipe' => $jenis, ':alamat' => $alamat_id])) {
                    $result = $stmt->fetchAll();
                    if ($result) {
                        return $response->withJson(["code" => 200, "msg" => "Berhasil mendapatkan data!", "data" => $result]);
                    }
                    return $response->withJson(["code" => 201, "msg" => "Gagal mendapatkan data!1"]);
                }
                return $response->withJson(["code" => 201, "msg" => "Gagal mendapatkan data!2"]);
            }
            return $response->withJson(["code" => 201, "msg" => "Gagal mendapatkan data!3"]);
        }
        return $response->withJson(["code" => 201, "msg" => "Gagal mendapatkan data!4"]);
    });


    $app->post('/user/getAlamat', function ($request, $response) {
        $id = $request->getParsedBodyParam('id');
        $token = $request->getParsedBodyParam('token');


        if (empty($id) || empty($token)) {
            return $response->withJson(["code" => 201, "msg" => "Lengkapi data!"]);
        }

        $queryCheck = "SELECT * FROM tb_user WHERE `user_id` = :id AND token_login = :token ";
        $querySelect = "SELECT * FROM tb_alamat";

        $stmt = $this->db->prepare($queryCheck);
        if ($stmt->execute([':id' => $id, ':token' => $token])) {
            $result = $stmt->fetch();
            if ($result) {
                $stmt = $this->db->prepare($querySelect);
                if ($stmt->execute()) {
                    $result = $stmt->fetchAll();
                    if ($result) {
                        return $response->withJson(["code" => 200, "msg" => "Berhasil mendapatkan data!", "data" => $result]);
                    }
                    return $response->withJson(["code" => 201, "msg" => "Gagal mendapatkan data!"]);
                }
                return $response->withJson(["code" => 201, "msg" => "Gagal mendapatkan data!"]);
            }
            return $response->withJson(["code" => 201, "msg" => "Gagal mendapatkan data!"]);
        }
        return $response->withJson(["code" => 201, "msg" => "Gagal mendapatkan data!"]);
    });


    $app->post('/user/make_orderan', function ($request, $response) {
        $id = $request->getParsedBodyParam('id');
        $token = $request->getParsedBodyParam('token');
        $mobil_id = $request->getParsedBodyParam('mobil_id');
        $s_Date = $request->getParsedBodyParam('start_date');
        $e_Date = $request->getParsedBodyParam('end_date');
        $metodePembayaran = $request->getParsedBodyParam('metode');
        $jenisOrder = $request->getParsedBodyParam('jenis');
        $harga = $request->getParsedBodyParam('harga');
        $thisdate       = date('Y-m-d H:i:s', time());


        if (empty($id) || empty($token) || empty($mobil_id) || empty($s_Date) || empty($e_Date) || empty($metodePembayaran) || empty($harga) || empty($jenisOrder)) {
            return $response->withJson(["code" => 201, "msg" => "Lengkapi data!"]);
        }

        $startDate = date('Y-m-d H:i:s', strtotime($s_Date));
        $endDate   = date('Y-m-d H:i:s', strtotime($e_Date));


        $queryCheck = "SELECT * FROM tb_user WHERE `user_id` = :id AND token_login = :token ";
        $queryMobil = "SELECT `status`, mobil_id FROM tb_mobil WHERE mobil_id = :m_id AND `status` = '1'";
        $queryUpdateMobil = "UPDATE tb_mobil SET `status` = '1' WHERE mobil_id = :mobil_id";
        $queryCheckTrans = "SELECT `status`, `user_id` FROM tb_transaksi WHERE `user_id` = :id AND `status` = '0'";
        $queryInsertTrans = "INSERT INTO tb_transaksi (`order_id`, `user_id`, metode_pembayaran, `harga`, `create_date`) VALUES (:o_id, :u_id, :metode, :harga, :create_date)";
        $queryInsert = "INSERT INTO tb_order (`user_id`, `mobil_id`, `jenis_order`, `order_date`, `start_date`, `end_date`) VALUES
        (:u_id, :mobil_id, :jenis, :o_date, :s_date, :e_date)";
        $querySelectOrder = "SELECT `user_id`, max(order_id) AS id FROM tb_order WHERE `user_id` = :u_id";

        $stmt1 = $this->db->prepare($queryMobil);
        if ($stmt1->execute([':m_id' => $mobil_id])) {
            $result = $stmt1->fetch();
            if ($result) {
                return $response->withJson(["code" => 201, "msg" => "Mobil sedang dipakai!"]);
            }
        }

        $stmt2 = $this->db->prepare($queryCheckTrans);
        if ($stmt2->execute([':id' => $id])) {
            $result = $stmt2->fetch();
            if ($result) {
                return $response->withJson(["code" => 201, "msg" => "Ada pembayaran yang belum lunas!"]);
            }
        }



        $stmt = $this->db->prepare($queryCheck);
        if ($stmt->execute([':id' => $id, ':token' => $token])) {
            $result = $stmt->fetch();
            if ($result) {
                $stmt = $this->db->prepare($queryInsert);
                if ($stmt->execute([':u_id' => $id, ':mobil_id' => $mobil_id, ':jenis' => $jenisOrder, ':o_date' => $thisdate, ':s_date' => $startDate, ':e_date' => $endDate])) {
                    $stmt = $this->db->prepare($queryUpdateMobil);
                    if ($stmt->execute([':mobil_id' => $mobil_id])) {
                        $stmt = $this->db->prepare($querySelectOrder);
                        if ($stmt->execute([':u_id' => $id])) {
                            $result = $stmt->fetch();
                            $order_id = $result['id'];
                            if ($result) {
                                $stmt = $this->db->prepare($queryInsertTrans);
                                if ($stmt->execute([':o_id' => $order_id, ':u_id' => $id, ':metode' => $metodePembayaran, ':harga' => $harga, ':create_date' => $thisdate])) {
                                    return $response->withJson(["code" => 200, "msg" => "Berhasil dipesan!"]);
                                }
                                return $response->withJson(["code" => 200, "msg" => "Insert transaksi!"]);
                            }
                            return $response->withJson(["code" => 200, "msg" => "Gagal mendapatkan id order!"]);
                        }
                        return $response->withJson(["code" => 200, "msg" => "Gagal update status mobil!"]);
                    }
                    return $response->withJson(["code" => 201, "msg" => "Gagal update status mobil!"]);
                }
                return $response->withJson(["code" => 201, "msg" => "Gagal insert order!"]);
            }
            return $response->withJson(["code" => 201, "msg" => "Token tidak berlaku!"]);
        }
        return $response->withJson(["code" => 201, "msg" => "Token tidak berlaku!"]);
    });


    $app->post('/user/cancel_order', function ($request, $response) {
        $id = $request->getParsedBodyParam('id');
        $token = $request->getParsedBodyParam('token');
        $id_order = $request->getParsedBodyParam('order_id');


        $queryCheck = "SELECT * FROM tb_user WHERE `user_id` = :id AND token_login = :token ";
        $querySelect = "SELECT
                        tb_order.order_id,
                        tb_order.`status`,
                        tb_mobil.`status`,
                        tb_transaksi.`status`
                        FROM
                        tb_order
                        INNER JOIN tb_mobil ON tb_order.mobil_id = tb_mobil.mobil_id
                        INNER JOIN tb_transaksi ON tb_order.order_id = tb_transaksi.order_id
                        WHERE tb_order.order_id = :order_id AND  tb_transaksi.`status` = '0' AND  tb_mobil.`status` = '1' AND  tb_order.`status` = '0' ";

        $queryUpdate = "UPDATE
                        tb_order
                        INNER JOIN tb_mobil ON tb_order.mobil_id = tb_mobil.mobil_id
                        INNER JOIN tb_transaksi ON tb_order.order_id = tb_transaksi.order_id
                        SET
                        tb_order.`status` = '3',
                        tb_mobil.`status` = '0',
                        tb_transaksi.`status` = '2'
                        WHERE tb_order.order_id = :order_id ";

        $stmt = $this->db->prepare($queryCheck);
        if ($stmt->execute([':id' => $id, ':token' => $token])) {
            $result = $stmt->fetch();
            if ($result) {
                $stmt = $this->db->prepare($querySelect);
                if ($stmt->execute([':order_id' => $id_order])) {
                    $result = $stmt->fetch();
                    if ($result) {
                        $stmt = $this->db->prepare($queryUpdate);
                        if ($stmt->execute([':order_id' => $id_order])) {
                            return $response->withJson(["code" => 200, "msg" => "Pesanan dibatalkan!"]);
                        }
                        return $response->withJson(["code" => 201, "msg" => "Pesanan gagal dibatalkan1!"]);
                    }
                    return $response->withJson(["code" => 201, "msg" => "Pesanan gagal dibatalkan!"]);
                }
                return $response->withJson(["code" => 201, "msg" => "Pesanan gagal dibatalkan!"]);
            }
            return $response->withJson(["code" => 201, "msg" => "Token atau id tidak ditemukan!"]);
        }
        return $response->withJson(["code" => 201, "msg" => "Token atau id tidak ditemukan!"]);
    });


    $app->post('/user/getListOrderan', function ($request, $response) {
        $id = $request->getParsedBodyParam('id');
        $token = $request->getParsedBodyParam('token');

        $queryCheck = "SELECT * FROM tb_user WHERE `user_id` = :id AND token_login = :token ";
        $querySelectOrder = "SELECT
                            tb_order.`status` as status_order,
                            tb_order.end_date,
                            tb_order.start_date,
                            tb_order.order_date,
                            tb_transaksi.harga,
                            tb_order.jenis_order,
                            tb_order.user_id,
                            tb_order.order_id,
                            tb_mobil.nama,
                            tb_mobil.foto,
                            tb_mobil.tipe,
                            tb_transaksi.metode_pembayaran,
                            tb_transaksi.`status`,
                            tb_mobil.mobil_id
                            FROM
                            tb_order
                            INNER JOIN tb_transaksi ON tb_order.order_id = tb_transaksi.order_id
                            INNER JOIN tb_mobil ON tb_order.mobil_id = tb_mobil.mobil_id
                            WHERE tb_order.`user_id` = :id AND tb_order.`status` <= 1 ORDER BY tb_order.order_date DESC";


        $stmt = $this->db->prepare($queryCheck);
        if ($stmt->execute([':id' => $id, ':token' => $token])) {
            $result = $stmt->fetch();
            if ($result) {
                $stmt = $this->db->prepare($querySelectOrder);
                if ($stmt->execute([':id' => $id])) {
                    $result = $stmt->fetchAll();
                    if ($result) {
                        return $response->withJson(["code" => 200, "msg" => "Berhasil mendapatkan data!", "data" => $result]);
                    }
                    return $response->withJson(["code" => 201, "msg" => "Data kosong!"]);
                }
                return $response->withJson(["code" => 201, "msg" => "Data kosong!"]);
            }
            return $response->withJson(["code" => 201, "msg" => "Input salah!"]);
        }
        return $response->withJson(["code" => 201, "msg" => "Input salah!"]);
    });
};
