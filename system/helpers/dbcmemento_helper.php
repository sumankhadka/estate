<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*add new line helpwe*/ 
if ( ! function_exists('get_trainline_name_by_id'))
{
	function get_trainline_name_by_id ($id)
	{
		if($id==0)
			return 'No parent';

		$CI = get_instance();
		$query = $CI->db->get_where('trainlines',array('id'=>$id));
		if($query->num_rows()>0)
		{
			$row = $query->row();
			return $row->name;
		}
		else
		{
			return 'N/A';
		}
	}
}
if ( ! function_exists('get_all_trainlines'))
{
	function get_all_trainlines()
	{
		$CI = get_instance();
		$CI->load->database();
		$query = $CI->db->get_where('trainlines',array('type'=>'trainline','status'=>1));
		return $query;
	}
}
/*add new line helpwe*/ 


if ( ! function_exists('get_location_name_by_id'))
{
	function get_location_name_by_id ($id)
	{
		if($id==0)
			return 'No parent';

		$CI = get_instance();
		$query = $CI->db->get_where('locations',array('id'=>$id));
		if($query->num_rows()>0)
		{
			$row = $query->row();
			return $row->name;
		}
		else
		{
			return 'N/A';
		}
	}
}

if ( ! function_exists('remove_featured_if_expired'))
{
	function remove_featured_if_expired ()
	{
		$CI = get_instance();

		$CI->load->helper('date');
		$datestring = "%Y-%m-%d";
		$time  = time();
		$today = mdate($datestring, $time);

		$option = get_option('last_feature_cron_date');

		if((is_array($option) && isset($option['error'])) || strtotime($option->values)<strtotime($today))
		{
			$CI->load->database();
			$query = $CI->db->get_where('posts',array('status'=>1,'featured'=>1));
			foreach($query->result() as $row)
			{
				$expirtion_date = get_post_meta($row->id,'feature_expirtion_date','');
				if($expirtion_date!='')
				{
					#echo $today.' '.$expirtion_date;die;

					if(strtotime($today)>strtotime($expirtion_date))
					{
						$CI->db->update('posts',array('featured'=>0),array('id'=>$row->id));
					}
				}
			}
			
			add_option('last_feature_cron_date',$today);
		}

	}
}

if ( ! function_exists('show_price'))
{
	function show_price ($price,$propert_id='')
	{
		$CI = get_instance();
		$CI->config->load('realcon');
	    $decimal_point = ($CI->config->item('decimal_point')!='')?$CI->config->item('decimal_point'):'.';
	    $thousand_separator = ($CI->config->item('thousand_separator')!='')?$CI->config->item('thousand_separator'):',';

		$currency_placing = get_settings('realestate_settings','currency_placing','before_with_no_gap');
		if($propert_id!='' && get_post_meta($propert_id,'price_negotiable','0')==1) {
			return "Price negotiable";
		}
		else if($currency_placing=='before_with_no_gap')
		{
			return $CI->session->userdata('system_currency').''.number_format($price, 0, $decimal_point, $thousand_separator);
		}
		else if($currency_placing=='before_with_gap')
		{
			return $CI->session->userdata('system_currency').' '.number_format($price, 0, $decimal_point, $thousand_separator);
		}
		else if($currency_placing=='after_with_no_gap')
		{
			return number_format($price, 0, $decimal_point, $thousand_separator).''.$CI->session->userdata('system_currency');
		}
		else
		{
			return number_format($price, 0, $decimal_point, $thousand_separator).' '.$CI->session->userdata('system_currency');
		}
	}
}

if ( ! function_exists('show_package_price'))
{
	function show_package_price ($price)
	{
		$CI = get_instance();

		$CI->config->load('realcon');
	    $decimal_point = ($CI->config->item('decimal_point')!='')?$CI->config->item('decimal_point'):'.';
		$thousand_separator = ($CI->config->item('thousand_separator')!='')?$CI->config->item('thousand_separator'):',';
	    
		$currency_placing = get_settings('realestate_settings','currency_placing','before_with_no_gap');
		
		$currency = get_settings('paypal_settings','currency','USD');

		if($currency_placing=='before_with_no_gap')
		{
			return $currency.''.number_format($price, 0, $decimal_point, $thousand_separator);
		}
		else if($currency_placing=='before_with_gap')
		{
			return $currency.' '.number_format($price, 0, $decimal_point, $thousand_separator);
		}
		else if($currency_placing=='after_with_no_gap')
		{
			return number_format($price, 0, $decimal_point, $thousand_separator).''.$currency;
		}
		else
		{
			return number_format($price, 0, $decimal_point, $thousand_separator).' '.$currency;
		}
	}
}

if ( ! function_exists('is_user_package_expired'))
{
	function is_user_package_expired ($user_id)
	{

		$CI = get_instance();
		$CI->load->database();
		$query = $CI->db->get_where('users',array('id'=>$user_id));
		if($query->num_rows()>0)
		{
			$row = $query->row();
			if($row->user_type==1)
				return 0; #admin will have no expire date
		}

		$expirtion_date = get_user_meta($user_id,'expirtion_date','');
		if($expirtion_date=='')
			return 1;
		elseif (strtotime($expirtion_date)<time()) 
		{
			return 1;
		}
		else
			return 0;
	}
}

if ( ! function_exists('get_package_name_by_id'))
{
	function get_package_name_by_id ($id)
	{
		$CI = get_instance();
		$query = $CI->db->get_where('packages',array('id'=>$id));
		if($query->num_rows()>0)
		{
			$row = $query->row();
			return $row->title;
		}
		else
		{
			return 'N/A';
		}
	}
}

if ( ! function_exists('get_user_properties_count'))
{
	function get_user_properties_count ($user_id)
	{
		$CI = get_instance();
		$CI->load->database();
		$CI->db->where('created_by',$user_id);
		$query = $CI->db->get_where('posts',array('status !='=>0));
		return $query->num_rows();
	}
}

