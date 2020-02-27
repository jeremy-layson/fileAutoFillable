<?php
namespace App\Http\Requests;
use Illuminate\Http\UploadedFile;

/**
 * @author Jeremy Layson <jeremy.b.layson@gmail.com>
 * @since 2020-02-27
 *
 * Can be implemented on any Request to automatically
 * Upload request fields and inject to the request
 * for faster CRUD
 *
 *
 * Add this to your app/Http/Requests folder
 */
trait FileAutoFillable {

    protected $defaultMagicKey = '__fill';

    protected $defaultStoreFunction = 'storePubliclyAs';

    protected $defaultAutoFillFolder = '/';

    protected function getValidatorInstance()
    {
        return parent::getValidatorInstance()->after(function ($validator) {
            $magicKey = $this->magicKey ?? $this->defaultMagicKey;
            $storeFunction = $this->storeFunction ?? $this->defaultStoreFunction;
            $autoFillFolder = $this->autoFillFolder ?? $this->defaultAutoFillFolder;

            foreach ($this->all() as $key => $value) {
                if ($value instanceof UploadedFile && (substr($key, - strlen($magicKey)) == $this->magicKey)) {
                    $filename = $this->generateAutoFilename(30) . '.' . $value->extension();
                    $uploadedFilename = $value->{$storeFunction}($autoFillFolder, $filename);
     
                    $this->merge([str_replace($magicKey, '', $key) => $uploadedFilename]);
                }
            }
        });
    }

    /**
     * Can be overridden to implement your own filename
     */
    protected function generateAutoFilename()
    {
        return $this->generateRandomString(30);
    }

    protected function generateRandomString($length = 5, $case = 'none')
    {
        $string = substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )), 1, $length);
    
        if ($case === 'lower') return strtolower($string);
        if ($case === 'upper') return strtoupper($string);

        // clean almost identical characters
        $string = str_replace("l", "1", $string);
        $string = str_replace("o", "0", $string);
        $string = str_replace("O", "0", $string);
        $string = str_replace("i", "1", $string);

        return $string;
    }
}