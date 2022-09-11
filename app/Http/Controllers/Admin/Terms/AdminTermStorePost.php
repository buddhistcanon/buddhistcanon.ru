<?php

namespace App\Http\Controllers\Admin\Terms;

use App\Actions\Terms\StoreTermAction;
use App\Data\TermData;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminTermStorePost extends Controller
{
    public function __invoke(Request $request)
    {
        $termData = TermData::from($request);

        (new StoreTermAction($termData))->execute();

        if ($termData->id) {
            return redirect("/admin/edit_term/$termData->id")->with('success_edit_term', 'Термин сохранён.');
        }

        return redirect('/admin/terms')->with('success_add_term', "Термин $termData->title добавлен..");
    }
}
