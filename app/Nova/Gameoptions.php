<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Select;
use Inspheric\Fields\Indicator;
use Laravel\Nova\Panel;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\Boolean;

class Gameoptions extends ResourceRegular
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
        if($request->user()->id == $this->resource->ownedBy) {
            return true;
        } elseif($request->user()->admin == '1') {
            return true;
        } else {
            return false;
        }
    }
    public static function detailQuery(NovaRequest $request, $query)
    {
        if($request->user()->admin != '1') {
            return $query->where('ownedBy', $request->user()->id);
        } else {
            return $query;
        }
    }

    
    public static function indexQuery(NovaRequest $request, $query)
    {
        if($request->user()->admin != '1') {
            return $query->where('ownedBy', $request->user()->id);
        } else {
            return $query;
        }
    }
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Slotlayer\GameoptionsParent::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'apikey';

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
        'id',
    ];

    public static function label()
    {
        return 'Webhook Settings';
    }

    public static function factoryWord()
    {
        $faker = \Faker\Factory::create();
        return $faker->word();
    }
    
        /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            BelongsTo::make('User')->rules('required', 'max:50', 'min:3')
            ->hideFromIndex(function ($request) {
                    return $request->user()->admin != "1";
            })
            ->readonly(function ($request) {
                    return $request->user()->admin != "1";
            }), 
            
            BelongsTo::make('Access', 'accessprofile', 'App\Nova\AccessProfiles'),

        
            Text::make('API Key', 'apikey_parent')
                ->sortable()
                ->default(function ($request) {
                    return Str::uuid().'-'.rand(1000, 999999);
                })
                ->rules('required', 'max:55', 'min:10')
                ->readonly(function() {
                    return $this->resource->id ? true : false;
                }),
                Text::make('Secret Password', 'operator_secret')
                ->sortable()
                ->hideFromIndex()
                ->rules('required', 'max:32', 'min:3')
                ->default(Str::random(12))->withMeta(['extraAttributes' => ['type' => 'password']]),

            Boolean::make('Active', 'active')
                ->trueValue('1')
                ->hideWhenUpdating(function ($request) {
                        return $request->user()->admin != "1";
                })
                ->readonly(function ($request) {
                        return $request->user()->admin != "1";
                })
                ->falseValue('0'),

            
            new Panel('Operator Configuration', $this->configurationFields()),
            new Panel('Activity', $this->activityFields()),

        ];
    }

    protected function configurationFields()
    {
        return [
            Text::make('API Endpoint URL', 'callbackurl')
                ->hideFromIndex()
                ->help('Make sure your API base endpoint ends with a slash, you can review completed endpoints after updating.')
                ->default('http://betboi.io/api/callback/tollgate/')
                ->rules('required', 'max:128', 'min:3'),

            Text::make('Casino Website URL', 'operatorurl')
                ->hideFromIndex()
                ->help('Casino URL is used in various games to redirect player on cashier buttons and on errors.')
                ->default('http://betboi.io')
                ->rules('required', 'max:128', 'min:3'),

                    Text::make('Webhook Endpoints (Callback Base + Prefix)',
                        function () {

                        return '<b>Balance:</b> <i>'.$this->resource->callbackurl.$this->resource->slots_prefix.'/balance</i>
                                <br>
                                <b>Bet:</b> <i>'.$this->resource->callbackurl.$this->resource->slots_prefix.'/bet</i>
                                ';
                    })->hideFromIndex()->hideWhenUpdating()->asHtml(), 

        Boolean::make('Return Log', 'return_log')
            ->trueValue('1')
            ->hideWhenUpdating(function ($request) {
                    return $request->user()->admin != "1";
            })
            ->readonly(function ($request) {
                    return $request->user()->admin != "1";
            })
            ->falseValue('0'),

            ];
    }


    protected function activityFields()
    {
        return [
            Text::make('API Endpoint URL', 'callbackurl')
                ->hideFromIndex()
                ->default('http://casinourl.com/api/callback/bulkbet/')
                ->rules('required', 'max:128', 'min:3'),

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
    return [
        (new Actions\ViewOperatorSecret)->onlyOnTableRow()->showOnDetail()
            ->confirmText(' Do you want generate new operator secret? Operator secret is only show once. It will immediately invalidate the old operator secret for any use.')
            ->confirmButtonText('Generate new secret key')
            ->cancelButtonText("Cancel"),
    ];
    }
}
