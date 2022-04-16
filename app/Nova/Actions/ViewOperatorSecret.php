<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Heading;

class ViewOperatorSecret extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public $withoutActionEvents = true;

    public $name = 'Create new API Secret';

    public function handle(ActionFields $fields, Collection $models)
    {
    if ($models->count() > 1) {
        return Action::danger('Please run this on only one user resource.');
    }
                    $selectModel = $models->first();


    if (auth()->user()->id !== $selectModel->ownedBy) {
        return Action::danger('You are not authorized to generate new secret key.');
    }


    $token = $fields->secret;

    $models->first()->update(['operator_secret' => $token]);

    return Action::message('API secret password has been changed.');


    }
    /**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {

        return [
            Heading::make('<p>Please note that below secret key is <b>only shown once</b>, after you confirm this action.</p><br><p>Fff</p>')->asHtml(),

            Text::make('Secret')->readonly()->default(Str::random(12).rand(1000, 9999999)),

        ];
    }
}
