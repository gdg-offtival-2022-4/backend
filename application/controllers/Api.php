<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{

    public function index()
    {
        echo "api index page";
    }

    public function image()
    {
            $file = $_FILES['image_url'];
            $allowed_extensions = explode(',', "png,jpg,jpeg");
            $max_file_size = 5242880;
            $ext = substr($file['name'], strrpos($file['name'], '.') + 1); // 파일 확장자 반환

            // 확장자 체크
            if (!in_array($ext, $allowed_extensions)) {
                echo '업로드할 수 없는 확장자입니다.';
                exit();
            }

            // 파일 크기 체크
            if ($file['size'] >= $max_file_size) {
                echo '5MB 까지만 업로드 가능합니다.';
                exit();
            }

            $timeNow = date("Y-m-d"); // 폴더명으로 사용할 현재 날짜
            $dir = "./upload/$timeNow"; // 폴더 위치, 이름
            $upload_dir = $dir . '/'; // 업로드 파일 저장 위치

            // 현재 날짜를 이름으로 가진 폴더 없을경우 생성
            if (is_dir($dir) != true) {
                mkdir($dir, 0777, true);
            }

            $fileName = $this->uuidgen(); // 업로드될 파일 명
            $path = $fileName . '.' . $ext;

            $base_url = "http://ec2-13-209-15-60.ap-northeast-2.compute.amazonaws.com";
            $full_url = $base_url . substr($upload_dir, 1) . $path;

            if (move_uploaded_file($file['tmp_name'], $upload_dir . $path)) {
                return $full_url;
            }
    }

    private function uuidgen()
    {
        return sprintf('%08x-%04x-%04x-%04x-%04x%08x',
            mt_rand(0, 0xffffffff),
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff), mt_rand(0, 0xffffffff)
        );
    }

    public function login()
    {
        $this->load->model('all_model');

        $login_member = json_decode($this->input->raw_input_stream, true);

        $user_info = $this->all_model->validate_login($login_member);

        if ($user_info['id'] == $login_member['id'] && $user_info['pw'] == $login_member['pw']) {
            $result = array(
                "user_id" => $user_info["user_id"]
            );
            echo json_encode($result);
        } else {
            echo "fail";
        }
    }

    public function signup()
    {
        $this->load->model('all_model');

        $image_url = $_FILES['image_url'];
        $id = $this->input->post("id");
        $pw = $this->input->post("pw");
        $nickname = $this->input->post("nickname");

        $id = str_replace('"', '', $id);
        $pw = str_replace('"', '', $pw);
        $nickname = str_replace('"', '', $nickname);

        $member_signup = array(
            "id" => $id,
            "pw" => $pw,
            "nickname" => $nickname,
            "image_url" => "https://i.imgur.com/oMdVph0.jpeg"
        );


//        $member_signup = json_decode($this->input->raw_input_stream, true);

        $user_id = $this->all_model->create_user($member_signup);

        $arr = array(
            "user_id" => strval($user_id)
        );

        echo json_encode($arr);
    }

    public function room()
    {
        $this->load->model('all_model');

        $room = json_decode($this->input->raw_input_stream, true);

        $room_id = $this->all_model->create_room($room);

        $arr = array(
            "room_id" => $room_id
        );

        echo json_encode($arr);
    }

    public function main()
    {
        $this->load->model('all_model');

        $main_posts = $this->all_model->get_main_posts();


        for ($i = 0; $i < count($main_posts); $i++) {
            $profile_images = $this->all_model->get_profile_images($main_posts[$i]['room_id']);
            $images = array(
                $profile_images[0]['image_url'], $profile_images[1]['image_url'], $profile_images[2]['image_url']
            );
            $main_posts[$i]['user_image_urls'] = $images;
        }

        echo json_encode($main_posts);
    }

    public function room_join()
    {
        $this->load->model('all_model');

        $room_join = json_decode($this->input->raw_input_stream, true);

        $this->all_model->join_room($room_join);

        echo "success";
    }

    public function room_rank()
    {
        $this->load->model('all_model');

        $room_id = $this->input->get("room_id");

        $room_rank = $this->all_model->get_room_rank($room_id);

        for ($i = 0; $i < count($room_rank); $i++) {
            $user_info = $this->all_model->get_user_info($room_rank[$i]['user_id']);
            $room_rank[$i]['user_image_url'] = $user_info['image_url'];
            $room_rank[$i]['nickname'] = $user_info['nickname'];
            $room_rank[$i]['rank'] = $i + 1;
        }

        echo json_encode($room_rank);
    }

    public function room_rank_detail()
    {
        $this->load->model('all_model');

        $user_id = $this->input->get("user_id");
        $room_id = $this->input->get("room_id");

//        $this->image();

        $user_info = $this->all_model->get_user_info($user_id);
        $user_info_sub = $this->room_rank_detail_sub($user_id, $room_id);

        $user_info['rank'] = $user_info_sub['rank'];
        $user_info['point'] = $user_info_sub['point'];

        $posts = $this->all_model->get_posts_by_user_id($user_id);

        $result = array(
            "user" => $user_info,
            "posts" => $posts
        );
        echo json_encode($result);
    }

    private function room_rank_detail_sub($user_id, $room_id)
    {
        $this->load->model('all_model');

        $room_rank = $this->all_model->get_room_rank($room_id);

        for ($i = 0; $i < count($room_rank); $i++) {
            $user_info = $this->all_model->get_user_info($room_rank[$i]['user_id']);
            $room_rank[$i]['user_image_url'] = $user_info['image_url'];
            $room_rank[$i]['nickname'] = $user_info['nickname'];
            $room_rank[$i]['rank'] = $i + 1;
        }

        for ($i = 0; $i < count($room_rank); $i++) {
            if ($room_rank[$i]['user_id'] == $user_id) {
                $rank = $room_rank[$i]['rank'];
                $point = $room_rank[$i]['point'];

                return array(
                    'rank' => $rank,
                    'point' => $point
                );
            }
        }
    }

    public function post()
    {
        $this->load->model('all_model');

        $user_id = $this->input->post("user_id");
        $room_id = $this->input->post("room_id");
        $image_url = $this->image();

        if ($image_url == null) {
            echo $image_url;
        }

        $arr = array(
            'user_id' => str_replace('"', '', $user_id),
            'room_id' => str_replace('"', '', $room_id),
            'image_url' => $image_url
        );

        $post_id = $this->all_model->create_post($arr);

        echo json_encode(array('post_id' => strval($post_id)));
    }

    public function room_post()
    {
        $this->load->model('all_model');

        $room_id = $this->input->get("room_id");

        $posts = $this->all_model->get_posts_by_room_id($room_id);

        $rtn = array(
            "data" => $posts
        );

        echo json_encode($rtn);

    }

    public function room_post_detail()
    {
        $this->load->model('all_model');

        $room_id = $this->input->get("room_id");
        $post_id = $this->input->get("post_id");

        $user_id = $this->all_model->get_user_id_by_post_id($post_id);
        $point = $this->all_model->get_user_point_by_room_id_and_user_id($room_id, $user_id['owned_user_id']);
        $user = $this->all_model->get_user_info_for_post_detail($user_id['owned_user_id']);
        $user['point'] = $point['point'];
        $post_info = $this->all_model->get_post_info_by_post_id($post_id);

        $result = array(
            "user" => $user,
            "post_image_url" => $post_info['post_image_url'],
            "created_date" => $post_info['created_date'],
            "status" => $post_info['status'],
            "up" => $post_info['up'],
            "down" => $post_info['down']
        );

        echo json_encode($result);
    }

    public function room_post_detail_up_down()
    {
        $this->load->model('all_model');

        $arr = json_decode($this->input->raw_input_stream, true);

        $result = $this->all_model->update_post_up_and_down($arr);

        echo json_encode($result);
    }

    public function room_info()
    {
        $this->load->model('all_model');

        $room_id = $this->input->get("room_id");

        $attr = $this->all_model->get_room_info($room_id);

        echo json_encode($attr);
    }

}