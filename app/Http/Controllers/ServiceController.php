<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Unirest\Request as unirest;
use Unirest\Request\Body as body;

use App\WemosModel as wemos;

class ServiceController extends Controller
{
    //
    private $magicWords = [
        'close'  => ['door', 0],
        'open'   => ['door', 1],
        'lock'   => ['lock', 1],
        'unlock' => ['lock', 0],
    ];
    private $action = "";
    private $apikey = "";
    private $address = "";
    private $headers = array('Accept' => 'application/json');
    private $messeages = [
        'API404' => "Status: API404" . PHP_EOL . "Message: API Key you input doesn't exist",
        '404'    => "The Wemos URL Not Found. Contact administrator",
    ];

    public function handleRequest(Request $req)
    {

        $apikey = $req->apikey;
        $data = $this->apiCheck($apikey);

        if ($data) {
            $this->action = $req->action;
            $this->apikey = $apikey;
            $this->address = "http://" . $data->ip . "/doTheAction";
            $response = $this->makeRequest();
            return response($response);
        } else {
            return response($this->messeages['API404']);
            //dd($req);
        }
    }

    public function handleWemos(Request $req)
    {
        $return = ['status' => "success", 'operation' => "", 'message' => ""];
        $ip     = $req->getClientIp();
        $apikey = $req->apikey;

        $wemosModel = wemos::where('apikey', $apikey);
        //$wemosModel->where('apikey', $apikey);

        if (!$wemosModel->exists()) {
            # code...
            $wemosInsert = new wemos;
            $wemosInsert->ip = $ip;
            $wemosInsert->apikey = $apikey;

            $checking = $wemosInsert->save();
            if ($checking) {
                $return['operation'] = "insert";
                $return['message'] = "insert new wemos success";
            } else {
                $return = ['status' => "failed", 'operation' => "insert", 'message' => "Insert new wemos failed"];
            }
        } else {
            //$updateWemos = new wemos();
            $update = \App\WemosModel::where('apikey', $apikey)->update(['ip' => $ip]); //$updateWemos->where('apikey', $apikey)->update(['ip' => $ip]);
            // $wemosModel->ip = $ip;
            // $update = $wemosModel->save();

            $return['operation'] = "update";
            $return['message'] = "Update operation success";
        }
        return response()->json($return);
    }

    private function apiCheck($api)
    {
        $return = FALSE;

        if (!empty($api) or $api !== NULL) {
            # code...
            $check = wemos::where('apikey', $api);
            $return = ($check->exists()) ? $check->get()[0] : FALSE;
        }
        return $return;
    }

    private function makeRequest()
    {
        $data = [
            'apikey' => $this->apikey,
            'action' => $this->action
        ];
        $body = body::json($data);
        $req = unirest::post($this->address, $this->headers, $body);

        if ($this->action !== 'check' and $req->code === 200) {
            # code...
            $updateThis = [$this->magicWords[$this->action][0] => $this->magicWords[$this->action][1]];
            wemos::where('apikey', $this->apikey)->update($updateThis);
        }

        return "Status: " . $req->code . " ~ " . PHP_EOL . "Message: " . (isset($this->messeages[$req->code])) ? $this->messeages[$req->code] : " "  . " " . $req->body;
    }

    private function makeRequestTest()
    {


        if ($this->action !== 'check') {
            # code...
            $updateThis = [$this->magicWords[$this->action][0] => (string) $this->magicWords[$this->action][1]];
            $wemos = wemos::where('apikey', $this->apikey)->update($updateThis);
        }

        return "Status: " . "ok" . " ~ " . PHP_EOL . "Message: " . "oke check database";
    }
}
