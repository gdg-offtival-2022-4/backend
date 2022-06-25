<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class All_model extends CI_Model
{
    public function create_user($attr=Array())
    {
        extract($attr);

        $this->db->set('id', $id);
        $this->db->set('pw', $pw);
        $this->db->set('nickname', $nickname);
        $this->db->set('image_url', $image_url);
        $this->db->insert('member');

        return $this->db->insert_id();
    }

    public function validate_login($attr=Array())
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

    public function create_room($attr=Array())
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

    public function join_room($attr=Array())
    {
        extract($attr);

        $this->db->set('user_id', $user_id);
        $this->db->set('room_id', $room_id);
        $this->db->set('participating', true);
        $this->db->insert('room_p');

        return true;
    }

}