if ( ! function_exists('get_all_currencies'))
{
	function get_all_currencies($key=0)
	{
		$currencies= array(
			"ALL"=> array("Albania, Leke", "4c, 65, 6b"),
			"AFN"=> array("Afghanistan, Afghanis", "60b"),
			"ARS"=> array("Argentina, Pesos", "24"),
			"AWG"=> array("Aruba, Guilders (also called Florins)", "192"),
			"AUD"=> array("Australia, Dollars", "24"),
			"AZN"=> array("Azerbaijan, New Manats", "43c, 430, 43d"),
			"BSD"=> array("Bahamas, Dollars", "24"),
			"BBD"=> array("Barbados, Dollars", "24"),
			"BYR"=> array("Belarus, Rubles", "70, 2e"),
			"BZD"=> array("Belize, Dollars", "42, 5a, 24"),
			"BMD"=> array("Bermuda, Dollars", "24"),
			"BOB"=> array("Bolivia, Bolivianos", "24, 62"),
			"BAM"=> array("Bosnia and Herzegovina, Convertible Marka", "4b, 4d"),
			"BWP"=> array("Botswana, Pulas", "50"),
			"BGN"=> array("Bulgaria, Leva", "43b, 432"),
			"BRL"=> array("Brazil, Reais", "52, 24"),
			"BND"=> array("Brunei Darussalam, Dollars", "24"),
			"KHR"=> array("Cambodia, Riels", "17db"),
			"XOF"=> array("Cameroon, CFA franc",""),
			"CAD"=> array("Canada, Dollars", "24"),
			"KYD"=> array("Cayman Islands, Dollars", "24"),
			"CLP"=> array("Chile, Pesos", "24"),
			"CNY"=> array("China, Yuan Renminbi", "a5"),
			"COP"=> array("Colombia, Pesos", "24"),
			"CRC"=> array("Costa Rica, Colón", "20a1"),
			"HRK"=> array("Croatia, Kuna", "6b, 6e"),
			"CUP"=> array("Cuba, Pesos", "20b1"),
			"CZK"=> array("Czech Republic, Koruny", "4b, 10d"),
			"DKK"=> array("Denmark, Kroner", "6b, 72"),
			"DOP"=> array("Dominican Republic, Pesos", "52, 44, 24"),
			"XCD"=> array("East Caribbean, Dollars", "24"),
			"EGP"=> array("Egypt, Pounds", "a3"),
			"SVC"=> array("El Salvador, Colones", "24"),
			"EEK"=> array("Estonia, Krooni", "6b, 72"),
			"EUR"=> array("Euro", "20ac"),
			"FKP"=> array("Falkland Islands, Pounds", "a3"),
			"FJD"=> array("Fiji, Dollars", "24"),
			"GHC"=> array("Ghana, Cedis", "a2"),
			"GIP"=> array("Gibraltar, Pounds", "a3"),
			"GTQ"=> array("Guatemala, Quetzales", "51"),
			"GGP"=> array("Guernsey, Pounds", "a3"),
			"GYD"=> array("Guyana, Dollars", "24"),
			"HNL"=> array("Honduras, Lempiras", "4c"),
			"HKD"=> array("Hong Kong, Dollars", "24"),
			"HUF"=> array("Hungary, Forint", "46, 74"),
			"ISK"=> array("Iceland, Kronur", "6b, 72"),
			"INR"=> array("India, Rupees", "20a8"),
			"IDR"=> array("Indonesia, Rupiahs", "52, 70"),
			"IRR"=> array("Iran, Rials", "fdfc"),
			"IMP"=> array("Isle of Man, Pounds", "a3"),
			"ILS"=> array("Israel, New Shekels", "20aa"),
			"JMD"=> array("Jamaica, Dollars", "4a, 24"),
			"JPY"=> array("Japan, Yen", "a5"),
			"JEP"=> array("Jersey, Pounds", "a3"),
			"KZT"=> array("Kazakhstan, Tenge", "43b, 432"),
			"KES"=> array("Kenyan Shilling", "4b, 73, 68, 73"),
			"KGS"=> array("Kyrgyzstan, Soms", "43b, 432"),
			"LAK"=> array("Laos, Kips", "20ad"),
			"LVL"=> array("Latvia, Lati", "4c, 73"),
			"LBP"=> array("Lebanon, Pounds", "a3"),
			"LRD"=> array("Liberia, Dollars", "24"),
			"LTL"=> array("Lithuania, Litai", "4c, 74"),
			"MKD"=> array("Macedonia, Denars", "434, 435, 43d"),
			"MAD"=> array("Morocco, Dirham", "2e, 62f, 2e, 645"),
			"MYR"=> array("Malaysia, Ringgits", "52, 4d"),
			"MUR"=> array("Mauritius, Rupees", "20a8"),
			"MXN"=> array("Mexico, Pesos", "24"),
			"MNT"=> array("Mongolia, Tugriks", "20ae"),
			"MZN"=> array("Mozambique, Meticais", "4d, 54"),
			"NAD"=> array("Namibia, Dollars", "24"),
			"NPR"=> array("Nepal, Rupees", "20a8"),
			"ANG"=> array("Netherlands Antilles, Guilders (also called Florins)", "192"),
			"NZD"=> array("New Zealand, Dollars", "24"),
			"NIO"=> array("Nicaragua, Cordobas", "43, 24"),
			"NGN"=> array("Nigeria, Nairas", "20a6"),
			"KPW"=> array("North Korea, Won", "20a9"),
			"NOK"=> array("Norway, Krone", "6b, 72"),
			"OMR"=> array("Oman, Rials", "fdfc"),
			"PKR"=> array("Pakistan, Rupees", "20a8"),
			"PAB"=> array("Panama, Balboa", "42, 2f, 2e"),
			"PYG"=> array("Paraguay, Guarani", "47, 73"),
			"PEN"=> array("Peru, Nuevos Soles", "53, 2f, 2e"),
			"PHP"=> array("Philippines, Pesos", "50, 68, 70"),
			"PLN"=> array("Poland, Zlotych", "7a, 142"),
			"QAR"=> array("Qatar, Rials", "fdfc"),
			"RON"=> array("Romania, New Lei", "6c, 65, 69"),
			"RUB"=> array("Russia, Rubles", "440, 443, 431"),
			"SHP"=> array("Saint Helena, Pounds", "a3"),
			"SAR"=> array("Saudi Arabia, Riyals", "fdfc"),
			"RSD"=> array("Serbia, Dinars", "414, 438, 43d, 2e"),
			"SCR"=> array("Seychelles, Rupees", "20a8"),
			"SGD"=> array("Singapore, Dollars", "24"),
			"SBD"=> array("Solomon Islands, Dollars", "24"),
			"SOS"=> array("Somalia, Shillings", "53"),
			"ZAR"=> array("South Africa, Rand", "52"),
			"KRW"=> array("South Korea, Won", "20a9"),
			"LKR"=> array("Sri Lanka, Rupees", "20a8"),
			"SEK"=> array("Sweden, Kronor", "6b, 72"),
			"CHF"=> array("Switzerland, Francs", "43, 48, 46"),
			"SRD"=> array("Suriname, Dollars", "24"),
			"SYP"=> array("Syria, Pounds", "a3"),
			"TWD"=> array("Taiwan, New Dollars", "4e, 54, 24"),
			"THB"=> array("Thailand, Baht", "e3f"),
			"TTD"=> array("Trinidad and Tobago, Dollars", "54, 54, 24"),
			"TRY"=> array("Turkey, Lira", "54, 4c"),
			"TRL"=> array("Turkey, Liras", "20a4"),
			"TVD"=> array("Tuvalu, Dollars", "24"),
			"UAH"=> array("Ukraine, Hryvnia", "20b4"),
			"AED"=>array("United Arab Emirates, Dirham","62f, 2e, 625"),
			"GBP"=> array("United Kingdom, Pounds", "a3"),
			"USD"=> array("United States of America, Dollars", "24"),
			"UYU"=> array("Uruguay, Pesos", "24, 55"),
			"UZS"=> array("Uzbekistan, Sums", "43b, 432"),
			"VEF"=> array("Venezuela, Bolivares Fuertes", "42, 73"),
			"VND"=> array("Vietnam, Dong", "20ab"),
			"YER"=> array("Yemen, Rials", "fdfc"),
			"ZWD"=> array("Zimbabwe, Zimbabwe Dollars", "5a, 24"));

		return $currencies;
	}
}

