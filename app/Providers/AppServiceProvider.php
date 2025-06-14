<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\library\EncryptionManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        // get license information
        $path_filename= public_path() . '/.license';

        $fp = fopen($path_filename, "ab+");
        if(filesize($path_filename) > 0)
          $license_content = fgets($fp , filesize($path_filename));
        else
          $license_content = "";
        fclose($fp);


        $EncryptManager = new EncryptionManager();

        $decrypt_license = $EncryptManager->decryptsequence($license_content);

        $license_array = json_decode($decrypt_license);

        view()->share('license_array', $license_array);
    }
}
