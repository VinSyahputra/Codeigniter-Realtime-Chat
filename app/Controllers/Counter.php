<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Auth;
use App\Models\CounterModel;
use App\Models\Message;
use CodeIgniter\HTTP\ResponseInterface;
use DateTime;
use DateTimeZone;
use PhpParser\Node\Stmt\TryCatch;
use Pusher;

class Counter extends BaseController
{
    public function index()
    {
        // $db = \Config\Database::connect();
        // if ($db->connect_error) {
        //     die("Connection failed: " . $db->connect_error);
        // }

        // $model = new CounterModel();
        // $data['count'] = $model->first()['count'] ?? 0;
        // return view('index', $data);
        if (session()->get('logged_in') == FALSE) {
            return redirect()->to('/login');
        }
        return view('index');
    }

    // public function increment()
    // {
    //     $model = new CounterModel();
    //     $current = $model->first();
    //     $count = $current ? $current['count'] + 1 : 1;

    //     if ($current) {
    //         $model->update($current['id'], ['count' => $count]);
    //     } else {
    //         $model->insert(['count' => $count]);
    //     }

    //     // Pusher
    //     $options = array(
    //         'cluster' => 'ap1',
    //         'useTLS' => true
    //     );
    //     $pusher = new Pusher(
    //         '9eeb4c738de702b10872',
    //         '1065e8377b3c9ba3dd99',
    //         '1850918',
    //         $options
    //     );


    //     try {
    //         $result = $pusher->trigger('new-app', 'update-count', ['count' => $count]);
    //     } catch (\Throwable $th) {
    //         $result =  $th->getMessage();
    //     }

    //     return $this->response->setJSON(['count' => $count]);
    // }

    public function sendMessage()
    {
        $message = new Message();
        $date = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
        $data = [
            'id' => uniqid('msg_', true),
            'sender_id' => session()->get('id'),
            'receiver_id' => $this->request->getVar('receiver_id'),
            'message' => $this->request->getVar('message'),
            'created_at' => $date->format('Y-m-d H:i:s')
        ];
        // Pusher
        $options = array(
            'cluster' => 'ap1',
            'useTLS' => true
        );
        $pusher = new Pusher(
            '9eeb4c738de702b10872',
            '1065e8377b3c9ba3dd99',
            '1850918',
            $options
        );

        try {
            $result = $pusher->trigger('new-app', 'update-chat', ['data' => $data]);
        } catch (\Throwable $th) {
            $result =  $th->getMessage();
        }
        if (!empty($this->request->getVar('receiver_id'))) {
            $message->insert($data);
            return redirect()->to('/')->with('success', 'Message sent');
        }
        return redirect()->to('/')->with('error', 'Receiver not found');
    }

    public function searchUser()
    {
        $db = \Config\Database::connect();
        $model = new Auth();
        // if (empty($this->request->getVar('value'))) {
        //     $data = $model->findAll();
        //     return $this->response->setJSON($data);
        // }
        // $data = $model->like('username', $this->request->getVar('value'))->findAll();
        $sql = "
            SELECT users.id,users.username, users.email, latest_message.*
            FROM users
            INNER JOIN (
                SELECT tb_message.*
                FROM tb_message
                WHERE tb_message.id IN (
                    SELECT MAX(id)
                    FROM tb_message
                    GROUP BY receiver_id
                )
            ) AS latest_message ON latest_message.receiver_id = users.id
            WHERE users.username LIKE '" . $this->request->getVar('value') . "%'
            ORDER BY latest_message.created_at DESC;
        ";

        $query = $db->query($sql);
        $data = $query->getResult();
        return $this->response->setJSON($data);
    }

    public function getMessages()
    {
        $db = \Config\Database::connect();
        $message = new Message();
        // $message = $db->table('tb_message');

        // $message->groupStart()
        //     ->where('sender_id', session()->get('id'))
        //     ->where('receiver_id', $this->request->getVar('receiver_id'))
        //     ->groupEnd()
        //     ->orGroupStart()
        //     ->where('sender_id', $this->request->getVar('receiver_id'))
        //     ->where('receiver_id', session()->get('id'))
        //     ->groupEnd();

        $sql = "
        SELECT tb_message.*, users.username
        FROM tb_message
        inner join users ON tb_message.receiver_id = users.id
        WHERE (sender_id = '" . session()->get('id') . "' AND receiver_id = '" . $this->request->getVar('receiver_id') . "')
        OR (sender_id = '" . $this->request->getVar('receiver_id') . "' AND receiver_id = '" . session()->get('id') . "')";

        $query = $db->query($sql);
        $data = $query->getResultArray();
        // echo "<pre>";
        // print_r($data);
        // die();        
        // $data = $message->get()->getResultArray();
        return $this->response->setJSON($data);
    }
}
