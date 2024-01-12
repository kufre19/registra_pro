<?php

    namespace App\Http\Controllers\CRM;

    use App\Helpers\Core\Traits\FileHandler;
    use App\Http\Controllers\Controller;
    use App\Models\Core\Builder\Form\CustomField;
    use App\Models\Core\File\File;
    use App\Models\Core\Status;
    use App\Models\CRM\Activity\Activity;
    use App\Models\CRM\Note\Note;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Storage;

    class AdditionalController extends Controller
    {
        use FileHandler;

        public function activitiesDone(Activity $activity)
        {
            $done = Status::where('name', 'LIKE', '%done')->first()->id;
            $activity->update(['status_id' => $done]);

            return updated_responses('activities_done');
        }

        public function noteUpdate(Request $request, Note $note)
        {
            $note->update($request->all());

            return updated_responses('note');
        }

        public function noteDestroy(Note $note)
        {
            $note->delete();

            return deleted_responses('note');
        }

        public function fileDownload(File $file)
        {
            $name = basename($file->path);
            return Storage::download($this->removeStorage('public/' . $file->path), $name);

        }

        public function fileDestroy(File $file)
        {
            $this->deleteFile($file->path);
            $file->delete();

            return deleted_responses('file');
        }

        public function statusesUser()
        {
            return Status::where('type', 'user')->get();
        }

        public function customFieldSearch()
        {
            if (request()->get('in_list') == '1') {
                return CustomField::with(['customFieldType:id,name'])
                    ->where('context', 'LIKE', '%' . request()->type . '%')
                    ->where('in_list', request()->get('in_list'))
                    ->get();
            } else {

                return CustomField::with(['customFieldType'])
                    ->where('context', 'LIKE', '%' . request()->type . '%')
                    ->get();
            }
        }
    }