if ( ! function_exists('get_currency_icon'))
{
	function get_currency_icon($currency = null)
	{
		$currencies = get_all_currencies();
		$currencySymbol = '';
	
		if($currency == null) {
    		return 'N/A';
      	}

    	$symbol = $currencies[$currency][1];
    	if($symbol=='')
    		return $currency;
    	$symbols = explode(', ', $symbol);
	    if(is_array($symbols)) {
	      $symbol = "";
	      foreach($symbols as $temp) {
	        $symbol .= '&#x'.$temp.';';
	        }
	    }
	    else {
	      $symbol = '&#x'.$symbol.';';
	    }

	    return $symbol;
	}
}

if ( ! function_exists('get_payment_status_title_by_value'))
{
	function get_payment_status_title_by_value($key=0)
	{
		$types = array("DBC_DELETED","DBC_ACTIVE","DBC_PENDING");
		return (isset($types[$key]))?lang_key($types[$key]):'N/A';
	}
}

if ( ! function_exists('get_status_title_by_value'))
{
	function get_status_title_by_value($key=0)
	{
		$types = array("DBC_DELETED","DBC_ACTIVE","DBC_PENDING","DBC_REPORTED");
		
		$front = '';
		$back  = '</span>';
		if($types[$key]=='DBC_DELETED')
		{
			$front = '<span class="label label-danger">';
		}
		else if($types[$key]=='DBC_ACTIVE')
		{
			$front = '<span class="label label-success">';
		}
		else if($types[$key]=='DBC_PENDING')
		{
			$front = '<span class="label label-primary">';
		}
		else
		{
			$front = '<span class="label label-warning">';
		}
		return (isset($types[$key]))?$front.lang_key($types[$key]).$back:'N/A';
	}
}

if ( ! function_exists('get_all_countries'))
{
	function get_all_countries()
	{
		$CI = get_instance();
		$CI->load->database();
		$query = $CI->db->get_where('locations',array('type'=>'country','status'=>1));
		return $query;
	}
}

if ( ! function_exists('add_user_meta'))
{
	function add_user_meta ($user_id,$key,$value)
	{
		$CI = get_instance();
		$CI->load->database();
		$query = $CI->db->get_where('user_meta',array('user_id'=>$user_id,'key'=>$key));
		if($query->num_rows()>0)
		{
			$CI->db->update('user_meta',array('value'=>$value),array('user_id'=>$user_id,'key'=>$key));
		}
		else
		{
			$CI->db->insert('user_meta',array('user_id'=>$user_id,'key'=>$key,'value'=>$value));
		}
	}
}

if ( ! function_exists('get_user_meta'))
{
	function get_user_meta ($user_id,$key,$default='')
	{
		$CI = get_instance();
		$CI->load->database();
		$query = $CI->db->get_where('user_meta',array('user_id'=>$user_id,'key'=>$key));
		if($query->num_rows()>0)
		{
			$row = $query->row();
			return $row->value;
		}
		else
		{
			return $default;
		}
	}
}

#-----------------

if ( ! function_exists('add_post_meta'))
{
	function add_post_meta ($post_id,$key,$value)
	{
		$CI = get_instance();
		$CI->load->database();
		$query = $CI->db->get_where('post_meta',array('post_id'=>$post_id,'key'=>$key));
		if($query->num_rows()>0)
		{
			$CI->db->update('post_meta',array('value'=>$value),array('post_id'=>$post_id,'key'=>$key));
		}
		else
		{
			$CI->db->insert('post_meta',array('post_id'=>$post_id,'key'=>$key,'value'=>$value));
		}
	}
}

