<?php
require_once "logindb.php";
$location = "../images/";
$up_location = "../uploads/";
if (isset($_POST['method'])) {
    switch ($_POST['method']) {
        case "user_auth":

            $data = json_decode($_POST['data']);
            $uid = $data->uid;
            $query = "SELECT * FROM reg_users WHERE uid = " . $uid; // Ишем есть ли такой пользователь
            if (count(convertResult($query)) == 0) { // если ответ нулевой, то делаем запись
                $name = $data->first_name;

                $querySet = "INSERT INTO `reg_users` (`id`, `uid`, `email`, `name`, `rank`, `registered`, `last_login`) VALUES (NULL, " . $uid . ", 'info@gmail.com', '" . $name . "', '0', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);";
                convertResult($querySet);
            } else { //
                $queryUpd = "UPDATE `reg_users` SET `last_login` = CURRENT_TIMESTAMP WHERE `reg_users`.`uid` = " . $uid;
                convertResult($queryUpd);
            }

            $queryResponse = "SELECT * FROM reg_users WHERE uid = " . $uid;
            $res = convertResult($queryResponse);

            $result_array["success"] = true;
            $result_array["data"] = $res;
            break;
        case "url_pic":
            $url = mysql_entities_fix_string($_POST['url']);

//            $Imagick = new \Imagick($url);
//            $imagick_type_format = $Imagick->getImageMimeType();

//            $pattern = "/image/";
//            $regexpResult = preg_match($pattern, $imagick_type_format);

//            if($imagick_type_format == )

//            $blob = $Imagick->getImageBlob();
//            $image_base64 = base64_encode($blob);
            $finfo = finfo_open(FILEINFO_MIME_TYPE);

            $result_array["success"] = true;
            $result_array["type"] = finfo_file($finfo, $url);
            finfo_close($finfo);
//            $result_array["blob"] = $image_base64;

            break;
        case "save":
            $autor = mysql_entities_fix_string($_POST['user']);
            $txt = mysql_entities_fix_string($_POST['txt']);

            $query = "SELECT * FROM pics WHERE text = '$txt'"; // Запрос на поиск такой же записи
            $origin = convertResult($query);

            if (count($origin) == 0) { // если ответ нулевой, то делаем запись
                $img = $_POST['img'];
                $name = uniqid();

                $pattern = "/scale\((.*)\)/";
                preg_match($pattern, $img, $matches, PREG_OFFSET_CAPTURE);
                $scale = explode(" ", $matches[1][0]);

                $svg_blob = $img;
                $svg_blob = preg_replace_callback('/<image xlink:href="([^"]+)"/i', function($match) use ($scale) {
                    $intScale = (float)$scale[0];
                    $path_to_image = $match[1];

                    $Imagick = new \Imagick($path_to_image);

                    $Imagick->scaleimage(
                        $Imagick->getImageWidth() * $intScale,
                        $Imagick->getImageHeight() * $intScale
                    );

                    $imagick_type_format = $Imagick->getImageMimeType();
                    $blob = $Imagick->getImageBlob();
                    $image_base64 = base64_encode($blob);
                    return "<image xlink:href=\"data:$imagick_type_format;base64,$image_base64\"";
                }, $svg_blob);

                $file = $location . $name . '.svg';
//                $result_array["$file"] = $file;
                $fp = fopen($file, "w");
                // записываем в файл текст
                $success = fwrite($fp, $svg_blob);
                // закрываем
                fclose($fp);


                if ($success) {
                    if ($result = $mysqli->query("INSERT INTO pics VALUES(NULL, '$name', '$txt', '$autor', CURRENT_TIMESTAMP )")) {
                        $query_to_id_pic = "SELECT * FROM pics WHERE text = '$txt'"; // Запрос на поиск такой же записи
                        $origin = convertResult($query_to_id_pic);

                        $result_array["success"] = true;
                        $result_array["msg"] = "Всё гуд, картинка сохранилась";
                        $result_array["id"] = $origin[0]['id'];
                    } else {
                        $result_array["success"] = false;
                        $result_array["data"] = "В базу не прошло";
                    }
                } else {
                    $result_array["success"] = false;
                    $result_array["data"] = "Файл не записался.";
                }
            } else {
                $result_array["success"] = false;
                $result_array["origin"] = $origin[0]['id'];
                $result_array["data"] = "Такой текст уже писали, вы не оригинальны, извините";
            }


            break;
        case "like":
            $user = mysql_entities_fix_string($_POST['user']);
            $picId = mysql_entities_fix_string($_POST['id']);
            $query = "SELECT * FROM likes WHERE id_users = '$user' AND id_pics = $picId"; // Запрос на поиск лайка

            if (count(convertResult($query)) == 0) { // если ответ нулевой, то делаем запись
                $querySet = "INSERT INTO `likes` (`id`, `id_users`, `id_pics`) VALUES (NULL, '$user', $picId) ";
                convertResult($querySet);
            } else { // если лайк уже стоит, то удаляем его
                $queryDel = "DELETE FROM likes WHERE id_users = '$user' AND id_pics = $picId";
                convertResult($queryDel);
            }

            $queryRepeat = "SELECT * FROM likes WHERE id_pics = $picId"; // Запрос на поиск лайка

            $result_array["success"] = true;
            $result_array["data"] = convertResult($queryRepeat);
            break;
        case "show_content":

            $picture = mysql_entities_fix_string($_POST['picture']);
            $profile = mysql_entities_fix_string($_POST['profile']);
            $page = mysql_entities_fix_string($_POST['page']);
            $limit = 10;
            $offset = $page * $limit;
            $query = 'SELECT pics.id AS picId,
							image,
							text,
							id_users,
							name,
							time,
							reg_users.email AS mail
							FROM pics
							JOIN reg_users
							ON pics.id_users = reg_users.id';

            if($profile){
                $query .= ' WHERE id_users = '. $profile;
            };
            if($picture){
                $query .= ' WHERE pics.id = '. $picture;
            }
            $query .= ' ORDER BY time DESC';
            $query .= ' LIMIT ' . $offset . ', '.$limit;

            $result = $mysqli->query($query) or die('Запрос не удался1: ' . mysqli_error() . PHP_EOL);
            $result_list = [];

            while($r = mysqli_fetch_assoc($result)) {


                $queryLikes = "SELECT * FROM likes WHERE id_pics = " . $r['picId'];
                $result1 = $mysqli->query($queryLikes);
                $r['likes'] = [];
                while($r1 = mysqli_fetch_assoc($result1)) {
                    $r['likes'][] = $r1;
                }
                $result1->close();

                $result_list[] = $r;

            }

            $result_array["success"] = true;
            $result_array["data"] = $result_list;


            break;
        case "show_upload":
            $files1 = scandir($up_location);
            unset ($files1[0]);
            unset ($files1[1]);


            foreach ($files1 as $value) {
                if (substr($value, -4) == ".png" || substr($value, -4) == ".jpg") {
                    $result_list[] = $value;
                }
            }
            $result_array["success"] = "true";
            $result_array["data"] = $result_list;
            break;
        default:
            $result_array["success"] = false;
            $result_array["data"] = "Мы не в курсе что делать. Идите нахуй";
            break;
    }
} else {
    $result_array["success"] = false;
    $result_array["data"] = "Нет пост запроса";
    $result_array["post"] = $_POST;
}


