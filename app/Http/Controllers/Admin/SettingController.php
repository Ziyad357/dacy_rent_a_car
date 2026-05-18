<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'company_name' => config('app.company_name', config('app.name')),
            'company_address' => config('app.company_address', ''),
            'company_email' => config('app.company_email', ''),
            'company_phone' => config('app.company_phone', ''),
            'currency' => config('app.currency', 'AZN'),
            'late_penalty_rate' => config('app.late_penalty_rate', 0.5),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_address' => 'nullable|string|max:500',
            'company_email' => 'nullable|email',
            'company_phone' => 'nullable|string|max:30',
            'currency' => 'required|string|max:10',
            'late_penalty_rate' => 'required|numeric|min:0|max:10',
            'logo' => 'nullable|image|max:1024',
        ]);

        $envPath = base_path('.env');
        $envContent = file_get_contents($envPath);

        $keys = [
            'APP_COMPANY_NAME' => $data['company_name'],
            'APP_COMPANY_ADDRESS' => $data['company_address'] ?? '',
            'APP_COMPANY_EMAIL' => $data['company_email'] ?? '',
            'APP_COMPANY_PHONE' => $data['company_phone'] ?? '',
            'APP_CURRENCY' => $data['currency'],
            'APP_LATE_PENALTY_RATE' => $data['late_penalty_rate'],
        ];

        foreach ($keys as $key => $value) {
            $value = str_contains($value, ' ') ? '"'.$value.'"' : $value;
            if (str_contains($envContent, $key.'=')) {
                $envContent = preg_replace('/^'.$key.'=.*/m', $key.'='.$value, $envContent);
            } else {
                $envContent .= "\n{$key}={$value}";
            }
        }

        file_put_contents($envPath, $envContent);

        if ($request->hasFile('logo')) {
            $request->file('logo')->storeAs('public', 'logo.png');
        }

        return back()->with('success', 'Parametrlər yadda saxlandı.');
    }
}