if ( ! function_exists('get_post_meta'))
{
	function get_post_meta ($post_id,$key,$default='n/a')
	{
		$CI = get_instance();
		$CI->load->database();
		$query = $CI->db->get_where('post_meta',array('post_id'=>$post_id,'key'=>$key));
		if($query->num_rows()>0)
		{
			$row = $query->row();
			return $row->value;
		}
		else
		{
			return $default;
		}
	}
}

if ( ! function_exists('get_post_custom_value'))
{
	function get_post_custom_value ($post_id,$key,$field,$default='n/a')
	{
		$CI = get_instance();
		$CI->load->database();
		$query = $CI->db->get_where('post_meta',array('post_id'=>$post_id,'key'=>$key));
		if($query->num_rows()>0)
		{
			$row = $query->row();
			if($row->value!='')
			{
				$val = (array)json_decode($row->value);
				return (isset($val[$field]))?$val[$field]:$default;
			}
		}
		else
		{
			return $default;
		}
	}
}


if ( ! function_exists('get_featured_photo_by_id'))
{
	function get_featured_photo_by_id($img='')
	{
		if($img=='')
		return base_url('assets/admin/img/preview.jpg');
		else
		return base_url('uploads/thumbs/'.$img);
	}
}


if ( ! function_exists('get_all_facilities'))
{
	function get_all_facilities()
	{
		$CI = get_instance();
		$CI->load->database();
		$CI->db->order_by('title','asc');
		$query = $CI->db->get_where('facilities',array('status'=>1));
		return $query;
	}
}

if ( ! function_exists('get_title_for_edit_by_id_lang'))
{
	function get_title_for_edit_by_id_lang($id,$lang)
	{
		$CI = get_instance();
		$CI->load->database();
		$query = $CI->db->get_where('post_meta',array('post_id'=>$id,'key'=>'title','status'=>1));
		if($query->num_rows()>0)
		{
			$row = $query->row();
			$data = ($row->value=='')?array():(array)json_decode($row->value);
			if(isset($data[$lang]) && $data[$lang]!='')
				return $data[$lang];
			else
			{
				$text = '';
				foreach ($data as $key => $value) {
					$text = $value;break;	
				}
				return $text;
			}
		}
		else
			return 'N/A';

		return $query;
	}
}

if ( ! function_exists('get_description_for_edit_by_id_lang'))
{
	function get_description_for_edit_by_id_lang($id,$lang)
	{
		$CI = get_instance();
		$CI->load->database();
		$query = $CI->db->get_where('post_meta',array('post_id'=>$id,'key'=>'description','status'=>1));
		if($query->num_rows()>0)
		{
			$row = $query->row();
			$data = ($row->value=='')?array():(array)json_decode($row->value);
			if(isset($data[$lang]) && $data[$lang]!='')
				return $data[$lang];
			else
			{
				$text = '';
				foreach ($data as $key => $value) {
					$text = $value;break;	
				}
				return $text;
			}
		}
		else
			return 'N/A';

		return $query;
	}
}



if ( ! function_exists('create_square_thumb'))
{
	function create_square_thumb($img,$dest)
	{
		$seg = explode('.',$img);
		$thumbType    = 'jpg';
		$thumbSize    = 300;
		$thumbPath    = $dest;
		$thumbQuality = 100;

		$last_index = count($seg);
		$last_index--;

		if($seg[$last_index]=='jpg' || $seg[$last_index]=='JPG' || $seg[$last_index]=='jpeg')
		{
			if (!$full = imagecreatefromjpeg($img)) {
			    return 'error';
			}			
		}
		else if($seg[$last_index]=='png')
		{
			if (!$full = imagecreatefrompng($img)) {
			    return 'error';
			}			
		}
		else if($seg[$last_index]=='gif')
		{
			if (!$full = imagecreatefromgif($img)) {
			    return 'error';
			}			
		}
		 
	    $width  = imagesx($full);
	    $height = imagesy($full);
		 
	    /* work out the smaller version, setting the shortest side to the size of the thumb, constraining height/wight */
	    if ($height > $width) {
	      $divisor = $width / $thumbSize;
	    } else {
	      $divisor = $height / $thumbSize;
	    }
		 
	    $resizedWidth   = ceil($width / $divisor);
	    $resizedHeight  = ceil($height / $divisor);
		 
	    /* work out center point */
	    $thumbx = floor(($resizedWidth  - $thumbSize) / 2);
	    $thumby = floor(($resizedHeight - $thumbSize) / 2);
		 
	    /* create the small smaller version, then crop it centrally to create the thumbnail */
	    $resized  = imagecreatetruecolor($resizedWidth, $resizedHeight);
	    $thumb    = imagecreatetruecolor($thumbSize, $thumbSize);

	    imagealphablending( $resized, false );
		imagesavealpha( $resized, true );

		imagealphablending( $thumb, false );
		imagesavealpha( $thumb, true );

	    imagecopyresized($resized, $full, 0, 0, 0, 0, $resizedWidth, $resizedHeight, $width, $height);
	    imagecopyresized($thumb, $resized, 0, 0, $thumbx, $thumby, $thumbSize, $thumbSize, $thumbSize, $thumbSize);
		 
		 $name = name_from_url($img);

	    imagejpeg($thumb, $thumbPath.str_replace('_large.jpg', '_thumb.jpg', $name), $thumbQuality);
	}
	
}

if ( ! function_exists('humanTiming'))
{
	function humanTiming ($time)
	{

	    $time = time() - $time; // to get the time since that moment

	    $tokens = array (
	        31536000 => 'year',
	        2592000 => 'month',
	        604800 => 'week',
	        86400 => 'day',
	        3600 => 'hour',
	        60 => 'minute',
	        1 => 'second'
	    );

	    foreach ($tokens as $unit => $text) {
	        if ($time < $unit) continue;
	        $numberOfUnits = floor($time / $unit);
	        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'').' ago';
	    }

	}
}

