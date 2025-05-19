<?php
use App\Models\ClientCase;
use App\Models\EmailTemplate;
use App\Models\GeneralSetting;
use App\Models\GroupPermission;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


function imageToBase64($filePath)
{
    if (file_exists($filePath)) {
        $image = file_get_contents($filePath);
        $imageType = mime_content_type($filePath);
        return 'data:' . $imageType . ';base64,' . base64_encode($image);
    }
    return null;
}

function allRoutes()
{
    $route = Route::currentRouteName();
    //    echo "<pre>"; print_r($route); exit;
    $routeName = '';
    switch ($route) {
        case $route == 'dashboard':
            $routeName = 'Dashboard';
            break;

        case $route == 'users':
            $routeName = 'Users';
            break;
        case $route == 'roles':
            $routeName = 'Roles';
            break;
        case $route == 'clients':
            $routeName = 'Clients';
            break;
        case $route == 'case.index':
            $routeName = 'Case List';
            break;
        case $route == 'create.case':
            $routeName = 'Create Case';
            break;
        case $route == 'invoices':
            $routeName = 'Invoices';
            break;
        case $route == 'courts.index':
            $routeName = 'Courts';
            break;
        case $route == 'court_category.index':
            $routeName = 'Court Category';
            break;
        case $route == 'quotations.index':
            $routeName = 'Quotations';
            break;
        case $route == 'general.settings':
            $routeName = 'General Settings';
            break;
        case $route == 'email.config':
            $routeName = 'SMTP Settings';
            break;
        case $route == 'countries':
            $routeName = 'Countries';
            break;
        case $route == 'states':
            $routeName = 'States';
            break;
        case $route == 'cities':
            $routeName = 'City';
            break;
        case $route == 'fee.description':
            $routeName = 'Fee Description';
            break;
        case $route == 'case.acts':
            $routeName = 'Case Acts';
            break;
        case $route == 'profile':
            $routeName = 'Profile' . ' - ' . Auth::guard('web')->user()->full_name;
            break;

        default:
            $routeName = '';
            break;
    }
    return $routeName;
}

/***************** User Roles Permissions ****************************/
function checkRolePermission($module_page)
{
    $_SESSION["group_id"] = Auth::guard('web')->user()->group_id;

    $group_id = $_SESSION["group_id"];
    return GroupPermission::where(['group_id' => $group_id])->where('module_page', $module_page)->first();
}

function getCurrentDateTime()
{
    date_default_timezone_set((config('app.timezone')));
    $currentDate = date('Y-m-d h:i:s A');
    return $currentDate;
}

function generateUniqueCode()
{

    $characters = '0123456789';
    $charactersNumber = strlen($characters);

    $code = '';

    while (strlen($code) < 4) {
        $position = rand(0, $charactersNumber - 1);
        $character = $characters[$position];
        $code = $code . $character;
    }

    if (ClientCase::where('caseID', $code)->exists()) {
        generateUniqueCode();
    }

    return $code;

}

function makeDirectory($path)
{
    if (file_exists($path))
        return true;
    return mkdir($path, 0755, true);
}

function uploadImage($image, $path, $old = null)
{

    $isDirectoryMade = makeDirectory($path);

    if (!$isDirectoryMade)
        throw new Exception('Directory could not made');

    // $filename = uniqid() . time() . '.' . $image->getClientOriginalExtension();
    $filename = uniqid() . time();



    if ($image->getClientOriginalExtension() == 'gif') {
        copy($image->getRealPath(), $path . '/' . $filename);
    } else {

        // $imageIntervention = Image::make($image);
        $image = Image::make($image)->encode('webp', 90);

        if ($image->width() > 1000) {
            $image->fit(1000);
        } else {
            $image->resize($image->width(), $image->height());
        }


        if ($old) {
            @unlink($path . '/' . $old);
        }

        $image->save($path . '/' . $filename . '.webp');
    }

    return $image->basename;
}

function filePath($folder_name)
{
    return public_path('uploads/' . $folder_name);
}

function getFile($folder_name, $filename)
{

    return asset('uploads/' . $folder_name . '/' . $filename);
}

function make_clean($string)
{
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

function unLinkFile($folder_name, $filename)
{
    $filePath = public_path('uploads/' . $folder_name . '/' . $filename);

    if (File::exists($filePath)) {

        File::delete($filePath);
    }
}

function variableReplacer($code, $value, $template)
{
    // echo "<pre>"; print_r($code); exit;
    return str_replace($code, $value, $template);
}


function sendMail($key, array $data = null, $user = null, $documents = null)
{
    $general = GeneralSetting::first();

    $emailData = [
        [
            "email" => $user->email,
        ]
    ];

    $template = EmailTemplate::where('name', $key)->first();
    $message = variableReplacer('{username}', $user->username, $template->template);
    $message = variableReplacer('{sent_to}', @$user->email, $message);

    $message = variableReplacer('{sent_from}', @$general->sitename, $message);

    if (isset($data['data']) && count($data['data']) > 0) {

        foreach ($data['data'] as $key => $value) {
            // echo "<pre>";
            // print_r($value);
            // exit;
            $message = variableReplacer("{" . $key . "}", $value, $message);
        }
    }
    // echo "<pre>";
    // print_r($message);
    // exit;

    try {
        if ($general->email_method == 'php') {
            $headers = "From: $general->sitename <$general->email_from> \r\n";
            $headers .= "Reply-To: $general->sitename <$general->email_from> \r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=utf-8\r\n";
            foreach ($emailData as $key => $sent_email) {
                @mail($sent_email['email'], $template->subject, $message, $headers);
            }
        } else {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = $general->smtp_config->smtp_host;
            $mail->SMTPAuth = true;
            $mail->Username = $general->smtp_config->smtp_username;
            $mail->Password = $general->smtp_config->smtp_password;
            $general->smtp_config->smtp_encryption == 'ssl' ?
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS :
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $general->smtp_config->smtp_port;
            $mail->CharSet = 'UTF-8';
            $mail->setFrom($general->email_from, $general->sitename);
            $mail->addAddress($user->email, @$user->fname);
            $mail->addReplyTo($general->email_from, $general->sitename);
            $mail->isHTML(true);
            $mail->Subject = $template->subject;
            $mail->Body = $message;

            if ($documents) {
                foreach ($documents as $key => $document) {
                    $mail->AddAttachment($document['pdfFullPath'], $document['fileName']);
                }
            }

            $mail->send();
        }

        // Determine if request is AJAX
        if (request()->ajax()) {
            return 'Email sent successfully';
        } else {
            return redirect()->back()->with('success', 'Email sent successfully');
        }
    } catch (Exception $e) {
        $errorMessage = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";

        // Determine if request is AJAX
        if (request()->ajax()) {
            return $errorMessage;
        } else {
            return redirect()->back()->with('error', $errorMessage);
        }
    }
}

