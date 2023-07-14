<?php

namespace App\Controllers;

use App\Models\AdminModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class Login extends ResourceController
{
    use ResponseTrait;

    private function encryptString($stringToEncrypt)
    {
      $key = 'Wj4MP9v79QK2';
      $cipher = "aes-256-cfb";
      if (in_array($cipher, openssl_get_cipher_methods()))
      {
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext = openssl_encrypt($stringToEncrypt, $cipher, $key, $options=0, $iv, $tag);
        return $ciphertext;
      }
    }

    private function generateRandomString($length = 10) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[random_int(0, $charactersLength - 1)];
      }
      return $this->encryptString($randomString);
  }

    public function create()
    {
      $data = json_decode($this->request->getBody(), true);
      $plaintext = $data['pass'];
      $data['pass'] = hash('sha512', $plaintext);
        $model = new AdminModel();
        $model->insert($data);
        $response = [
          'status'   => 201,
          'error'    => null,
          'messages' => [
            'success' => 'Admin created'
          ]
        ];
        return $this->respondCreated($response, 201);
      }

    public function update($id = null)
    {
      $data = json_decode($this->request->getBody(), true);
      $db = db_connect();
      $tokenString = $this->generateRandomString(50);
      $query = $db->query('UPDATE admins SET token="' . $tokenString . '" WHERE user="' . $data['user'] . '" AND pass="' . hash('sha512', $data['pass']) .'"');
      if ($query) {
        $response = [
          'status'   => 200,
          'error'    => null,
          'messages' => [
            'success' => 'Token created',
            'data' => [
              'token' => $tokenString,
              'user' => $data['user'],
              'pass' => hash('sha512', $data['pass'])
            ]
          ]
        ];
        return $this->respondCreated($response, 200);
      }
      return $this->fail('Token not created');
    }
}
