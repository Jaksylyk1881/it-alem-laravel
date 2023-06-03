<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    protected function Result(int $statusCode, $data = null, $message = null)
    {
        switch ($statusCode) {
            case 200: //�������
                $result['statusCode'] = $statusCode;
                $result['message'] = ($message) ? $message : trans('messages.http_errors.200');
                $result['data'] = $data;
                break;
            case 201: //������� ������
                $result['statusCode'] = $statusCode;
                $result['message'] = ($message) ? $message : trans('messages.http_errors.201');
                $result['data'] = $data;
                break;
            case 202: //������� ������
                $result['statusCode'] = $statusCode;
                $result['message'] = ($message) ? $message : trans('messages.http_errors.202');
                $result['data'] = $data;
                break;
            case 400: //�������� ������
                $result['statusCode'] = 400;
                $result['message'] = ($message) ? $message : trans('messages.http_errors.400');
                $result['data'] = $data;
                break;
            case 401: //�� �����������
                $result['statusCode'] = 401;
                $result['message'] = ($message) ? $message : trans('messages.http_errors.401');
                break;
                $result['data'] = null;
            case 403: //������ ��������
                $result['statusCode'] = 403;
                $result['message'] = ($message) ? $message : trans('messages.http_errors.403');
                $result['data'] = null;
                break;
            case 404: //�� �������
                $result['statusCode'] = $statusCode;
                $result['message'] = ($message) ? $message : trans('messages.http_errors.404');
                $result['data'] = $data;
                break;
            case 500: //������ �������
                $result['statusCode'] = $statusCode;
                $result['message'] = ($message) ? $message : trans('messages.http_errors.500');
                $result['data'] = $data;
                break;
            default:
                $result['statusCode'] = $statusCode;
                $result['message'] = $message;
                $result['data'] = $data;
                break;
        }

        return response()->json($result, $result['statusCode'], []);
    }
    protected function uploadFile($file, $dir = '')
    {
        if (isset($file)) {
            File::isDirectory($dir) or File::makeDirectory($dir, 0777, true, true);

            $file_type = File::extension($file->getClientOriginalName());
            $file_name = time().Str::random(5).'.'.$file_type;
            $file->move('uploads/'.$dir, $file_name);

            return config('app.url') . '/uploads/'.$dir.'/'.$file_name;
        }
    }

    protected function deleteFile(string $path)
    {
        if (File::exists($path)) {
            File::delete($path);
            return true;
        } else {
            return false;
        }
    }
}
