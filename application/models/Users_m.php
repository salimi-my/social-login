<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Users Model
 */
class Users_m extends CI_Model
{
	function __construct()
	{
		$this->userTbl = 'users';
	}
	/*
   * Get rows from the users table
   */
	function getRows($params = array())
	{
		$this->db->select('u.*');
		$this->db->from($this->userTbl . ' as u');

		//fetch data by conditions
		if (array_key_exists("conditions", $params)) {
			foreach ($params['conditions'] as $key => $value) {
				if (strpos($key, '.') !== false) {
					$this->db->where($key, $value);
				} else {
					$this->db->where('u.' . $key, $value);
				}
			}
		}

		if (array_key_exists("id", $params)) {
			$this->db->where('u.id', $params['id']);
			$query = $this->db->get();
			$result = $query->row();
		} else {
			//set start and limit
			if (array_key_exists("start", $params) && array_key_exists("limit", $params)) {
				$this->db->limit($params['limit'], $params['start']);
			} elseif (!array_key_exists("start", $params) && array_key_exists("limit", $params)) {
				$this->db->limit($params['limit']);
			}
			$query = $this->db->get();
			if (array_key_exists("returnType", $params) && $params['returnType'] == 'count') {
				$result = $query->num_rows();
			} elseif (array_key_exists("returnType", $params) && $params['returnType'] == 'single') {
				$result = ($query->num_rows() > 0) ? $query->row_array() : FALSE;
			} else {
				$result = ($query->num_rows() > 0) ? $query->result_array() : FALSE;
			}
		}

		// return fetched data
		return $result;
	}

	/*
	 * Insert user information
	 */
	public function insert($data = array())
	{
		//add created and modified date if not included
		if (!array_key_exists("created", $data)) {
			$data['created'] = date("Y-m-d H:i:s");
		}
		if (!array_key_exists("modified", $data)) {
			$data['modified'] = date("Y-m-d H:i:s");
		}

		//insert user data to users table
		$insert = $this->db->insert($this->userTbl, $data);

		//return the status
		if ($insert) {
			return $this->db->insert_id();;
		} else {
			return false;
		}
	}

	/*
	 * Update user information
	 */
	public function update($data, $conditions)
	{
		if (!empty($data) && is_array($data) && !empty($conditions)) {
			//add modified date if not included
			if (!array_key_exists("modified", $data)) {
				$data['modified'] = date("Y-m-d H:i:s");
			}

			//update user data to users table
			$update = $this->db->update($this->userTbl, $data, $conditions);
			return $update ? true : false;
		} else {
			return false;
		}
	}

	/*
   * Insert / Update social user data into the database
   * @param array the data for inserting into the table
   */
	function checkUser($userData = array())
	{
		if (!empty($userData)) {
			// OAuth conditions
			$oauthConditions = array('oauth_provider' => $userData['oauth_provider'], 'oauth_uid' => $userData['oauth_uid']);

			// Check whether user data already exists in database with same oauth info
			$this->db->from($this->userTbl);
			$this->db->where($oauthConditions);
			$prevRowNum = $this->db->count_all_results();

			// Check whether user data already exists in database with same email
			$this->db->from($this->userTbl);
			$this->db->where("email != '' AND email = '" . $userData['email'] . "'");
			$prevRowNum2 = $this->db->count_all_results();

			if ($prevRowNum > 0) {
				// add modified date if not included
				if (!array_key_exists("modified", $userData)) {
					$userData['modified'] = date("Y-m-d H:i:s");
				}

				// update data
				$update = $this->db->update($this->userTbl, $userData, $oauthConditions);
			} elseif ($prevRowNum2 > 0) {
				// add modified date if not included
				if (!array_key_exists("modified", $userData)) {
					$userData['modified'] = date("Y-m-d H:i:s");
				}
				$conditions = array('email' => $userData['email']);

				// update data
				$update = $this->db->update($this->userTbl, $userData, $conditions);
			} else {
				// add created, modified and other required date if not included
				if (!array_key_exists("created", $userData)) {
					$userData['created'] = date("Y-m-d H:i:s");
				}
				if (!array_key_exists("modified", $userData)) {
					$userData['modified'] = date("Y-m-d H:i:s");
				}
				$userData['activated'] = '1';
				$userData['status'] = '1';

				// insert user data to users table
				$insert = $this->db->insert($this->userTbl, $userData);
			}

			// Get user data from the database
			$query = $this->db->get_where($this->userTbl, $oauthConditions);
			$userData = $query->row_array();
		}

		// Return user data
		return $userData;
	}
}
