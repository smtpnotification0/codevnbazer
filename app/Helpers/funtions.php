<?php

use App\Models\Setting;
use App\Constants\Status;
use App\Settings\GeneralSettings;
use Illuminate\Support\Facades\File;


if (!function_exists('user_id')) {
    function user_id()
    {
        return auth()->user()->id;
    }
}

if (!function_exists('custom_date')) {
    function custom_date($date)
    {
        return date('d-m-Y h:i:s A', strtotime($date->created_at));
    }
}

if (!function_exists('amount')) {
    function amount($data, $decimals = 0)
    {
        $replaced_data = str_replace(",", "", $data);
        return number_format((float) $replaced_data, $decimals, ".", "");
    }
}

if (!function_exists('price')) {
    function price($data, $decimals = 2)
    {
        $replaced_data = str_replace(",", "", $data);
        $floatValue = (float) $replaced_data;
        $formatted = number_format($floatValue, $decimals, ".", "");
        // Remove .00 if decimals are zero (only for display purposes)
        if ($decimals > 0 && $floatValue == floor($floatValue)) {
            $formatted = number_format($floatValue, 0, ".", "");
        }
        return gs()->currency_symbol . $formatted;
    }
}

if (!function_exists('gs')) {
    function gs()
    {
        return new GeneralSettings();
    }
}

if (!function_exists('get_image')) {
    function get_image($path)
    {
        return asset('uploads/' . $path);
    }
}

if (!function_exists('setEnvValue')) {
    function setEnvValue($key, $value)
    {
        // Get the path to the .env file
        $envFilePath = app()->environmentFilePath();

        // Read the current contents of the .env file
        $contents = File::get($envFilePath);

        // Generate a new value for the key
        $newValue = is_string($value) ? '"' . $value . '"' : $value;

        // Update the contents with the new key-value pair
        $pattern = "/^{$key}=.*/m";
        $newContents = preg_replace($pattern, "{$key}={$newValue}", $contents);

        // Write the updated contents back to the .env file
        File::put($envFilePath, $newContents);
    }
}

if (!function_exists('setEnvValues')) {
    function setEnvValues(array $keyValuePairs)
    {
        // Get the path to the .env file
        $envFilePath = app()->environmentFilePath();

        // Read the current contents of the .env file
        $contents = File::get($envFilePath);

        // Update the contents with the new key-value pairs
        foreach ($keyValuePairs as $key => $value) {
            // Generate a new value for the key
            $newValue = is_string($value) ? '"' . $value . '"' : $value;

            // Create a regex pattern for the key
            $pattern = "/^{$key}=.*/m";

            // Replace the value in the contents
            $contents = preg_replace($pattern, "{$key}={$newValue}", $contents);
        }

        // Write the updated contents back to the .env file
        File::put($envFilePath, $contents);
    }
}

if (!function_exists('strRandom')) {
    function strRandom($length = 12)
    {
        $characters = 'ABCDEFGHJKMNOPQRSTUVWXYZ123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

if (!function_exists('putPermanentEnv')) {
    function putPermanentEnv($key, $value)
    {
        $path = app()->environmentFilePath();
        $escaped = preg_quote('=' . env($key), '/');
        file_put_contents($path, preg_replace(
            "/^{$key}{$escaped}/m",
            "{$key}={$value}",
            file_get_contents($path)
        ));
    }
}

if (!function_exists('slug')) {
    function slug($title)
    {
        return \Illuminate\Support\Str::slug($title);
    }
}

if (!function_exists('productType')) {
    function productType($type)
    {
        if ($type === Status::TOPUP) {
            return "Game / Topup";
        } elseif ($type === Status::INGAME) {
            return "Game / In Game";
        } elseif ($type === Status::VOUCHER) {
            return "Game / Voucher";
        } else {
            return "Digital Product";
        }
    }
}

if (!function_exists('jsonToPlainText')) {
    function jsonToPlainText($jsonData)
    {
        $data = json_decode($jsonData, true);
        $result = '';

        foreach ($data as $key => $value) {
            $key = ucwords(str_replace('_', ' ', $key));
            $result .= ucfirst($key) . ': ' . $value . '<br>';
        }

        return $result;
    }
}

if (!function_exists('jsonToPlainTextAdmin')) {
    function jsonToPlainTextAdmin($jsonData)
    {
        $data = json_decode($jsonData, true);
        $result = '';

        foreach ($data as $key => $value) {
            $key = ucwords(str_replace('_', ' ', $key));
            $result .= ucfirst($key) . ': ' . $value . PHP_EOL;
        }

        return $result;
    }
}

// Payment Gateway
if (!function_exists('depositRedirectUrl')) {
    function depositRedirectUrl($deposit, $gateway)
    {
        return route('user.deposit.ipn', [$deposit->track_id, $gateway]);
    }
}

if (!function_exists('depositIpnRedirectUrl')) {
    function depositIpnRedirectUrl()
    {
        return route('user.addfunds');
    }
}

if (!function_exists('depositCancelUrl')) {
    function depositCancelUrl()
    {
        return route('user.deposit.cancel');
    }
}


if (!function_exists('orderRedirectUrl')) {
    function orderRedirectUrl($order, $gateway)
    {
        return route('user.order.ipn', [$order->track_id, $gateway]);
    }
}

if (!function_exists('orderIpnRedirectUrl')) {
    function orderIpnRedirectUrl($order)
    {
        return ($order->product->isVoucher()) ? route('user.codes') : route('user.orders');
    }
}

if (!function_exists('orderCancelUrl')) {
    function orderCancelUrl($order)
    {
        return ($order->product->isVoucher()) ? route('user.code.cancel') : route('user.order.cancel');
    }
}

if (!function_exists('getPercentageAmount')) {
    function getPercentageAmount($amount, $percentage)
    {
        return amount(($amount * $percentage) / 100);
    }
}