if ( ! function_exists('next_post_by_id'))
{

	function next_post_by_id($id)
	{
		$CI = get_instance();
		$CI->load->database();
		$CI->db->where('id >',$id);
		$query = $CI->db->get_where('posts',array('status'=>1),1,0);
		if($query->num_rows()>0)
		{
			$row = $query->row_array();
			return $row;
		}
		else
			return array('error'=>'not_found');
	}
}

if ( ! function_exists('prev_post_by_id'))
{

	function prev_post_by_id($id)
	{
		$CI = get_instance();
		$CI->load->database();
		$CI->db->where('id <',$id);
		$CI->db->order_by('id','desc');
		$query = $CI->db->get_where('posts',array('status'=>1),1,0);
		if($query->num_rows()>0)
		{
			$row = $query->row_array();
			return $row;
		}
		else
			return array('error'=>'not_found');
	}
}


if ( ! function_exists('social_sharing_meta_tags_for_post'))
{

	function social_sharing_meta_tags_for_post($post='')
	{
		if($post!='' && $post->num_rows()>0)
		{
			$CI = get_instance();
			$post = $post->row();
			$curr_lang = ($CI->uri->segment(1)!='')?$CI->uri->segment(1):'en';
			$site_title = get_settings('site_settings','site_title','Memento');
			$title = get_title_for_edit_by_id_lang($post->id,$curr_lang);
            $detail_link = site_url('property/'.$post->unique_id.'/'.dbc_url_title($title));

			$meta = '<meta name="twitter:card" content="photo" />'."\n".
					'<meta name="twitter:site" content="'.$site_title.'" />'."\n".
					'<meta name="twitter:image" content="'.get_featured_photo_by_id($post->featured_img).'" />'."\n".
					'<meta property="og:title" content="'.$title.'" />'."\n".
					'<meta property="og:site_name" content="'.$site_title.'" />'."\n".
					'<meta property="og:url" content="'.$detail_link.'" />'."\n".
					'<meta property="og:description" content="Click to view detail..." />'."\n".
					'<meta property="og:type" content="article" />'."\n".
					'<meta property="og:image" content="'.get_featured_photo_by_id($post->featured_img).'" />'.
					'<meta property="fb:app_id" content="'.get_settings('memento_settings','fb_app_id','none').'" />';
		 
		 	return $meta;			
		}
		else
			return '';
	}
}

if(!function_exists('social_sharing_meta_tags_for_blog'))
{

    function social_sharing_meta_tags_for_blog($blog_meta='')
    {
        if($blog_meta!='')
        {
            $site_title = get_settings('site_settings','site_title','Memento');
            $detail_link = site_url('show/postdetail/'.$blog_meta->id.'/'.dbc_url_title($blog_meta->title));
            $image_path=(!empty($blog_meta->featured_img)? base_url().'uploads/thumbs/'.$blog_meta->featured_img : base_url().'assets/admin/img/preview.jpg');

            $remove_tag_text = (!empty($blog_meta->description)?strip_tags($blog_meta->description):'');
            $remove_tag_text = str_replace('"', '', $remove_tag_text);
            $description = (strlen($remove_tag_text) > 160) ? substr($remove_tag_text,0,160) : $remove_tag_text;           

            $meta = '<meta name="twitter:card" content="photo" />'."\n".
                '<meta name="twitter:site" content="'.$site_title.'" />'."\n".
                '<meta name="twitter:image" content="'.$image_path.'" />'."\n".
                '<meta property="og:title" content="'.$blog_meta->title.'" />'."\n".
                '<meta property="og:site_name" content="'.$site_title.'" />'."\n".
                '<meta property="og:url" content="'.$detail_link.'" />'."\n".
                '<meta property="og:description" content="'.$description.'" />'."\n".
                '<meta property="og:type" content="article" />'."\n".
                '<meta property="og:image" content="'.$image_path.'" />'."\n".
                '<meta property="fb:app_id" content="'.get_settings('memento_settings','fb_app_id','none').'" />';

            return $meta;

        }
        else
            return '';
    }
}

if ( ! function_exists('fileinfo_from_url'))
{

	function fileinfo_from_url($filePath)
	{
	 $fileParts = pathinfo($filePath);

	 if(!isset($fileParts['filename']))
	 {$fileParts['filename'] = substr($fileParts['basename'], 0, strrpos($fileParts['basename'], '.'));}
	 
	 return $fileParts;
	}
}

if ( ! function_exists('name_from_url'))
{

	function name_from_url($filePath)
	{
	 $fileParts = pathinfo($filePath);

	 if(!isset($fileParts['filename']))
	 {$fileParts['filename'] = substr($fileParts['basename'], 0, strrpos($fileParts['basename'], '.'));}
	 
	 return $fileParts['basename'];
	}
}


if ( ! function_exists('image_from_url'))
{
	function image_from_url ($url,$name='')
	{
		if($name=='')
		$name = name_from_url($url);
		$ch = curl_init($url);
		$fp = fopen('uploads/url/'.$name, 'wb');
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_exec($ch);
		curl_close($ch);
		fclose($fp);

		return base_url('uploads/url/'.$name);
	}
}


if ( ! function_exists('gif2jpeg'))
{
	function gif2jpeg($p_fl, $p_new_fl='', $bgcolor=false)
	{
	  	list($wd, $ht, $tp, $at)=getimagesize($p_fl);
		$img_src=imagecreatefromgif($p_fl);
		$img_dst=imagecreatetruecolor($wd,$ht);
		$clr['red']=255;
		$clr['green']=255;
		$clr['blue']=255;
		
		if(is_array($bgcolor)) $clr=$bgcolor;
		$kek=imagecolorallocate($img_dst,
		$clr['red'],$clr['green'],$clr['blue']);
		imagefill($img_dst,0,0,$kek);
		imagecopyresampled($img_dst, $img_src, 0, 0, 0, 0, $wd, $ht, $wd, $ht);
	  	$draw=true;
		if(strlen($p_new_fl)>0)
		{
		    if($hnd=fopen($p_new_fl,'w'))
		    {
		    	$draw=false;
		    	fclose($hnd);
		    }
		}
		
		if(true==$draw)
		{
			header("Content-type: image/jpeg");
		    imagejpeg($img_dst);
		}
		else 
			imagejpeg($img_dst, $p_new_fl);
		  
		imagedestroy($img_dst);
		imagedestroy($img_src);
	}
}


