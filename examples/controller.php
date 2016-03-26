<?php
class TerrenosAdminController extends BaseController {

	public function __construct() {
		// Add filters
		$this->beforeFilter('auth', array('on' => 'post', 'on' => 'get'));
	}

		
	public function getNew() {
		return View::make('terrenos::admin.create');
	}

	public function postCreate() {
		$validator = Validator::make(Input::all(), Terrenos::$rules);

		if ($validator->passes()) {
			try {
				$object = new Terrenos;
				$object->manzana = Input::get('manzana');
				$object->lote = Input::get('lote');
				$object->prototipo = Input::get('prototipo');
				$object->ubicacion = Input::get('ubicacion');
				$object->superficie = Input::get('superficie');
				$object->norte = Input::get('norte');
				$object->sur = Input::get('sur');
				$object->oriente = Input::get('oriente');
				$object->poniente = Input::get('poniente');
				
				$object->save();
			} catch (Exception $e) {
				$message = array(
					'color'=>'red',
					'title'	=> 'Advertencia',
					'message' 	=> 'Los datos ya existen en la base de datos.'
				);
				return Redirect::back()
					->with("message", $message);
			}
			$message = array(
				'color'=>'green',
				'title'	=> 'Exitoso',
				'message' 	=> 'Acción realizada con éxito.'
			);
			return Redirect::back()
				->with("message", $message);
		} else {
			return Redirect::back()->withErrors($validator)->withInput();
		}
	}
	
	public function getList() {
		$data = Terrenos::limit(10)->get();
		
		return View::make('terrenos::admin.list')
			->with('data',$data);
	}
		
	public function getEdit($id) {
		$data = Terrenos::find($id);
		if (is_null($data)) {
			throw new Exception('No existente', 1);
		}

		return View::make('terrenos::admin.edit')
			->with('data',$data)
			->with('id',$id);
	}

	public function postUpdate() {
		$validator = Validator::make(Input::all(), Terrenos::$rules);

		if ($validator->passes()) {
			try {
				$object = Terrenos::find(Input::get('id'));
				$object->manzana = Input::get('manzana');
				$object->lote = Input::get('lote');
				$object->prototipo = Input::get('prototipo');
				$object->ubicacion = Input::get('ubicacion');
				$object->superficie = Input::get('superficie');
				$object->norte = Input::get('norte');
				$object->sur = Input::get('sur');
				$object->oriente = Input::get('oriente');
				$object->poniente = Input::get('poniente');
				
				$object->save();
			} catch (Exception $e) {
				$message = array(
					'color'=>'red',
					'title'	=> 'Advertencia',
					'message' 	=> 'Los datos ya existen en la base de datos.'
				);
				return Redirect::back()
					->with("message", $message);
			}

			$message = array(
				'color'=>'green',
				'title'	=> 'Exitoso',
				'message' 	=> 'Acción realizada con éxito.'
			);
			return Redirect::back()
				->with("message", $message);
		
		} else {
			return Redirect::back()->withErrors($validator)->withInput();
		}
	}
		
	public function getDelete($id) {
		$object = Terrenos::find($id);
		$object->delete();

		return Redirect::to('/');
	}
	

} 
?>