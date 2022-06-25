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
        $image_url = $_FILES['image_url'];

        $result = array(
            "image_url" => "https://i.imgur.com/oMdVph0.jpeg"
        );

        echo json_encode($result);
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

        $temp_image_urls = array(
          "https://mblogthumb-phinf.pstatic.net/MjAxOTA4MTdfMTc5/MDAxNTY2MDA3ODMwMDQ2.rJge1pGaPjaNLIAfDlcqT29JE7_eSsaBzf1l8oGTPTQg.2XKoYzxuCcpBR33UchGAn_GLJmi-699tPurA0vue_mQg.JPEG.yamasa_studio/야탑역사진관야탑사진관증명사진0171.jpg?type=w800",
            "https://img.hankyung.com/photo/201904/01.19372617.1.jpg",
            "https://dispatch.cdnser.be/wp-content/uploads/2017/02/8f928ac94dabf0f77af2f7f53a240253.jpg"
        );

        for ($i = 0; $i < count($main_posts); $i++) {
            $main_posts[$i]['user_image_urls'] = $temp_image_urls;
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

        $image_url = $_FILES['image_url'];
        $user_id = $this->input->post("user_id");
        $room_id = $this->input->post("room_id");

        $arr = array(
            'user_id' => $user_id,
            'room_id' => $room_id
        );

        $post_id = $this->all_model->create_post($arr);

        echo json_encode(strval($post_id));
    }

}