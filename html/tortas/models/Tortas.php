<?php


include_once 'Combinations.php';
include_once 'Combinatorics.php';

/**
*
*/
class Tortas
{

	private $num_tortas = 0;
	private $num_ingredients = 3;

	private $adjectives = ['rica', 'sabrosa', 'jugosa', 'loca', 'chula', 'caliente', 'picante', 'buena', 'deliciosa', 'gigante', 'la juanita', 'la vaquita'];

	private $ingredients = ['pierna', 'milanesa', 'quesillo', 'piña', 'huevo', 'salchicha', 'chorizo', 'manchego', 'rajas con crema', 'pastor', 'champiñones', 'pechuga', 'queso amarillo', 'chuleta', 'jamón', 'tocino'];

	private $national_names = ['abjasia', 'afgana', 'albanesa', 'alemana', 'andorrana', 'angoleña', 'antiguana', 'argelina', 'argentina', 'armenia', 'arubeña', 'australiana', 'austriaca', 'azerbaiyana', 'bahameña', 'beliceña', 'beninesa', 'bermudeña', 'bielorrusa', 'birmana', 'boliviana', 'bosnia', 'botsuana', 'brasileña', 'británica', 'bruneana', 'burkinesa', 'burundesa', 'butanesa', 'búlgara', 'caboverdiana', 'camboyana', 'camerunesa', 'ceilanesa', 'chadiana', 'checa', 'chilena', 'china', 'colombiana', 'congoleña', 'cubana', 'curazaleña', 'danesa', 'dominicana', 'dominiquesa', 'ecuatoguineana', 'ecuatoriana', 'egipcia', 'eritrea', 'eslovaca', 'eslovena', 'española', 'estonia', 'filipina', 'finlandesa', 'fiyiana', 'francesa', 'gabonesa', 'gambiana', 'georgiana', 'ghanesa', 'gibraltareña', 'granadina', 'griega', 'groenlandesa', 'guamesa', 'guatemalteca', 'guerneseyesa', 'guineana', 'guyanesa', 'haitiana', 'hondureña', 'hongkonesa', 'húngara', 'india', 'indonesia', 'irlandesa', 'islandesa', 'italiana', 'jamaicana', 'japonesa', 'jerseyesa', 'jordana', 'kazaja', 'keniana', 'kiribatiana', 'laosiana', 'letona', 'libanesa', 'liberiana', 'libia', 'liechtensteiniana', 'lituana', 'luxemburguesa', 'macedonia', 'malasia', 'maldiva', 'maltesa', 'marfileña', 'mauriciana', 'mauritana', 'mexicana', 'micronesia', 'moldava', 'monegasca', 'mongola', 'montenegrina', 'mozambiqueña', 'namibia', 'nauruana', 'neerlandesa', 'neozelandesa', 'nepalesa', 'nigeriana', 'nigerina', 'norcoreana', 'noruega', 'palauana', 'palestina', 'panameña', 'paraguaya', 'peruana', 'polaca', 'portuguesa', 'puertorriqueña', 'ruandesa', 'rumana', 'rusa', 'sahariana', 'salvadoreña', 'samoana', 'sancristobaleña', 'sanvicentina', 'senegalesa', 'serbia', 'sierraleonesa', 'siria', 'somalilandesa', 'sudafricana', 'sudanesa', 'sueca', 'suiza', 'surcoreana', 'surinamesa', 'surosetia', 'tailandesa', 'taiwanesa', 'tanzana', 'tayika', 'togolesa', 'tongana', 'tunecina', 'turca', 'turcomana', 'tuvaluana', 'ucraniana', 'ugandesa', 'uruguaya', 'uzbeka', 'vaticana', 'venezolana', 'yibutiana', 'zambiana'];

	private $state_names = ['aguascalentense', 'bajacaliforniana', 'campechana', 'chetumaleña', 'chiapaneca', 'chihuahuense', 'chilpancinguense', 'coahuilense', 'colimense', 'cuernavaquense', 'culiacanense', 'duranguense', 'guadalajarense', 'guanajuatense', 'guerrerense', 'hermosillense', 'hidalguense', 'jalisciense', 'meridana', 'mexicalense', 'mexiquense', 'michoacana', 'morelense', 'moreliana', 'nayarita', 'neoleonéa', 'oaxaqueña', 'pachuqueña', 'paseña', 'poblana', 'potosina', 'queretana', 'quintanarroense', 'regiomontana', 'saltillense', 'sinaloense', 'sonorense', 'sudcaliforniana', 'tabasqueña', 'tamaulipeca', 'tapatía', 'tepiqueña', 'tlaxcalteca', 'toluqueña', 'veracruzana', 'victorense', 'villahermosina', 'xalapeña', 'yucateca', 'zacatecana', 'neoyorquina', 'parisina', 'madrileña', 'tejana'];

