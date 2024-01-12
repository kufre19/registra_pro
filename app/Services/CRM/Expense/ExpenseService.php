<?php

namespace App\Services\CRM\Expense;

use App\Models\Core\File\File;
use App\Models\CRM\Expense\Expense;
use App\Services\ApplicationBaseService;
use Illuminate\Http\UploadedFile;

class ExpenseService extends ApplicationBaseService
{
    public function __construct(Expense $expense)
    {
        $this->model = $expense;
    }

    public function saveAttachments($attachments): self
    {
        if ($attachments && count($attachments) > 0) {
            foreach ($attachments as $attachment) {
                if (!($attachment instanceof UploadedFile)) continue;
                $this->model->attachments()->save(new File([
                    'path' => $this->storeFile($attachment, 'expense'),
                    'type' => 'expense',
                ]));
            }
        }
        return $this;
    }

    public function removeAttachments($remove_attachments): self
    {
        if ($remove_attachments && count($remove_attachments) > 0) {
            foreach ($remove_attachments as $remove_attachment) {
                $decoded_attachment = json_decode($remove_attachment);
                $attachment = $this->model->attachments()->find(optional($decoded_attachment)->id);
                if ($attachment) {
                    $this->deleteFile(optional($attachment)->path);
                    $attachment->delete();
                }
            }
        }
        return $this;
    }
}
