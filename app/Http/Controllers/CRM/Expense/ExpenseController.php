<?php

namespace App\Http\Controllers\CRM\Expense;

use App\Filters\CRM\ExpenseFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CRM\Expense\ExpenseRequest;
use App\Models\CRM\Expense\Expense;
use App\Services\CRM\Expense\ExpenseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Exp;

class ExpenseController extends Controller
{
    public function __construct(ExpenseService $service, ExpenseFilter $filter)
    {
        $this->service = $service;
        $this->filter = $filter;
    }

    public function index()
    {
        return $this->service
            ->filters($this->filter)
            ->with([
                'expenseArea:id,name',
                'createdBy:id,first_name,last_name',
                'attachments',
            ])
            ->latest('id')
            ->paginate(
                request('per_page', 10)
            );
    }


    public function create()
    {
        //
    }


    public function store(ExpenseRequest $request)
    {
        DB::transaction(function () use ($request) {
            $expense = $this->service
                ->save(array_merge(
                    $request->only('name', 'expense_area_id', 'amount', 'description', 'expense_date'),
                    ['created_by' => auth()->id()]
                ));

            if ($request->attachments) {
                $this->service
                    ->setModel($expense)
                    ->setAttrs($request->only('attachments'))
                    ->saveAttachments(request('attachments', []));
            }
        });

        return created_responses('expense');
    }


    public function show(Expense $expense): Expense
    {
        return $expense->load('expenseArea:id,name', 'attachments', 'createdBy:id,first_name,last_name');
    }


    public function edit($id)
    {
        //
    }

    public function update(ExpenseRequest $request, Expense $expense)
    {
        $this->service
            ->setModel($expense)
            ->setAttributes(
                $request->only('name','expense_area_id', 'description', 'amount', 'expense_date')
            )
            ->save();

        $this->service
            ->setModel($expense)
            ->saveAttachments(request('attachments', []))
            ->removeAttachments(request('remove_attachments', []));

        return updated_responses('expense');
    }

    public function destroy(Expense $expense)
    {
        $expense->load('attachments');
        $this->service
            ->setModel($expense)
            ->removeAttachments($expense->attachments)
            ->saveAttachments([])
            ->delete();

        return deleted_responses('expense');
    }
}
