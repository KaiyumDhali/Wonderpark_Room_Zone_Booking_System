<?php

namespace App\Http\Controllers\DownloadDB;

use App\Http\Controllers\Controller;
use App\Models\Admin\DownloadDB;
use App\Models\Admin\CompanyProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Log;
use Session;
use Illuminate\Support\Facades\File;

class DownloaddbController extends Controller {

    function __construct() {
        $this->middleware('permission:read dbbackup|write dbbackup|create dbbackup', ['only' => ['index', 'show']]);
        $this->middleware('permission:create dbbackup', ['only' => ['create', 'store']]);
        $this->middleware('permission:write dbbackup', ['only' => ['edit', 'update', 'destroy']]);
    }

    public function home() {
        $data['companyProfile'] = CompanyProfile::orderBy('id', 'DESC')->first();
        $data['trainings'] = Training::orderBy('id', 'desc')->paginate(1);
        return view('pages.training', $data)
                        ->with('i', (request()->input('page', 1) - 1) * 1);
    }

    public function index() {
        $files = Storage::disk('laravel')->allFiles();
        rsort($files);
        return view('pages.dbbackup.dbbackups.index', compact('files'));
    }

    public function download_db() {
        $db_name = "db_backup_" . date('Y_m_d_h_i_s_A');

        $globalPass = 'x+DaSpG2Cr?g';
        
        shell_exec("F:/xampp/mysql/bin/mysqldump -h localhost -u root shahjalal_equity_hr> F:/xampp/htdocs/shahjalal_equity_hr/storage/app/laravel/" . $db_name . ".sql");

//        shell_exec("/usr/bin/mysqldump -h localhost -u gslcorporate_erpuser -p'$globalPass' gslcorporate_erpdb > /home/gslcorporate/public_html/storage/app/laravel/" . $db_name . ".sql");
        
        //$files = Storage::disk('laravel')->allFiles();
        //rsort($files);
        
        return back()->withSuccess('Database backup successfully!');
        
       // return view('pages.dbbackup.dbbackups.index', compact('files'));
    }

    function downloadFile($file_name) {
        //dd('here');
        // $file = Storage::disk('laravel')->get($file_name);
        //dd($file);
        return Storage::disk('laravel')->download($file_name);
    }

    public function dbDestroy($name) {

        Storage::disk('laravel')->delete($name);
//        $files = Storage::disk('laravel')->allFiles();
//        rsort($files);
//        return view('pages.dbbackup.dbbackups.index', compact('files'));
        return back()->withSuccess('Database delete successfully!');
    }

}