if ( ! function_exists('resized_to_fixed_width'))
{

	function resized_to_fixed_width($img,$width=500)
	{
		$CI = get_instance();
		$config['image_library'] = 'gd2';
		$config['source_image'] = $img;
		$config['maintain_ratio'] = TRUE;
		$config['width'] = $width;

		$CI->load->library('image_lib', $config);

		$CI->image_lib->resize();
	}
}

if ( ! function_exists('create_rect_thumb'))
{

	function create_rect_thumb($img,$dest,$ratio=3)
	{

		$seg = explode('.',$img);	//explde the source to get the image extension
		$thumbType    = 'jpg';		//generated thumb will be of type jpg
		$thumbPath    = $dest;	//destination path of the thumb -- original image name will be appended
		$thumbQuality = 80;				//quality of the thumbnail (in percent)

		//chech the image type and create image accordingly
		if($seg[2]=='jpg' || $seg[2]=='JPG' || $seg[2]=='jpeg')
		{
			if (!$full = imagecreatefromjpeg($img)) {
			    return 'error';
			}
		}
		else if($seg[2]=='png')
		{
			if (!$full = imagecreatefrompng($img)) {
			    return 'error';
			}			
		}
		else if($seg[2]=='gif')
		{
			if (!$full = imagecreatefromgif($img)) {
			    return 'error';
			}			
		}

	    $width  = imagesx($full);
	    $height = imagesy($full);

	    /*wourk out the thumbnail size*/
	    $resizedHeight	= min($width*$ratio/8,$height);
	    $resizedWidth	= $width;
		 
	    /* work out starting point */
	    $thumbx = 0;	// x always starts at zero -- the thumbnail gets the same width as the source image
	    $extra_height = $height - $resizedHeight;
	    $thumby = floor(($extra_height) / 2);
		 
	    /* create the small smaller version, then crop it centrally to create the thumbnail */
	    $resized  = imagecreatetruecolor($resizedWidth, $resizedHeight);
	    imagealphablending( $resized, false );
		imagesavealpha( $resized, true );

	    imagecopy($resized, $full,0,0,$thumbx,$thumby,$resizedWidth,$resizedHeight);
		 
		$name = name_from_url($img);

	    imagejpeg($resized, $thumbPath.str_replace('_large.jpg', '_thumb.jpg', $name), $thumbQuality);
	}
}



if ( ! function_exists('put_watermark'))
{
	function put_watermark($src,$text='')
	{
		$CI = get_instance();
		$CI->load->library('image_lib');
		$config['source_image'] = $src;
		$config['wm_text'] = $text;
		$config['wm_type'] = 'text';
		$config['wm_font_path'] = './system/fonts/texb.ttf';
		$config['wm_font_size'] = '16';
		$config['wm_font_color'] = 'ffffff';
		$config['wm_vrt_alignment'] = 'bottom';
		$config['wm_hor_alignment'] = 'center';
		$config['wm_padding'] = '0';

		$CI->image_lib->initialize($config);

		$CI->image_lib->watermark();
	}
}

if ( ! function_exists('filePath'))
{
	function filePath($filePath)
	{
		$fileParts = pathinfo($filePath);

		if(!isset($fileParts['filename']))
		{
			$fileParts['filename'] = substr($fileParts['basename'], 0, strrpos($fileParts['basename'], '.'));
		}
	 
		return $fileParts;
	}
}


if ( ! function_exists('is_animated'))
{
	function is_animated($filename)
	{
        $filecontents=file_get_contents($filename);

        $str_loc=0;
        $count=0;
        while ($count < 2) # There is no point in continuing after we find a 2nd frame
        {
            $where1=strpos($filecontents,"\x00\x21\xF9\x04",$str_loc);
            if ($where1 === FALSE)
            {
                break;
            }
            else
            {
                $str_loc=$where1+1;
                $where2=strpos($filecontents,"\x00\x2C",$str_loc);
                if ($where2 === FALSE)
                {
                    break;
                }
                else
                {
                    if ($where1+8 == $where2)
                    {
                        $count++;
                    }
                                $str_loc=$where2+1;
                }
            }
        }

        if ($count > 1)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
	}
}

if ( ! function_exists('videoType'))
{
	function videoType($url) 
	{
	    if (strpos($url, 'youtube') > 0) 
	    {
	        return 'youtube';
	    } 
	    elseif (strpos($url, 'vimeo') > 0) 
	    {
	        return 'vimeo';
	    }
	    else 
	    {
	        return 'unknown';
	    }
	}
}


if ( ! function_exists('render_widgets'))
{
	function render_widgets($position='')
	{
		$CI 		= get_instance();
		$CI->load->helper('inflector');	
		$CI->load->helper('file');
		$widgets 	= get_widgets_by_position($position);	
		foreach($widgets as $row)
		{
			$query = $CI->db->get_where('widgets',array('alias'=>$row));
			if($query->num_rows()>0)
			{
				$row = $query->row();
				if($row->status==1)
				{
					if(read_file('./application/modules/widgets/'.$row->alias.'.php')!=FALSE)
						require_once'./application/modules/widgets/'.$row->alias.'.php';					
				}
				else if($row->status==0)
					echo '';
				else
					echo '';
			}
			else
			{
				echo '';
			}
				
		}
	}
}

