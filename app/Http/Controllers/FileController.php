<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function store(Request $request)
    {
        if (request()->file('task_files')) {
            $task_files = request()->file('task_files');
            for ($i = 0; $i < count($task_files); $i++) {
                $task_file = $task_files[$i];
                $task_file_name = $task_file->getClientOriginalName();
                
                if(File::where('task_id', $request->task_id)->where('name', $task_file_name)->count() > 0) {
                    $task_file_name = '1_' . $task_file->getClientOriginalName();
                }

                $task_file->move(public_path() . '/uploads/task_' . $request->task_id, $task_file_name);

                $file = new File();
                $file->name = $task_file_name;
                $file->task_id = $request->task_id;
                $file->user_id = $request->user_id;
                $file->save();

                return \Response::make('/uploads/' . $task_file_name, 200, [
                    'Content-Disposition' => 'inline',
                ]);
            }
        }
    }
}
