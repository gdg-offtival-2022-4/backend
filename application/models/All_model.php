<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class All_model extends CI_Model
{
    public function create_user($attr = array())
    {
        extract($attr);

        $this->db->set('id', $id);
        $this->db->set('pw', $pw);
        $this->db->set('nickname', $nickname);
        $this->db->set('image_url', $image_url);
        $this->db->insert('member');

        return $this->db->insert_id();
    }

    public function validate_login($attr = array())
    {
        extract($attr);

        $this->db->select('*');
        $this->db->from('member');
        $this->db->where('id', $id);
        $this->db->where('pw', $pw);
        $this->db->limit(1);

        $rtn = $this->db->get()->row_array();
        return $rtn;
    }

    public function create_room($attr = array())
    {
        extract($attr);

        $this->db->set('title', $title);
        $this->db->set('category', $category);
        $this->db->set('content', $content);
        $this->db->insert('room');

        return $this->db->insert_id();
    }

    public function get_main_posts()
    {
        $this->db->select('*');
        $this->db->from('room');

        $rtn = $this->db->get()->result_array();
        return $rtn;
    }


    public function join_room($attr = array())
    {
        extract($attr);

        $this->db->set('user_id', $user_id);
        $this->db->set('room_id', $room_id);
        $this->db->set('participating', true);
        $this->db->insert('room_p');

        return true;
    }

    public function get_room_rank($room_id)
    {
        $this->db->select('*');
        $this->db->from('room_p');
        $this->db->where('room_id', $room_id);
        $this->db->order_by('point', 'DESC');

        $rtn = $this->db->get()->result_array();
        return $rtn;
    }

    public function get_user_info($user_id)
    {
        $this->db->select('*');
        $this->db->from('member');
        $this->db->where('user_id', $user_id);

        $rtn = $this->db->get()->row_array();
        return $rtn;
    }

    public function get_posts_by_user_id($user_id)
    {
        $this->db->select('post_id, post_image_url, status');
        $this->db->from('post');
        $this->db->where('owned_user_id', $user_id);

        $rtn = $this->db->get()->result_array();
        return $rtn;
    }

    public function create_post($attr=Array())
    {
        extract($attr);

        $this->db->set('owned_user_id', $user_id);
        $this->db->set('room_id', $room_id);
        $this->db->set('post_image_url', 'https://pbs.twimg.com/media/EA9UJBjU4AAdkCm?format=jpg&name=medium');
        $this->db->set('created_date', 'NOW()', false);
        $this->db->insert('post');

        return $this->db->insert_id();
    }

    public function get_posts_by_room_id($room_id)
    {
        $this->db->select('post_id, post_image_url');
        $this->db->from('post');
        $this->db->where('room_id', $room_id);

        $rtn = $this->db->get()->result_array();
        return $rtn;
    }

    public function get_user_id_by_post_id($post_id)
    {
        $this->db->select('owned_user_id');
        $this->db->from('post');
        $this->db->where('post_id', $post_id);

        $rtn = $this->db->get()->row_array();
        return $rtn;
    }

    public function get_user_point_by_room_id_and_user_id($room_id, $user_id)
    {
        $this->db->select('point');
        $this->db->from('room_p');
        $this->db->where('user_id', $user_id);
        $this->db->where('room_id', $room_id);

        $rtn = $this->db->get()->row_array();
        return $rtn;
    }

    public function get_user_info_for_post_detail($user_id)
    {
        $this->db->select('nickname, image_url');
        $this->db->from('member');
        $this->db->where('user_id', $user_id);

        $rtn = $this->db->get()->row_array();
        return $rtn;
    }

    public function get_post_info_by_post_id($post_id)
    {
        $this->db->select('*');
        $this->db->from('post');
        $this->db->where('post_id', $post_id);

        $rtn = $this->db->get()->row_array();
        return $rtn;
    }

    public function update_post_up_and_down($arr=array())
    {
        extract($arr);

        if ($is_up == 1) {
            $this->db->set('up', 'up + 1', false);
        } else {
            $this->db->set('down', 'down + 1', false);
        }
        $this->db->where('post_id', $post_id);
        $this->db->update('post');

        $this->db->select('up, down');
        $this->db->from('post');
        $this->db->where('post_id', $post_id);

        $rtn = $this->db->get()->row_array();
        return $rtn;
    }

    public function get_room_info($room_id)
    {
        $this->db->select('*');
        $this->db->from('room');
        $this->db->where('room_id', $room_id);

        $rtn = $this->db->get()->row_array();
        return $rtn;
    }
}