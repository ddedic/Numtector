<?php namespace Ddedic\Numtector;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

use Ddedic\Numtector\Countries\Repositories\CountryEloquentRepo as CountryProvider;
use Ddedic\Numtector\Operators\Repositories\OperatorEloquentRepo as OperatorProvider;


class NumtectorServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{	
		$loader = AliasLoader::getInstance();


		// Nexsell
		$loader->alias('Numtector', 'Ddedic\Numtector\Facades\NumtectorFacade');		
		$this->package('ddedic/numtector');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{

		$this->app['numtector'] = $this->app->share(function($app)
		{
		  	return new Numtector($app['config'], new CountryProvider, new OperatorProvider);
		});

       $this->app->bind('GatewayPricingQueue', function($app)
        {
            return new Queues\GatewayPricing($app['config'], new CountryProvider, new OperatorProvider);
        });

       $this->app->bind('GatewayPricingFileQueue', function($app)
        {
            return new Queues\GatewayPricingFileQueue( new OperatorProvider );
        });
       
       $this->app->bind('OperaterInsertQueue', function($app)
        {
            return new Queues\OperaterInsertQueue( new OperatorProvider );
        });

	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('numtector');
	}

}