$result = json_encode($result_array);
echo $result;
mysqli_close($mysqli);



function convertResult($query)
{
    global $mysqli;

    $result = $mysqli->query($query) or die('Запрос не удался2: ' . mysqli_error() . ' ' . $query);

    $resultArray = array();
    while ($r = mysqli_fetch_assoc($result)) {
        $resultArray[] = $r;
    }
    return $resultArray;
}


function mysql_entities_fix_string($string)
{
    return htmlentities(mysql_fix_string($string));
}

function mysql_fix_string($string)
{
    global $mysqli;
    if (get_magic_quotes_gpc()) $string = stripslashes($string);
    return $mysqli->real_escape_string($string);
}

function download_image1($image_url, $image_file){
    $fp = fopen ($image_file, 'w+');              // open file handle

    $ch = curl_init($image_url);
    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // enable if you want
    curl_setopt($ch, CURLOPT_FILE, $fp);          // output to file
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 1000);      // some large value to allow curl to run for a long time
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    // curl_setopt($ch, CURLOPT_VERBOSE, true);   // Enable this line to see debug prints
    curl_exec($ch);

    curl_close($ch);                              // closing curl handle
    fclose($fp);                                  // closing file handle
}
function save_image($img,$fullpath){
    $ch = curl_init ($img);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
    $rawdata=curl_exec($ch);
    curl_close ($ch);
    if(file_exists($fullpath)){
        unlink($fullpath);
    }
    $fp = fopen($fullpath,'x');
    fwrite($fp, $rawdata);
    fclose($fp);
}
?>