if ( ! function_exists('load_view'))
{
	function load_view($view='',$data=array(),$buffer=FALSE,$theme='')
	{
		$CI 	= get_instance();
		if($theme=='')
		$theme 	= get_active_theme();
		if($buffer==FALSE)
		{
			if(@file_exists(APPPATH."modules/themes/views/".$theme."/".$view.".php"))
			$CI->load->view('themes/'.$theme.'/'.$view,$data);
			else
			$CI->load->view('themes/default/'.$view,$data);	
		}
		else
		{
			if(@file_exists(APPPATH."modules/themes/views/".$theme."/".$view.".php"))
			$view_data = $CI->load->view('themes/'.$theme.'/'.$view,$data,TRUE);
			else
			$view_data = $CI->load->view('themes/default/'.$view,$data,TRUE);	
			return $view_data;
		}
	}
}

if ( ! function_exists('load_template'))
{
	function load_template($data=array(),$theme='',$tmpl='template_view')
	{
		$row 	= get_option('site_settings');
		if(is_array($row) && isset($row['error']))
		{
			echo 'Site settings not found.error on : epbase_helper';
			die();
		}
		else
		{
			$values 		= json_decode($row->values);
			$data['title'] 	= $values->site_title;
		}

		load_view($tmpl,$data);
	}
}

if ( ! function_exists('get_active_theme'))
{
	function get_active_theme()
	{
		$row = get_option('active_theme');
		if(is_array($row) && isset($row['error']))
		{
			return 'default';
		}
		else
			return $row->values;
	}
}

if ( ! function_exists('get_option'))
{
	function get_option($key='')
	{
		$CI = get_instance();
		$query = $CI->db->get_where('options',array('key'=>$key,'status'=>1));		
		if($query->num_rows()>0)
			return $query->row();
		else
			return array('error'=>'Key not found');
	}
}

if ( ! function_exists('update_option'))
{
	function update_option($key='',$values=array())
	{
		$CI = get_instance();
		$data['values'] = json_encode($values);
		echo $key;
		print_r($data);
		$query = $CI->db->update('options',$data,array('key'=>$key));		
	}
}


if ( ! function_exists('get_plugins'))
{
	function get_plugins()
	{
		$CI = get_instance();
		$query = $CI->db->get_where('plugins',array('status'=>1));		
		return $query;
	}
}

if ( ! function_exists('get_widgets_by_position'))
{
	function get_widgets_by_position($pos='')
	{
		$CI = get_instance();
		$positions = get_option('positions');
		$positions = json_decode($positions->values);
		$widgets = array();
		foreach($positions as $position)
		{
			if($position->name==$pos)
			{
				if(isset($position->widgets))
				$widgets = $position->widgets;
			}
		}
		return $widgets;
	}
}

if ( ! function_exists('configPagination'))
{
	function configPagination($url,$total_rows,$segment,$per_page=10)
	{
		$CI = get_instance();
		$CI->load->library('pagination');
		$config['base_url'] 		= site_url($url);
		$config['total_rows'] 		= $total_rows;
		$config['per_page'] 		= $per_page;
		$config['uri_segment'] 		= $segment;
		$config['full_tag_open'] 	= '<div class="pagination"><ul>';
		$config['full_tag_close'] 	= '</ul></div>';
		$config['num_tag_open'] 	= '<li>';
		$config['num_tag_close'] 	= '</li>';
		$config['cur_tag_open'] 	= '<li class="active"><a href="#">';
		$config['cur_tag_close']	= '</a></li>';
		$config['num_links'] 		= 5;
		$config['next_tag_open'] 	= "<li>";
		$config['next_tag_close'] 	= "</li>";
		$config['prev_tag_open'] 	= "<li>";
		$config['prev_tag_close'] 	= "</li>";
		
		$config['first_link'] 	= FALSE;
		$config['last_link'] 	= FALSE;
		$CI->pagination->initialize($config);
		
		return $CI->pagination->create_links();
	}
}


if ( ! function_exists('get_category_title_by_id'))
{
	function get_category_title_by_id($id='')
	{
		if($id==0)
			return 'No parent';
		$CI = get_instance();
		$CI->load->database();
		$query = $CI->db->get_where('categories',array('id'=>$id));
		if($query->num_rows()>0)
		{
			$row = $query->row();
			return $row->title;
		}
		else
			return 'N/A';
	}
}

if ( ! function_exists('get_profile_photo_by_id'))
{
	function get_profile_photo_by_id($id='',$type='')
	{
		if($id==0)
			return 'No found';

		$CI = get_instance();
		$CI->load->database();
		$query = $CI->db->get_where('users',array('id'=>$id));
		if($query->num_rows()>0)
		{
			$row = $query->row();
			if($row->profile_photo=='')
				return base_url().'uploads/profile_photos/nophoto-'.strtolower($row->gender).'.jpg';
			
			if($type=='thumb')
			return base_url().'uploads/profile_photos/thumb/'.$row->profile_photo;
			else
			return base_url().'uploads/profile_photos/'.$row->profile_photo;
		}
		else
		{

			return base_url().'uploads/profile_photos/nophoto-female.jpg';
		}
	}
}

if ( ! function_exists('get_profile_photo_name_by_username'))
{
	function get_profile_photo_name_by_username($username='',$type='thumb')
	{
		if($username=='')
			return 'Not found';

		$CI = get_instance();
		$CI->load->database();
		$query = $CI->db->get_where('users',array('user_name'=>$username));
		if($query->num_rows()>0)
		{
			$row = $query->row();
			if($row->profile_photo!='')
			return $row->profile_photo;
			else
			return 'nophoto-'.strtolower($row->gender).'.jpg';
		}
		else
			return 'nophoto-.jpg';
	}
}

if ( ! function_exists('get_profile_photo_by_username'))
{
	function get_profile_photo_by_username($username='',$type='thumb')
	{
		if($username=='')
			return 'Not found';

		$CI = get_instance();
		$CI->load->database();
		$query = $CI->db->get_where('users',array('user_name'=>$username));
		if($query->num_rows()>0)
		{
			$row = $query->row();
			if($row->profile_photo!='')
			return base_url().'uploads/profile_photos/'.$type.'/'.$row->profile_photo;
			else
			return base_url().'uploads/profile_photos/nophoto-'.strtolower($row->gender).'.jpg';
		}
		else
			return base_url().'uploads/profile_photos/nophoto-female.jpg';
	}
}

