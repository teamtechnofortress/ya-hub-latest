<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function filedownload(Request $request){ 
        $file=$request->input('url');
        header("Content-Description: File Transfer"); 
        header("Content-Type: application/octet-stream"); 
        header("Content-Disposition: attachment; filename=\"". basename($file) ."\""); 

        readfile ($file);
        exit(); 
    }
    public function mailMedia(Request $request){
        try{
            if($request->file('file')){
                $file= $request->file('file');
                $filename= date('YmdHi').rand(10000,100000000).'.'.$file->getClientOriginalExtension();
                $file->move('uploads', $filename);
            }
            $res['location'] = asset('uploads/'.$filename);
            echo json_encode($res);
        }
        catch(Throwable $e){
            return $e;
        }
    }
}
