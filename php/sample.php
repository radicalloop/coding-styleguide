<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\UserSetting;

use JWTAuth;
use Response;
use Validator;
use Hash;
use File;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware('jwt.auth', []);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $respCode = 401;
        $response = [
            'error'   => true,
            'message' => []
        ];

        $validator = Validator::make($request->all(), [
                    'firstName' => 'required',
                    'lastName'  => 'required'
        ]);

        if ($validator->fails())
        {
            $respCode            = 406;
            $response['message'] = $validator->errors()->all();
        }
        else
        {
            $user = JWTAuth::parseToken()->authenticate();

            if ($user)
            {
                $user->firstname = $request->firstName;
                $user->lastname  = $request->lastName;

                $resp = $user->save();

                $respCode            = 200;
                $response['message'] = 'success';
                $response['user']    = $user;
            }
        }

        return Response::json($response, $respCode);
    }

    public function change_password(Request $request)
    {
        $respCode = 401;
        $response = [
            'error'   => true,
            'message' => []
        ];

        $user = JWTAuth::parseToken()->authenticate();

        if ($user)
        {

            $rules = array(
                'current_pass'          => 'required|hashmatch',
                'new_pass'              => 'required|min:5|confirmed',
                'new_pass_confirmation' => 'required|min:5',
            );

            $messages = array(
                'hashmatch' => 'Your current password is incorrect.'
            );

            Validator::extend('hashmatch', function($attribute, $value, $parameters) use ($user) {
                return Hash::check($value, $user->password);
            });

            // run the validation rules on the inputs from the form
            $validator = Validator::make($request->all(), $rules, $messages);


            if ($validator->fails())
            {
                $respCode            = 406;
                $response['message'] = $validator->errors()->all();
            }
            else
            {
                $user->password = bcrypt($request->new_pass);
                $user->save();

                $respCode            = 200;
                $response['message'] = 'success';
                $response['user']    = $user;
            }
        }

        return Response::json($response, $respCode);
    }

    public function uploadAvatar(Request $request)
    {
        $respCode = 401;
        $response = [
            'error'   => true,
            'message' => []
        ];

        $user = JWTAuth::parseToken()->authenticate();

        if ($user)
        {

            $validator = Validator::make($request->all(), [
                        'avatar' => 'required|image'
            ]);

            if ($validator->fails())
            {
                $respCode            = 406;
                $response['message'] = $validator->errors()->all();
            }
            else
            {
                $avatar         = $request->file('avatar');
                $uniqueFileName = uniqid('img_') . '.jpg';
                $destFolder     = public_path() . '/avatar/' . $user->id;

                if (!is_dir($destFolder))
                {
                    mkdir($destFolder);
                    chmod($destFolder, 0755);
                }

                $moved = $avatar->move($destFolder, $uniqueFileName);

                if ($moved)
                {
                    $user->avatar = $uniqueFileName;
                    $user->save();

                    //Delete all other files
                    $allAvatars = File::files($destFolder);

                    foreach ($allAvatars as $avatarPath)
                    {
                        $avatarPathInfo = pathinfo($avatarPath);

                        if ($avatarPathInfo['basename'] !== $uniqueFileName)
                        {
                            File::delete($avatarPath);
                        }
                    }

                    $respCode            = 200;
                    $response['message'] = 'success';
                    $response['user']    = $user;
                }
                else
                {
                    $respCode            = 400;
                    $response['message'] = 'error in upload';
                }
            }
        }

        return Response::json($response, $respCode);
    }

    public function getSettings(Request $request)
    {
        $respCode = 401;
        $response = [
            'error'   => true,
            'message' => []
        ];

        $user = JWTAuth::parseToken()->authenticate();

        if ($user)
        {
            $userSetting = $user->user_setting;

            if ($user->user_setting)
            {
                $response['settings'] = [
                    'send_email_notif' => $userSetting->send_email_notif,
                    'add_other_emails' => $userSetting->add_other_emails,
                ];
            }

            $respCode            = 200;
            $response['error']   = false;
            $response['message'] = 'success';
        }

        return Response::json($response, $respCode);
    }

    public function saveSettings(Request $request)
    {
        $respCode = 401;
        $response = [
            'error'   => true,
            'message' => []
        ];

        $user = JWTAuth::parseToken()->authenticate();

        if ($user)
        {
            $userSetting = $user->user_setting;

            if (!$userSetting)
            {
                $userSetting = new UserSetting();
            }

            $userSetting->user_id          = $user->id;
            $userSetting->send_email_notif = $request->send_email_notif;
            $userSetting->add_other_emails = $request->add_other_emails;

            $userSetting->save();

            $respCode            = 200;
            $response['error']   = false;
            $response['message'] = 'success';
        }

        return Response::json($response, $respCode);
    }
}
