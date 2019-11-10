<?php
class Users extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
    }

   /**
    * Add New User if validated
    *
    * @param array $data
    * @return void
    */
    public function addUser(array $data)
    {
        if ($this->db->insert('users', $data)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    /**
     * Authenticate credentials
     *
     * @param array $data
     * @return void
     */
    public function authenticate(array $data)
    {
        $user = $this->db->get_where('users', $data);
        if ($user) {
            return $user->row();
        }
        return false;
    }
}
