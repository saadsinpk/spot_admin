<?php

    /*!
	 * POCKET v3.8
	 *
	 * http://www.droidoxy.com
	 * support@droidoxy.com
	 *
	 * Copyright DroidOXY ( http://www.droidoxy.com )
	 */
	 
	 require_once ("admin/controller/controller.php");
	 
	 $offer_delete = $dbo->prepare("DELETE FROM offer");
	 $offer_delete->execute();

	 $offerwalls = $dbo->prepare("SELECT * FROM offerwalls WHERE status = 1");
	 $offerwalls->execute();
	 $row = $offerwalls->fetchAll(PDO::FETCH_ASSOC);
	 foreach ($row as $offer_walls_key => $offer_walls_value) {
	 	if($offer_walls_value['name'] == 'KiwiWall') {
			$url = $offer_walls_value['url'];

			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

			//for debug only!
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

			$resp = curl_exec($curl);
			curl_close($curl);

			$offers_kiwiwall = array();
			if(!empty($resp)) {
				$offers_kiwiwall = json_decode($resp);
			}
			if(isset($offers_kiwiwall->offers)) {
				$offers_kiwiwall = $offers_kiwiwall->offers;
			}

			foreach ($offers_kiwiwall as $kiwiwall_key => $kiwiwall_value) {
				if(isset($kiwiwall_value->id)) {
					$insert_id = $kiwiwall_value->id;
					$insert_name = $kiwiwall_value->name;
					$insert_requirements = $kiwiwall_value->instructions;
					$insert_icon = $kiwiwall_value->logo;
					$insert_payout = $kiwiwall_value->amount;
					$insert_url = $kiwiwall_value->link;
					$insert_devices = $kiwiwall_value->os;
					$insert_categories = $kiwiwall_value->category;


		            $insert_sql = "INSERT INTO offer (id, name, requirements, icon, payout, url, device, categories) VALUES ('$insert_id', '$insert_name', '$insert_requirements', '$insert_icon', '$insert_payout', '$insert_url', '$insert_devices', '$insert_categories')";
		            $insert_stmt = $dbo->prepare($insert_sql);
		            $insert_stmt->execute();

				}
			}
		}

	 	if($offer_walls_value['name'] == 'Wannads') {

			$url_two = $offer_walls_value['url'];

			$curl_two = curl_init($url_two);
			curl_setopt($curl_two, CURLOPT_URL, $url_two);
			curl_setopt($curl_two, CURLOPT_RETURNTRANSFER, true);

			//for debug only!
			curl_setopt($curl_two, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl_two, CURLOPT_SSL_VERIFYPEER, false);

			$resp_two = curl_exec($curl_two);
			curl_close($curl_two);

			$offers_wannads = array();

			if(!empty($resp_two)) {
				$offers_wannads = json_decode($resp_two);
			}

			foreach ($offers_wannads as $wannads_key => $wannads_value) {
				if(isset($wannads_value->id)) {
					$insert_id = $wannads_value->id;
					$insert_name = $wannads_value->title;
					$insert_requirements = $wannads_value->conversion_point;
					if(isset($wannads_value->img_url)) {
						$insert_icon = $wannads_value->img_url;
					} else {
						$insert_icon = '';
					}
					$insert_payout = $wannads_value->revenue;
					$insert_url = $wannads_value->offer_url;
					$insert_devices = $wannads_value->devices;
					$insert_categories = $wannads_value->categories;

					foreach ($insert_devices as $devices_key => $devices_value) {
						foreach ($insert_categories as $categories_key => $categories_value) {
				            $insert_sql = "INSERT INTO offer (id, name, requirements, icon, payout, url, device, categories) VALUES ('$insert_id', '$insert_name', '$insert_requirements', '$insert_icon', '$insert_payout', '$insert_url', '$devices_value', '$categories_value')";
				            $insert_stmt = $dbo->prepare($insert_sql);
				            $insert_stmt->execute();

						}
					}
				}
			}
		}


	 	if($offer_walls_value['name'] == 'Ad GateMedia') {
			$url_three = $offer_walls_value['url'];

			$curl_three = curl_init($url_three);
			curl_setopt($curl_three, CURLOPT_URL, $url_three);
			curl_setopt($curl_three, CURLOPT_RETURNTRANSFER, true);

			//for debug only!
			curl_setopt($curl_three, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl_three, CURLOPT_SSL_VERIFYPEER, false);

			$resp_three = curl_exec($curl_three);
			curl_close($curl_three);

			$offers_adgatemedia = array();

			if(!empty($resp_three)) {
				$offers_adgatemedia = json_decode($resp_three);
			}
			if(isset($offers_adgatemedia->data)) {
				foreach($offers_adgatemedia->data as $adgatemedia_key => $adgatemedia_value) {
					$insert_id = $adgatemedia_value->id;
					$insert_name = $adgatemedia_value->name;
					$insert_requirements = $adgatemedia_value->requirements;
					if(isset($adgatemedia_value->creatives->icon)) {
						$insert_icon = $adgatemedia_value->creatives->icon;
					} else {
						$insert_icon = '';
					}
					$insert_payout = $adgatemedia_value->payout;
					$insert_url = $adgatemedia_value->click_url;
					$insert_devices = $adgatemedia_value->user_agent;
					$insert_categories = $adgatemedia_value->categories;

					foreach ($insert_devices as $devices_key => $devices_value) {
						foreach ($insert_categories as $categories_key => $categories_value) {

				            $insert_sql = "INSERT INTO offer (id, name, requirements, icon, payout, url, device, categories) VALUES ('$insert_id', '$insert_name', '$insert_requirements', '$insert_icon', '$insert_payout', '$insert_url', '$devices_value', '$categories_value')";
				            $insert_stmt = $dbo->prepare($insert_sql);
				            $insert_stmt->execute();

						}
					}
				}
			}
		}



	 	if($offer_walls_value['name'] == 'CPA Lead') {
			$url_four = $offer_walls_value['url'];

			$curl_four = curl_init($url_four);
			curl_setopt($curl_four, CURLOPT_URL, $url_four);
			curl_setopt($curl_four, CURLOPT_RETURNTRANSFER, true);

			//for debug only!
			curl_setopt($curl_four, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl_four, CURLOPT_SSL_VERIFYPEER, false);

			$resp_four = curl_exec($curl_four);
			curl_close($curl_four);

			if(!empty($resp_four)) {
				$offers_cpalead = json_decode($resp_four);
			}
			if(isset($offers_cpalead->offers)) {
				foreach ($offers_cpalead->offers as $cpalead_key => $cpalead_value) {
					$insert_id = $cpalead_value->campid;
					$insert_name = $cpalead_value->title;
					$insert_requirements = $cpalead_value->description;
					$insert_icon = '';
					if(isset($cpalead_value->creatives[0])) {
						if(isset($cpalead_value->creatives[0]->url)) {
							$insert_icon = $cpalead_value->creatives[0]->url;
						}
					}
					$insert_payout = $cpalead_value->epc;
					$insert_url = $cpalead_value->link;
					$insert_devices = $cpalead_value->mobile_app_type;
					$insert_categories = $cpalead_value->category_name;

		            $insert_sql = "INSERT INTO offer (id, name, requirements, icon, payout, url, device, categories) VALUES ('$insert_id', '$insert_name', '$insert_requirements', '$insert_icon', '$insert_payout', '$insert_url', '$insert_devices', '$insert_categories')";
		            $insert_stmt = $dbo->prepare($insert_sql);
		            $insert_stmt->execute();
				}
			}
		}

	 }
	
?>
