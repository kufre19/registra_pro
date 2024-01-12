<?php

namespace App\Http\Controllers\CRM\Expense;

use App\Exceptions\GeneralException;
use App\Filters\CRM\ExpenseAreaFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CRM\Expense\ExpenseAreaRequest;
use App\Models\CRM\Expense\Expense;
use App\Models\CRM\Expense\ExpenseArea;
use App\Services\CRM\Expense\ExpenseAreaService;
use Illuminate\Support\Facades\DB;

class AreaOfExpenseController extends Controller
{
    public function __construct(ExpenseAreaService $service, ExpenseAreaFilter $filter)
    {
        $this->service = $service;
        $this->filter = $filter;
    }

    public function index()
    {
        return $this->service
            ->filters($this->filter)
            ->withSum([
                'expenses as total_amount' => function($query) { $query->select(DB::raw('COALESCE(SUM(amount), 0)')); }
            ], 'total_amount')
            ->latest('id')
            ->paginate(
                request('per_page', 10)
            );
    }

    public function create()
    {
        //
    }

    public function store(ExpenseAreaRequest $request)
    {
        $this->service
            ->save(array_merge(
                $request->only('name', 'description'),
                ['created_by' => auth()->id()]
            ));

        return created_responses('area_of_expense');
    }


    public function show(ExpenseArea $expenseArea): ExpenseArea
    {
        return $expenseArea;
    }


    public function edit($id)
    {
        //
    }


    public function update(ExpenseAreaRequest $request, ExpenseArea $expenseArea)
    {
        $this->service
            ->setModel($expenseArea)
            ->save($request->only('name', 'description'));

        return updated_responses('area_of_expense');
    }


    public function destroy(ExpenseArea $expenseArea)
    {
        $expenseAreaCount = $expenseArea->expenses()->count();

        throw_if($expenseAreaCount > 0, new GeneralException(trans('default.expense_area_in_use')));

        $expenseArea->expenses()->delete();

        $this->service
            ->setModel($expenseArea)
            ->delete();

        return deleted_responses('area_of_expense');
    }
}
