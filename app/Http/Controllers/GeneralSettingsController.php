<?php

namespace App\Http\Controllers;

use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Session;

class GeneralSettingsController extends Controller
{
    /********************************************************************/
    public function authenticateRole($roles = null)
    {
        $permissionRole = [];
        foreach ($roles as $key => $value) {

            $permissionCheck = checkRolePermission($value);

            $permissionRole[] = [
                'role' => $value,
                'access' => $permissionCheck->access
            ];
        }

        if (@$permissionRole[0]['access'] == 0 && @$permissionRole[1]['access'] == 0) {
            Session::flash('flash_message_warning', 'You have no permission');
            return redirect(route('dashboard'))->send();
        }
    }
    public function index()
    {

        $roles = [
            '0' => 'settings',
            '1' => 'general_settings'
        ];
        $this->authenticateRole($roles);

        $pageTitle = "General Settings";
        $general = GeneralSetting::first();
        return view('settings.general', compact('pageTitle', 'general'));
    }
    /*********************************************************/
    public function generalSettingUpdate(Request $request)
    {
        // echo "<pre>"; print_r($request->all()); exit;
        $general = GeneralSetting::first();

        $request->validate([
            'sitename' => 'required',
            'title' => 'required',
            'address' => 'required',
            'mobile' => 'required',
            'email' => 'required',
            'law_firm_admin' => 'required',
            'law_firm_lawyer' => 'required',
            'copy_r' => 'required',
            'logo' => [
                Rule::requiredIf(function () use ($general) {
                    return !$general;
                }),
                'image',
                'mimes:jpg,jpeg,png'
            ],
            'icon' => [
                Rule::requiredIf(function () use ($general) {
                    return !$general;
                }),
                'image',
                'mimes:jpg,jpeg,png'
            ],
            'default_image' => 'sometimes|image|mimes:jpg,jpeg,png',
            'login_image' => 'sometimes|image|mimes:jpg,jpeg,png'
        ]);



        if (!$general) {
            if ($request->hasFile('logo')) {
                $filename = 'logo' . '.' . $request->logo->getClientOriginalExtension();
                @unlink(getFile('logo', @$general->logo));
                $request->logo->move(filePath('logo'), $filename);
            }

            if ($request->hasFile('icon')) {
                $icon = 'icon' . '.' . $request->icon->getClientOriginalExtension();
                @unlink(getFile('logo', @$general->icon));
                $request->icon->move(filePath('logo'), $icon);
            }

            if ($request->hasFile('default_image')) {
                $default_image = 'default_image' . '.' . $request->default_image->getClientOriginalExtension();
                @unlink(getFile('logo', @$general->default_image));
                $request->default_image->move(filePath('logo'), $default_image);
            }

            if ($request->hasFile('login_image')) {
                $login_image = 'login_image' . '.' . $request->login_image->getClientOriginalExtension();
                @unlink(getFile('logo', @$general->login_image));
                $request->login_image->move(filePath('logo'), $login_image);
            }



            GeneralSetting::create([
                'sitename' => $request->sitename,
                'title' => $request->title,
                'address' => $request->address,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'law_firm_admin' => $request->law_firm_admin,
                'law_firm_lawyer' => $request->law_firm_lawyer,
                'copy_r' => $request->copy_r,
                'logo' => $filename,
                'icon' => $icon,
                'default_image' => $default_image,
                'login_image' => $login_image
            ]);
            return redirect()->back()
                ->with('success', 'Setting Updated Successfully');

        }

        if ($request->hasFile('logo')) {
            $filename = 'logo' . '.' . $request->logo->getClientOriginalExtension();
            unLinkFile('logo', @$general->logo);

            $request->logo->move(filePath('logo'), $filename);
        }

        if ($request->hasFile('icon')) {
            $icon = 'icon' . '.' . $request->icon->getClientOriginalExtension();
            $request->icon->move(filePath('logo'), $icon);
        }


        if ($request->hasFile('default_image')) {
            $default_image = 'default_image' . '.' . $request->default_image->getClientOriginalExtension();
            @unlink(getFile('logo', @$general->default_image));
            $request->default_image->move(filePath('logo'), $default_image);
        }

        // if ($request->hasFile('default_image')) {
        //     $default_image = 'default_image' . '.' . $request->default_image->getClientOriginalExtension();
        //     @unlink(getFile('logo', @$general->default_image));
        //     $request->default_image->move(filePath('logo'), $default_image);
        // }

        if ($request->hasFile('login_image')) {
            $login_image = 'login_image' . '.' . $request->login_image->getClientOriginalExtension();
            @unlink(getFile('logo', @$general->login_image));
            $request->login_image->move(filePath('logo'), $login_image);
        }


        $general->update([
            'sitename' => $request->sitename,
            'title' => $request->title,
            'address' => $request->address,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'law_firm_admin' => $request->law_firm_admin,
            'law_firm_lawyer' => $request->law_firm_lawyer,
            'copy_r' => $request->copy_r,
            'logo' => $filename ?? $general->logo,
            'icon' => $icon ?? $general->icon,
            'default_image' => $default_image ?? $general->default_image,
            'login_image' => $login_image ?? $general->login_image

        ]);
        Session::flash('flash_message_success', 'Setting Updated Successfully');
        return redirect()->back();

    }
    /************************************************************/
    public function emailConfig()
    {

        $roles = [
            '0' => 'settings',
            '1' => 'smtp_settings'
        ];
        $this->authenticateRole($roles);
        $pageTitle = 'Email Configuration';
        $general = GeneralSetting::first();
        return view('email.config', compact('pageTitle', 'general'));

    }

    public function emailConfigUpdate(Request $request)
    {

        $data = $request->validate([
            'email_from' => 'required|email',
            'email_method' => 'required',
            'smtp_config' => "required_if:email_method,==,smtp",
            'smtp_config.*' => 'required_if:email_method,==,smtp'
        ]);

        $general = GeneralSetting::first();

        $general->update($data);

        Session::flash('flash_message_success', 'Email Setting Updated Successfully');

        return redirect()->back();


    }
}
