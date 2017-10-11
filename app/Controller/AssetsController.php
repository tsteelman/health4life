<?php

App::uses('AppController', 'Controller');

class AssetsController extends AppController {

	public function combine($type) {
		$this->autoRender = false;
		$themePath = App::themePath('App');
		if ($type === 'css') {
			header("Content-type: text/css; charset: UTF-8");
			header('Expires: ' . gmdate("D, d M Y H:i:s", time() + 3600 * 24 * 7) . ' GMT');
			$cssFiles = array(
				'bootstrap.min',
				'bootstrap-theme.min',
				'jquery-ui-1.10.3.custom.min',
				'main',
				'home',
				'responsive',
				'event',
				'wizard',
				'blueimp-gallery.min',
				'chosen',
				'zabuto_calendar.min',
				'bootstrap-timepicker.min',
				'fineuploader-4.0.2.min',
				'imgareaselect-animated',
				'bootstrap-tagsinput',
				'ladda-themeless.min'
			);

			foreach ($cssFiles as $cssFile) {
				require_once ($themePath . 'webroot/css/' . $cssFile . '.css');
				echo PHP_EOL;
			}
		} elseif ($type == "js") {
			header('Content-type: text/javascript; charset: UTF-8');
			header('Expires: ' . gmdate("D, d M Y H:i:s", time() + 3600 * 24 * 7) . ' GMT');

			$jsFiles = array(
				'vendor/html5shiv',
				'vendor/modernizr-2.6.2-respond-1.1.0.min',
				'vendor/jquery-1.10.1.min',
				'vendor/bootstrap.min',
				'vendor/jquery.jsticky',
				'vendor/chosen.jquery.min',
				'vendor/jquery.slimscroll.min',
				'vendor/jquery.validate.min',
				'vendor/additional-methods.min',
				'vendor/bootstrap.file-input',
				'vendor/fuelux.wizard.min',
				'vendor/bootbox.min',
				'vendor/fineuploader-4.0.2.min',
				'vendor/jquery-ui-1.10.3.min',
				'vendor/jquery.imgareaselect.min',
				'vendor/bootstrap-tagsinput.min',
				'vendor/spin.min',
				'vendor/ladda.min',
				'vendor/jquery.contenthover',
				'vendor/jquery.blueimp-gallery.min',
				'vendor/jquery.pwstrength.bootstrap',
				'vendor/jquery.flexisel',
				'vendor/jquery.browser',
				'vendor/bootstrap-timepicker.min'
			);

			foreach ($jsFiles as $jsFile) {
				require_once ($themePath . 'webroot/js/' . $jsFile . '.js');
				echo PHP_EOL;
			}
		}

		exit();
	}
}