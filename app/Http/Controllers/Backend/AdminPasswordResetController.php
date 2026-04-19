<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class AdminPasswordResetController extends Controller
{
    public function forgetPassword(Request $request){

        if($request->isMethod('post')){
            $request->validate(['email' => 'required|string|max:255']);

            $admin = Admin::findForForgotPassword($request->email);
            if($admin){
                //send otp mail
                $otp_code = sprintf("%d", random_int(123456, 999999));
                try {
                    Mail::to($admin->email)->send(new BasicMail([
                        'subject' =>  __('Otp Email'),
                        'message' => __('Your otp code').' '.$otp_code
                    ]));
                }
                catch (\Exception $e) {}

                Admin::whereKey($admin->id)->update(['email_verify_token'=>$otp_code]);

                Session::put('email',$admin->email);
                return redirect()->route('admin.forgot.password.otp');
            }
            return back()->with(toastr_error(__('Email not found please enter a valid email')));
        }
        return view('backend.pages.forgot-password.forgot-password');
    }

    public function passwordResetOtp(Request $request){

        if($request->isMethod('post')){
            $request->validate(['otp' => 'required']);
            $admin_email = session()->get('email');

            $find_email = Admin::where('email',$admin_email)->where('email_verify_token',$request->otp)->first();
            if($find_email){
                Session::put('admin_email',$find_email->email);
                Session::put('admin_otp',$request->otp);
                return redirect()->route('admin.forgot.password.reset');
            }else{
                return back()->with(toastr_error(__('Please enter a valid otp')));
            }
        }
        return view('backend.pages.forgot-password.password-reset-otp');
    }

    public function passwordReset(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'password' => 'required|min:6|max:191',
                'confirm_password' => 'required|min:6|max:191',
            ]);

            $admin_email = session()->get('admin_email');
            $admin_otp = session()->get('admin_otp');

            $admin = Admin::select(['email','email_verify_token'])->where('email',$admin_email)->where('email_verify_token',$admin_otp)->first();

            if($admin){
                if ($request->password == $request->confirm_password) {
                    Admin::where('email',$admin_email)
                        ->where('email_verify_token',$admin_otp)
                        ->update(['password' => Hash::make($request->password)]);
                    return redirect()->route('admin.login');
                }
                return back()->with(toastr_warning(__('Password does not match')));
            }else{
                return back()->with(toastr_warning(__('Admin not found')));
            }
        }
        return view('backend.pages.forgot-password.password-reset');
    }
}
