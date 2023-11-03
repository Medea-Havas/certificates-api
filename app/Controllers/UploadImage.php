<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class UploadImage extends ResourceController
{
    // create a course
    public function create()
    {
        helper(['form']);
        $type = $this->request->getVar('type');
        switch ($type) {
            case 'thumbnail':
                $certificate_thumbnail = $this->request->getFile('certificate_thumbnail');
                $imageSize = $this->checkImageSize($certificate_thumbnail, 0.5);
                if ($imageSize) {
                    return $this->fail('Image size ' . $imageSize . 'MB is bigger than allowed 0.5MB');
                }
                $newName = $this->generateNewName($certificate_thumbnail->getName());
                $certificate_thumbnail->move('./assets/certificates', $newName, true);
                $response = [
                  'status' => 200,
                  'error' => null,
                  'file_name' => $newName,
                  'messages' => [
                    'success' => 'Certificate thumbnail succesfully uploaded'
                  ]
                ];
                return $this->respond($response);
            case 'image':
                $certificate_image = $this->request->getFile('certificate_image');
                $imageSize = $this->checkImageSize($certificate_image, 1.5);
                if ($imageSize) {
                    return $this->fail('Image size ' . $imageSize . 'MB is bigger than allowed 1.5MB');
                }
                $newName = $this->generateNewName($certificate_image->getName());
                $certificate_image->move('./assets/certificates', $newName, true);
                $response = [
                  'status' => 200,
                  'error' => null,
                  'file_name' => $newName,
                  'messages' => [
                    'success' => 'Certificate image succesfully uploaded'
                  ]
                ];
                return $this->respond($response);
            case 'image2':
                $certificate_image2 = $this->request->getFile('certificate_image2');
                $imageSize = $this->checkImageSize($certificate_image2, 1.5);
                if ($imageSize) {
                    return $this->fail('Image size ' . $imageSize . 'MB is bigger than allowed 1.5MB');
                }
                $newName = $this->generateNewName($certificate_image2->getName());
                $certificate_image2->move('./assets/certificates', $newName, true);
                $response = [
                  'status' => 200,
                  'error' => null,
                  'file_name' => $newName,
                  'messages' => [
                    'success' => 'Certificate image 2 succesfully uploaded'
                  ]
                ];
                return $this->respond($response);
            default:
                break;
        }
    }
    private function checkImageSize($image, $maxSize)
    {
        if ($image->getSize() * 0.000001 > $maxSize) {
            return number_format((float)$image->getSize() * 0.000001, 2, '.', '');
        }
        return 0;
    }
    private function generateNewName($oldName, $length = 4)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        $extension_pos = strrpos($oldName, '.');
        $newName = substr($oldName, 0, $extension_pos) . '_' . $randomString . substr($oldName, $extension_pos);
        return $newName;
    }
}