if ( ! function_exists('get_comment_count'))
{
	function get_comment_count($post_id)
	{
		$CI = get_instance();
		$CI->load->database();
		$query = $CI->db->get_where('post_meta',array('post_id'=>$post_id,'choice'=>'comments'));
		if($query->num_rows()>0)
		{
			$row = $query->row();
			if($row->value=='')
				return 0;
			else
				return count(json_decode($row->value));
		}
		else
			return 0;
	}
}


if ( ! function_exists('get_view_count'))
{
	function get_view_count($post_id,$from='all')
	{
		if (isset($_COOKIE['viewcookie'.$post_id])==FALSE && $from=='detail')
		{
			$CI = get_instance();
			$CI->load->database();

			$query = $CI->db->get_where('posts',array('id'=>$post_id));
			if($query->num_rows()>0)
			{
				$row = $query->row();
				$total_view = $row->total_view;
				$total_view++;
			}		
			else
				$total_view = 0;	
			$CI->db->update('posts',array('total_view'=>$total_view),array('id'=>$post_id));
			setcookie("viewcookie".$post_id, 1, time()+1800);
			return $total_view;
		}
		else
		{
			$CI = get_instance();
			$CI->load->database();

			$query = $CI->db->get_where('posts',array('id'=>$post_id));
			if($query->num_rows()>0)
			{
				$row = $query->row();
				return $row->total_view;
			}		
			else
				$total_view = 0;				
		}
	}
}

if ( ! function_exists('is_reported'))
{
	function is_reported($post_id)
	{
		$CI = get_instance();
		$user_name = $CI->session->userdata('user_name');
		if($user_name=='')
			return '';

		$key = '"post_'.$post_id.'"';
		$CI->load->database();
		$CI->db->like('reported',$key);
		$query = $CI->db->get_where('users',array('user_name'=>$user_name));
		if($query->num_rows()>0)
		{
			return 'reported';
		}
		else
			return '';
	}
}

if ( ! function_exists('is_liked'))
{
	function is_liked($post_id)
	{
		$CI = get_instance();
		$user_name = $CI->session->userdata('user_name');
		if($user_name=='')
			return '';

		$key = '"post_'.$post_id.'"';
		$CI->load->database();
		$CI->db->like('liked',$key);
		$query = $CI->db->get_where('users',array('user_name'=>$user_name));
		if($query->num_rows()>0)
		{
			return 'liked';
		}
		else
			return '';
	}
}

if ( ! function_exists('is_disliked'))
{
	function is_disliked($post_id)
	{
		$CI = get_instance();
		$user_name = $CI->session->userdata('user_name');
		if($user_name=='')
			return '';

		$key = '"post_'.$post_id.'"';
		$CI->load->database();
		$CI->db->like('disliked',$key);
		$query = $CI->db->get_where('users',array('user_name'=>$user_name));
		if($query->num_rows()>0)
		{
			return 'disliked';
		}
		else
			return '';
	}
}

if ( ! function_exists('get_all_properties_map_data'))
{
    function get_all_properties_map_data ($curr_lang)
    {
        $CI = get_instance();
        $CI->load->database();
        $query = $CI->db->get_where('posts',array('status !='=> 0));
        $data = array();
        $estates = array();

        foreach ($query->result() as $row)
        {
            $title = get_title_for_edit_by_id_lang($row->id,$curr_lang);
            $title = str_replace("'",'', $title);
            $estate = array();
            $estate['estate_id'] = $row->id;
            $estate['estate_title'] =  $title;
            $estate['featured_image_url'] = get_featured_photo_by_id($row->featured_img);
            $estate['latitude'] = $row->latitude;
            $estate['longitude'] = $row->longitude;
            $estate['estate_type'] = $row->type;
            $estate['estate_type_lang'] = lang_key($row->type);
            $estate['estate_status'] = $row->status;
            $estate['estate_price'] = show_price($row->total_price);
            $estate['estate_short_address'] = get_location_name_by_id($row->city).','.get_location_name_by_id($row->state).','.get_location_name_by_id($row->country);
            $estate['detail_link'] = site_url('property/'.$row->unique_id.'/'.dbc_url_title($title));
            array_push($estates,$estate);
        }

        $data['estates'] = $estates;
        return $data;
    }
}

if ( ! function_exists('show_square_unit'))
{
    function show_square_unit($unit)
    {
        if($unit == 'sqft'){
            $unit_text = 'ft<sup>2</sup>';
        }
        else if($unit == 'sqmeter'){
            $unit_text = 'm<sup>2</sup>';
        }
        else{
            $unit_text = $unit;
        }
        return $unit_text;
    }
}

if ( ! function_exists('get_distance_info_by_id'))
{
    function get_distance_info_by_id($id='')
    {
    	$CI = get_instance();
    	$CI->load->database();
    	$query = $CI->db->get_where('options',array('id'=>$id));
    	if($query->num_rows>0)
    	{
    		$row = $query->row();
    		return $row->values;
    	}
    	else
    	{
    		return '{}';
    	}
    }
}

if ( ! function_exists('load_page_local_data'))
{
	function load_page_local_data($alias='',$lang='')
	{
		$CI 	= get_instance();
		$CI->load->helper('file');
		$file_path = './dbc_config/locals-pages/'.$alias.'_'.$lang.'.html';

		$status = 0;
		$main_content = '';
		if(@file_exists($file_path))
		{
			$main_content = read_file('./dbc_config/locals-pages/'.$alias.'_'.$lang.'.html');			
			$status = 1;
		}

		$sidebar = '';
		if(@file_exists($file_path))
		{
			$sidebar = read_file('./dbc_config/locals-pages/'.$alias.'_'.$lang.'_sidebar.html');
			$status = 1;			
		}

		$data = array();
		$data['sidebar'] = $sidebar;
		$data['content'] = $main_content;
		$data['status']  = $status;
		return $data;

	}
}


/* End of file array_helper.php */
/* Location: ./system/helpers/array_helper.php */