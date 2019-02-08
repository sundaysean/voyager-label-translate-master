<?php

namespace KRAVANH;

use Illuminate\Events\Dispatcher;
use TCG\Voyager\Models\Menu;
use TCG\Voyager\Models\MenuItem;
use TCG\Voyager\Models\Permission;
use TCG\Voyager\Models\Role;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;

class LabelTranslateServiceProvider extends ServiceProvider
{
	private $models = [
		'LabelTranslate',
	];

	public function register()
	{
		define('VOYAGER_LABELTRANSLATES_PATH', __DIR__.'/..');
		
		app(Dispatcher::class)->listen('voyager.admin.routing', [$this, 'addLabelLanguagesRoutes']);
		app(Dispatcher::class)->listen('voyager.menu.display', [$this, 'addLabelLanguagesMenuItem']);

	}

	public function boot(\Illuminate\Routing\Router $router, Dispatcher $events)
	{
		$this->loadViewsFrom(__DIR__.'/../resources/views', 'kravanh');
		$this->loadModels();
	}

	public function addLabelLanguagesRoutes($router)
    {
        $namespacePrefix = '\\KRAVANH\\Http\\Controllers\\';
        $router->get('label-multi-translate', ['uses' => $namespacePrefix.'LanguageTranslationController@index', 'as' => 'labellanguages']);
		$router->post('translations/update', ['uses' => $namespacePrefix.'LanguageTranslationController@transUpdate', 'as' => 'translation.update.json']);
		$router->post('translations/updateKey', ['uses' => $namespacePrefix.'LanguageTranslationController@transUpdateKey', 'as' => 'translation.update.json.key']);
		$router->delete('translations/destroy/{key}', ['uses' => $namespacePrefix.'LanguageTranslationController@destroy', 'as' => 'translations.destroy']);
		$router->post('translations/create', ['uses' => $namespacePrefix.'LanguageTranslationController@store', 'as' => 'translations.create']);
    }

	public function addLabelLanguagesMenuItem(Menu $menu)
	{
		if ($menu->name == 'admin') 
		{
	        $url = route('voyager.labellanguages', [], false);
	        $menuItem = $menu->items->where('url', $url)->first();
			if (is_null($menuItem)) 
			{
	            $menu->items->add(MenuItem::create([
	                'menu_id'    => $menu->id,
	                'url'        => $url,
	                'title'      => 'Label Translate',
	                'target'     => '_self',
	                'icon_class' => 'voyager-world',
	                'color'      => null,
	                'parent_id'  => null,
	                'order'      => 99,
	            ]));
	            $this->ensurePermissionExist();
	            $this->addLabelLanguagesTable();
	        }
	    }
	}

	private function loadModels()
	{
		foreach($this->models as $model)
		{
			$namespacePrefix = 'KRAVANH\\Models\\';
			if(!class_exists($namespacePrefix . $model))
			{
				@include(__DIR__.'/Models/' . $model . '.php');
			}
		}
	}

	protected function ensurePermissionExist()
    {
        $permissions = [
        	Permission::firstOrNew(['key' => 'browse_label_translates', 'table_name' => 'label_translates']),
        	Permission::firstOrNew(['key' => 'read_label_translates', 'table_name' => 'label_translates']),
        	Permission::firstOrNew(['key' => 'edit_label_translates', 'table_name' => 'label_translates']),
        	Permission::firstOrNew(['key' => 'add_label_translates', 'table_name' => 'label_translates']),
        	Permission::firstOrNew(['key' => 'delete_label_translates', 'table_name' => 'label_translates'])
        ];

		foreach($permissions as $permission)
		{
			if (!$permission->exists) 
			{
	            $permission->save();
	            $role = Role::where('name', 'admin')->first();
				if (!is_null($role)) 
				{
	                $role->permissions()->attach($permission);
	            }
	        }
	    }
    }

	private function addLabelLanguagesTable()
	{

		if(!Schema::hasTable('label_translates'))
		{

    		Schema::create('label_translates', function (Blueprint $table) {
				$table->increments('id');
				$table->string('name');
				$table->string('code');
				$table->timestamps();
			});

		}
		
    }
}
