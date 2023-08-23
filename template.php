class HubspotIntegrator
{

	public $error_notification_email;

	public function __construct()
	{
		$this->error_notification_email = 'alex@cityranked.com';
		add_action('gform_after_submission_1', [$this, 'set_post_content'], 10, 2); // modal contact 
		add_action('gform_after_submission_4', [$this, 'set_commercial_content'], 10, 2); // commericial contact 
	}

	// modal contact
	public function set_post_content($entry, $form)
	{

		error_log('commericial ' . $entry[19]);
		if ($entry[19] == 'commercial') {

			error_log('firstname: ' . strval($entry['1']));
			error_log('lastname: ' . strval($entry['8']));
			error_log('email: ' . $entry['5']);
			error_log('phone: ' . $entry['2']);
			error_log('zip: ' . $entry['6']);
			error_log('primary point of intereset: ' . strval($entry['7']));
			error_log("ipAddress: " . strval($entry['21']));
			error_log("pageURI: " . strval($entry['4']));
			error_log("pageName" . strval($entry['3']));




			date_default_timezone_set("UTC");
			$time = round(microtime(true) * 1000);

			$hubspot_form = (object) array(
				// 			"submittedAd" => time(),
				"submittedAt" => $time,
				"fields" => array(
					(object) array(
						"name" => "firstname",
						"value" =>	$entry['1']
					),
					(object) array(
						"name" => "lastname",
						"value" =>	$entry['8']
					),
					(object) array(
						"name" => "email",
						"value" =>	$entry['5']
					),
					(object) array(
						"name" => "phone",
						"value" =>	$entry['2']
					),
					(object) array(
						"name" => "zip",
						"value" =>	$entry['6']
					),
					(object) array(
						"name" => "primary_product_of_interest",
						"value" =>	$entry['7']
					),
					(object) array(
						"name" => "res_com",
						"value" =>	'Commercial'
					),
					(object) array(
						"name" => "leadsource", // @todo change here 
						"value" =>	'Pay Per Click'
					),
					(object) array(
						"name" => "business_unit",
						"value" =>	'Pest'
					),
					(object) array( // new
						"name" => "country",
						"value" =>	'United States' // 
					),
					(object) array(
						"name" => "campaign_name", // @todo change here
						"value" =>	'CC-2023-PTX-General Pest-PPC' // update campaign name
					)
				),
				"context" => (object) array(
					"ipAddress" => $entry['21'],
					"pageUri" =>	$entry['4'],
					"pageName" => $entry['3']
				)
			);
			$send_parcel = json_encode($hubspot_form);
			$url = 'https://api.hsforms.com/submissions/v3/integration/submit/2602531/a3321712-2284-4474-89fe-493150310387'; // @todo: change form id here

			$response = wp_remote_post($url, array(
				'body'    => $send_parcel,
				'headers' => array(
					'Content-Type' => 'application/json',
					'Authorization' => 'Authorization: Bearer pat-na1-39774922-e53e-4edb-9683-b5690ee3456f header'
				),
			));

			if (is_wp_error($response)) {
				// Handle WP_Error
				$error_message = $response->get_error_message();
				error_log('Error sending the request: ' . $error_message);

				// Send an email notification
				$subject = 'HubSpot API Error';
				$message = 'An error occurred while sending data to HubSpot API: ' . $error_message;
				$to = $this->error_notification_email; // Replace with your email address
				wp_mail($to, $subject, $message);
			} else {
				$response_code = wp_remote_retrieve_response_code($response);
				if ($response_code === 200) {
					// Success
					error_log('Request was successful. Response code: ' . $response_code);
				} else {
					// Request failed with non-200 response code
					error_log('Request failed. Response code: ' . $response_code);

					// Send an email notification
					$subject = 'HubSpot API Error';
					$message = 'HubSpot API responded with a non-200 status code: ' . $response_code;
					$to = $this->error_notification_email; // Replace with your email address
					wp_mail($to, $subject, $message);
				}
			}


			error_log(print_r($response, true));
		}
	}

	// commerical contact form 
	function set_commercial_content($entry, $form)
	{
		if ($entry[22] == 'commercial') {


			date_default_timezone_set("UTC");
			$time = round(microtime(true) * 1000);


			$hubspot_form = (object) array(
				// 			"submittedAd" => time(),
				"submittedAt" => $time,
				"fields" => array(
					(object) array(
						"name" => "firstname",
						"value" =>	$entry['1']
					),
					(object) array(
						"name" => "lastname",
						"value" =>	$entry['8']
					),
					(object) array(
						"name" => "email",
						"value" =>	$entry['5']
					),
					(object) array(
						"name" => "phone",
						"value" =>	$entry['2']
					),
					(object) array(
						"name" => "zip",
						"value" =>	$entry['6']
					),
					(object) array(
						"name" => "primary_product_of_interest",
						"value" =>	$entry['23']
					),
					(object) array(
						"name" => "res_com",
						"value" =>	'Commercial'
					),
					(object) array( // new
						"name" => "country",
						"value" =>	'United States' // 
					),
					(object) array(
						"name" => "company",
						"value" =>	$entry['20']
					),
					(object) array(
						"name" => "leadsource",
						"value" =>	'Pay Per Click'
					),
					(object) array(
						"name" => "business_unit",
						"value" =>	'Pest'
					),
					(object) array(
						"name" => "campaign_name",
						"value" =>	'CC-2023-PTX-General Pest-PPC-Commercial Leads'
					)
				),
				"context" => (object) array(
					"ipAddress" => $entry['21'],
					"pageUri" =>	$entry['4'],
					"pageName" => $entry['3']
				)
			);
			$send_parcel = json_encode($hubspot_form);
			$url = 'https://api.hsforms.com/submissions/v3/integration/submit/2602531/2ffdcb07-a050-4a98-ac22-95413b5886b8';
			$response = wp_remote_post($url, array(
				'body'    => $send_parcel,
				'headers' => array(
					'Content-Type' => 'application/json',
				),
			));

			if (is_wp_error($response)) {
				// Handle WP_Error
				$error_message = $response->get_error_message();
				error_log('Error sending the request: ' . $error_message);

				// Send an email notification
				$subject = 'HubSpot API Error';
				$message = 'HubSpot API responded with a non-200 status code for form ' . rgar($form, 'title') . ': ' . $error_message;
				$to = $this->error_notification_email; // Replace with your email address
				wp_mail($to, $subject, $message);
			} else {
				$response_code = wp_remote_retrieve_response_code($response);
				if ($response_code === 200) {
					// Success
					error_log('Request was successful. Response code: ' . $response_code);
				} else {
					// Request failed with non-200 response code
					error_log('Request failed. Response code: ' . $response_code);

					// Send an email notification
					$subject = 'HubSpot API Error';
					$message = 'HubSpot API responded with a non-200 status code for form ' . rgar($form, 'title') . ': ' . $response_code;
					$to = $this->error_notification_email; // Replace with your email address
					wp_mail($to, $subject, $message);
				}
			}

			error_log(print_r($response, true));
		}
	}
}
