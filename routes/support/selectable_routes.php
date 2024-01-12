<?php

use App\Http\Controllers\CRM\Selectable\SelectableController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'app'], function (Router $route) {
    $route->get('selectable/organisations', [SelectableController::class, 'selectableOrganisations'])->name('selectable.organisations');
    $route->get('selectable/tags', [SelectableController::class, 'selectableTags'])->name('selectable.tags');
    $route->get('selectable/persons', [SelectableController::class, 'selectablePersons'])->name('selectable.persons');

    $route->get('selectable/deals', [SelectableController::class, 'selectableDeals'])->name('selectable.deals');
    $route->get('selectable/owners', [SelectableController::class, 'selectableOwners'])->name('selectable.owners');

    $route->get('selectable/organization/{id}/person', [SelectableController::class, 'selectableOrganizationPerson'])
        ->name('selectable.organization_owners');

    $route->get('selectable/countries', [SelectableController::class, 'selectableCountries'])
        ->name('selectable.countries');

    $route->get('selectable/taxes', [SelectableController::class, 'selectableTaxes'])
        ->name('selectable.taxes');

    $route->get('taxes/all', [SelectableController::class, 'allTaxes'])
        ->name('selectable.all_taxes');

    $route->get('selectable/expenses', [SelectableController::class, 'selectableExpenseAreas'])
        ->name('selectable.expense_areas');
});
