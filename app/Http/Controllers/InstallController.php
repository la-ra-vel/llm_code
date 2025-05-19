<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class InstallController extends Controller
{
    public function showInstallForm()
    {
        return view('install');
    }

    public function install(Request $request)
    {
        // Validate the installation form inputs
        $validator = Validator::make($request->all(), [
            'app_name' => 'required|string|max:255',
            'db_host' => 'required|string|max:255',
            'db_name' => 'required|string|max:255',
            'db_user' => 'required|string|max:255',
            'db_password' => 'nullable|string|max:255',
            'purchase_code' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Verify the purchase code
        $purchaseCode = $request->input('purchase_code');
        $personalToken = 'YOUR_ENVATO_PERSONAL_TOKEN'; // Replace with your Envato personal token

        // Uncomment and adjust as needed for actual purchase code verification
        /*
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $personalToken,
            'User-Agent' => 'Purchase code verification script'
        ])->get("https://api.envato.com/v3/market/author/sale?code={$purchaseCode}");

        if (!$response->successful() || !$response->json()) {
            return back()->withErrors(['purchase_code' => 'Invalid purchase code.'])->withInput();
        }
        */

        // Set environment variables
        $this->setEnv([
            'APP_NAME' => $request->input('app_name'),
            'DB_HOST' => $request->input('db_host'),
            'DB_DATABASE' => $request->input('db_name'),
            'DB_USERNAME' => $request->input('db_user'),
            'DB_PASSWORD' => $request->input('db_password'),
        ]);

        // Migrate the database
        try {
            // Clear cache and configuration
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('config:cache');

            // Migrate the cache table first
            // Artisan::call('migrate', ['--path' => 'database/migrations/0001_01_01_000001_create_cache_table.php', '--force' => true]);

            // Migrate all other tables
            Artisan::call('migrate', ['--force' => true]);

            // Seed the database
            Artisan::call('db:seed', ['--force' => true]);
        } catch (\Exception $e) {
            // Log the error and return with error message
            \Log::error('Migration failed: ' . $e->getMessage());
            return back()->withErrors(['db' => 'Failed to migrate database: ' . $e->getMessage()])->withInput();
        }

        // Redirect to the home page after successful installation
        return redirect('/')->with('success', 'Application installed successfully!');
    }

    protected function setEnv(array $data)
    {
        $envFile = base_path('.env');

        if (!file_exists($envFile)) {
            throw new \Exception('The .env file does not exist.');
        }

        $envContents = file_get_contents($envFile);

        foreach ($data as $key => $value) {
            $pattern = "/^{$key}=.*$/m";
            $replacement = "{$key}={$value}";
            if (preg_match($pattern, $envContents)) {
                $envContents = preg_replace($pattern, $replacement, $envContents);
            } else {
                $envContents .= "\n{$replacement}";
            }
        }

        file_put_contents($envFile, $envContents);
    }
}