	private $local_names = ['acapulqueña', 'acayuqueña', 'actopense', 'alvaradeña', 'amatlensa', 'apizaquense', 'aquicalidensa', 'arandensa', 'barqueña', 'bruja', 'cachanilla', 'cancunense', 'capitalina', 'cerroazulense', 'chilanga', 'choca', 'comiteca', 'cuautlense', 'cuernavacense', 'culichi', 'defeña', 'deliciense', 'doctormorense', 'emeritense', 'ensenadense', 'francorrinconense', 'fresnillense', 'gomezpalatina', 'guadalupense', 'guaymense', 'hidrómila', 'hidrocálida', 'huanimarensa', 'igualteca', 'irapuatense', 'jalisciensa', 'jarocha', 'juarense', 'leonéa', 'linarense', 'ludovicense', 'mascotense', 'mazateca', 'metepequense', 'milpaneca', 'minatitleca', 'misanteca', 'mochitense', 'moroleonéa', 'neolaredense', 'nicolaíta', 'nigropetense', 'nuevoleonéa', 'obregonense', 'ocuituquensa', 'orizabeña', 'panuquense', 'parralense', 'playense', 'pozarricense', 'purisimense', 'quintanarroensa', 'reinera', 'reynosense', 'robledeña', 'sahuayense', 'salmantina', 'samalayuquense', 'sampetrina', 'sanagustinense', 'sanandrecina', 'sanjuanensa', 'sanluisina', 'tacambarense', 'tampiqueña', 'tecampanera', 'tecuexe', 'tehuacanensa', 'teloloapense', 'tepiteña', 'tequileña', 'tequixquense', 'tijuanense', 'torreonense', 'tresvallense', 'tulancinguense', 'tulense', 'tulteca', 'tultepequensa', 'tultitleca', 'tuxteca', 'uriangatense', 'vallejuarense', 'xochimilquense', 'yavareña'];



	private $national_names_subset = ['afgana', 'alemana', 'argentina', 'australiana', 'americana', 'austriaca', 'boliviana', 'brasileña', 'chilena', 'china', 'colombiana', 'coreana', 'cubana', 'danesa', 'salvadoreña', 'española', 'filipina', 'francesa', 'griega', 'gringa', 'guatemalteca', 'haitiana', 'hondureña', 'irlandesa', 'italiana', 'japonesa', 'libanesa', 'mexicana', 'mongola', 'noruega', 'palestina', 'paraguaya', 'peruana', 'polaca', 'portuguesa', 'dominicana', 'rusa', 'sueca', 'suiza', 'uruguaya', 'venezolana'];

	private $state_names_subset = ['campechana', 'chiapaneca', 'meridana', 'mexiquense', 'michoacana', 'moreliana', 'nayarita', 'oaxaqueña', 'pachuqueña', 'paseña', 'poblana', 'potosina', 'queretana', 'regiomontana', 'californiana', 'tabasqueña', 'tapatía', 'tepiqueña', 'toluqueña', 'veracruzana', 'xalapeña', 'yucateca', 'neoyorquina', 'parisina', 'madrileña', 'tejana'];

	private $local_names_subset = ['acapulqueña', 'amatlensa', 'arandensa', 'barqueña', 'bruja', 'cachanilla', 'capitalina', 'chilanga', 'comiteca', 'defeña', 'guadalupense', 'hidrómila', 'jalisciensa', 'jarocha', 'leonéa', 'mazateca', 'milpaneca', 'orizabeña', 'reinera', 'robledeña', 'salmantina', 'sampetrina', 'sanjuanensa', 'sanluisina', 'tampiqueña', 'tecuexe', 'tepiteña', 'tequileña', 'tulteca', 'xochimilquense', 'yavareña'];



	private $names;

	public function __construct($num_tortas = Null, $num_ingredients = Null, $adjectives = Null, $ingredients = Null, $names = Null) {
		if (isset($num_tortas)) {
        	$this->num_tortas = $num_tortas;
        }
        if (isset($num_ingredients)) {
        	$this->num_ingredients = $num_ingredients;
        }
        if (isset($adjectives)) {
        	$this->adjectives = $adjectives;
        }
        if (isset($ingredients)) {
        	$this->ingredients = $ingredients;
        }
        if (isset($names)) {
        	$this->names = $names;
        } else {
        	$this->names = array_merge($this->national_names_subset, $this->state_names_subset, $this->local_names_subset);
        }
    }




	public function get_name() {
		return "torta " . $this->adjectives[array_rand($this->adjectives)];
	}



	public function get_menu() {

		// some combinations, for reference
		// 8c2 = 28, 8c3 = 56
		// 9c2 = 36, 9c3 = 84
		// 10c2 = 45, 10c3 = 120

		// create combinations of tuples of all 1-ingredient to n-ingredient combinations taken from the array of ingredients
		$ic = [];
		for ($i = 1; $i <= $this->num_ingredients; $i++) {
			$ic_comb = new Combinations($this->ingredients, $i);

			foreach ($ic_comb as $comb) {
				$ic[] = $comb;
			}
		}

		shuffle($ic);
		shuffle($this->names);

		$num_ic = count($ic);
		$num_names = count($this->names);


		// make sure arrays have the same length, so that the array of ingredient combinations can be concatenated onto the array of names (pairing ingredients with the names of tortas)
		if ($num_ic > $num_names) {
			array_splice($ic, 0, $num_ic - $num_names);
		} elseif ($num_names > $num_ic) {
			array_splice($this->names, 0, $num_names - $num_ic);
		}


		// shuffle the 2 and 3 ingredient combinations to approximate the haphazard structure of a torta menu
		foreach ($ic as &$this->ingredients) {
		    shuffle($this->ingredients);
		}
		unset($this->ingredients);


		// initialize an empty array of tortas, which will contain tortas and their ingredients
		$tortas = [];
		foreach(range(0, count($ic) - 1) as $i) {
			$tortas[] = [$this->names[$i], implode(', ', $ic[$i])];
		}


		if($this->num_tortas) {
			return array_slice($tortas, 0, $this->num_tortas);
		} else {
			return $tortas;
		}

	}

}

