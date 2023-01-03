<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ViewsController extends Controller
{

    /************************* Fin Templates en uso ****************************/
    // Login en uso
    public function login()
    {
        $pageConfigs = ['blankPage' => false];

        return view('auth/login', ['pageConfigs' => $pageConfigs]);
    }

    // Forgot Password en uso
    public function forgot_password()
    {
        $pageConfigs = ['blankPage' => false];

        return view('auth/forgot-password', ['pageConfigs' => $pageConfigs]);
    }

    // Reset Password en uso
    public function reset_password($email, $token)
    {
        $user = User::where('email', $email)->first();

        if (!$user) return redirect('/forgot-password')->with('errors', 'El email no existe en nuestros registros.');

        $user2 = User::where('token', $token)->first();

        if (!$user2) return redirect('/forgot-password')->with('errors', 'El token no existe o ya expiró.');

        $pageConfigs = ['blankPage' => false];

        return view('auth/reset-password', ['pageConfigs' => $pageConfigs])->with('email', $email)->with('token', $token);
    }

    /************************* Fin Templates en uso ****************************/

    // Reset Password cover
    public function dashboard()
    {
        $pageConfigs = ['pageHeader' => false, 'footerType' => 'sticky'];

        return view('content/nebolus/dashboard/dashboard', ['pageConfigs' => $pageConfigs]);
    }

    // Notices: Entrys
    public function entry()
    {
        $pageConfigs = ['pageHeader' => false, 'footerType' => 'sticky'];

        //$breadcrumbs = [['link' => "/notice/entry", 'name' => "Entrys"], ['name' => "Entrys"]];
        return view('content/nebolus/notice/entry', [
            'pageConfigs' => $pageConfigs
            //'breadcrumbs' => $breadcrumbs
        ]);
    }

    // Notices: TrendingTopics
    public function trendingtopic()
    {
        $pageConfigs = ['pageHeader' => false, 'footerType' => 'sticky'];

        //$breadcrumbs = [['link' => "/notice/trendingtopic", 'name' => "TrendingTopics"], ['name' => "TrendingTopics"]];
        return view('content/nebolus/notice/trendingtopic', [
            'pageConfigs' => $pageConfigs
            //'breadcrumbs' => $breadcrumbs
        ]);
    }

    // Academy: Tags
    public function tag()
    {
        $pageConfigs = ['pageHeader' => false, 'footerType' => 'sticky'];

        //$breadcrumbs = [['link' => "/notice/tag", 'name' => "Tags"], ['name' => "Tags"]];
        return view('content/nebolus/academy/tag', [
            'pageConfigs' => $pageConfigs
            //'breadcrumbs' => $breadcrumbs
        ]);
    }

    // Live
    public function live()
    {
        $pageConfigs = ['pageHeader' => false, 'footerType' => 'sticky'];

        //$breadcrumbs = [['link' => "/live", 'name' => "Live"], ['name' => "Live"]];
        return view('content/nebolus/live', [
            'pageConfigs' => $pageConfigs
            //'breadcrumbs' => $breadcrumbs
        ]);
    }
}
