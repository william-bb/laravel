<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Select;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Number;

class AccessProfiles extends ResourceRegular
{

    public static function authorizedToCreate(Request $request)
    {
        if(auth()->user()) {
            if($request->user()->admin != '1') {
                return false;
            } else {
                return true;
            }
        } else {
          return false;
        }

    }

    public function authorizedToDelete(Request $request)
    {
        return false;
    }


    public function authorizedToUpdate(Request $request)
    {
        if(auth()->user()) {
            if($request->user()->admin != '1') {
                return false;
            } else {
                return true;
            }
        } else {
          return false;
        }
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        if(auth()->user()) {
            if($request->user()->admin != '1') {
                return false;
            } else {
                return true;
            }
        } else {
          return false;
        }
    }

    public static function detailQuery(NovaRequest $request, $query)
    {
        if(auth()->user()) {
            if($request->user()->admin != '1') {
                return false;
            } else {
                return true;
            }
        } else {
          return false;
        }
    }

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Slotlayer\AccessProfiles::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'profile_name';

    public static function perPageOptions()
    {
        return [50, 100, 150, 250, 500];
    } 

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'profile_name',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [

            ID::make()->sortable()->hideFromIndex(),


            Text::make('Profile Tag', 'profile_name')
                ->sortable()
                ->rules('required', 'max:100'),


            Select::make('Launcher Branding', 'branded')->options([
                '0' => 'None',
                '1' => 'Betboi.io',
                '2' => 'RXC',
            ])->displayUsingLabels()->help('Brand themes must be setup on lobby launcher before using'),

        Number::make('Max. Entries per Session', 'max_entries_sessions')->hideFromIndex()->help('Amount of times a player can enter using same session link.')->min(1)->default(2)->max(1000)->step(1)->rules('required', 'max:10'),
        Number::make('Demo Sessions per Hour', 'max_hourly_demosessions')->help('Max. demo (fun play) sessions operator can create per hour.')->min(1)->max(2500)->default(400)->step(1)->rules('required', 'max:10'),

        Number::make('Callback Errors max. per Hour', 'max_hourly_callback_errors')->hideFromIndex()->help('Callback error limit within 1 hour before setting operator to in-active.')->min(1000)->max(15000)->default(5000)->step(1)->rules('required', 'max:10'),
        Number::make('Create Session Errors max. per Hour', 'max_hourly_createsession_errors')->help('Create Session error limit within 1 hour before setting operator to in-active.')->min(2500)->max(10000)->default(2000)->step(1)->rules('required', 'max:10'),





        ];
    }

    
    /** 
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
