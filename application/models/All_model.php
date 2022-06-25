<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class All_model extends CI_Model
{
    public function save_user($attr=Array())
    {
        extract($attr);

        $this->db->set('id', $id);
        $this->db->set('pw', $pw);
        $this->db->set('nickname', $nickname);
        $this->db->set('image_url', $image_url);
        $this->db->insert('member');

        return true;
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

}